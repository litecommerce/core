<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_InventoryTracking_Model_Product extends XLite_Model_Product implements XLite_Base_IDecorator
{
    /**
     * inventory 
     * 
     * @var    XLite_Module_InventoryTracking_Model_Inventory
     * @access protected
     * @since  3.0.0
     */
    protected $inventory = null;


    /**
     * Maximal available amount
     *
     * @return int
     * @access public
     * @since  3.0.0
     */
    public function getMaxPurchaseLimit()
    {
        $result = parent::getMaxPurchaseLimit();

        $inventory = $this->getInventory();
        if ($inventory->is('found') && 0 < $inventory->get('amount')) {
            $result = min($result, $inventory->get('amount'));
        }

        return $result;
    }



    public function __construct($id = null)
    {
        $this->fields["tracking"] = 0;
        $this->fields["sku_variants"] = '';
        parent::__construct($id);
    }

    function getInventory()
    {
        if (is_null($this->inventory)) {
            $this->inventory = new XLite_Module_InventoryTracking_Model_Inventory();
            $found = $this->inventory->find("inventory_id='".$this->get("product_id")."' AND enabled=1");
            $this->inventory->set("found", $found);
        }
        return $this->inventory;
    }
    
    function filter()
    {
        $result = parent::filter();

        // check for active inventory card amount
        if (!$this->xlite->is("adminZone") && $this->getComplex('config.InventoryTracking.exclude_product')) {
            $result &= $this->isInStock();
        }

        return $result;
    }

    /**
     * Check - product is in-stock or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isInStock()
    {
        $result = true;

        $options = (array) $this->get('productOptions');

        $max_options = 0;
        if ($this->get('tracking') && $options) {

            // calculate the amount of options cominations for tracking with product options
            foreach ($options as $opt) {
                if (in_array(strtolower($opt->get('opttype')), array('radio button', 'selectbox'))) {
                    if ($max_options == 0) {
                        $max_options = 1;
                    }

                    $cnt = count(explode("\n", $opt->get('options')));

                    if ($cnt > 0) {
                        $max_options *= $cnt;
                    }
                }
            }
        }

        if ($max_options && $this->get('tracking')) {
            $inv = new XLite_Module_InventoryTracking_Model_Inventory();
            $result = $inv->count('inventory_id LIKE \'' . $this->get('product_id') . '|%\' AND amount <= 0') < $max_options;

        } else {
            $result = !$this->getInventory()->get('found') || $this->getInventory()->get('amount') > 0;
        }

        return $result;
    }

    /**
     * Check - product is out-of-stock or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isOutOfStock()
    {
        return !$this->isInStock();
    }
    
    function delete()
    {
        // delete inventory card for this product
        $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
        $product_id = $this->get("product_id");
        $inventories = $inventory->findAll("inventory_id='$product_id' OR inventory_id LIKE '$product_id" . "|%'");
        if (is_array($inventories)) {
            foreach($inventories as $inventory_) {
                $inventory_->delete();
            }
        }
        parent::delete();
    }

    function parseInventoryOptions($properties)
    {
        $options = $properties["options"];
        $options = explode("\r\n",$options);
        foreach($options as $key => $option) {
           if (strpos($option, "=")) {
                $options[$key] = substr($option, 0, strpos($option, "="));
            } else {
                $options[$key] = substr($option, 0);
            }
        }
        return $options;
    }

    function updateInventory($properties)
    {
        if ($properties['opttype'] == 'Text'|| $properties['opttype'] == 'Textarea') return;    
        
        $option = new XLite_Module_ProductOptions_Model_ProductOption($properties['option_id']);
        $old_properties = $option->get('properties');
        $inventory = new XLite_Module_InventoryTracking_Model_Inventory();

        $inventories = $inventory->findAll("inventory_id LIKE '".$properties['product_id']."|%".addslashes($old_properties['optclass'])."%'");
        if (empty($inventories)) return; 

        if($properties['optclass']!=$old_properties['optclass'])    
            foreach($inventories as $inventory_) {
                $inventory = new XLite_Module_InventoryTracking_Model_Inventory($inventory_->getComplex('properties.inventory_id'));
                $inventory->delete();
                $inventory->set('properties',$inventory_->get('properties'));
                $inventory->set('inventory_id',preg_replace('/'.$old_properties['optclass'].':/',$properties['optclass'].":",$inventory_->getComplex('properties.inventory_id')));
                $inventory->create();
            }

        $options = $this->parseInventoryOptions($properties);
        if(empty($options)) return;
        foreach($options as $key => $option) {
            $options[$key] = $properties['optclass'].':'.$option;
        }

        $inventories = $inventory->findAll("inventory_id LIKE '".$properties['product_id']."|%".addslashes($properties['optclass'])."%'");

        foreach($inventories as $inventory_) {
            $inventory_options = $inventory_->parseOptions($inventory_->get('inventory_id'));
            foreach($inventory_options as $key => $inventory_option) {
                if (preg_match("/".$properties['optclass']."/",$inventory_option)) {
                    $matched = $key;
                }
            }
            if (!in_array($inventory_options[$matched],$options)) {
                $inventory_->delete();
            }
        }
    }
        
    function deleteInventory($properties)
    {
        $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
        $deleted_inventories = $inventory->findAll("inventory_id LIKE '".$properties['product_id']."|%".addslashes($properties['optclass'])."%'");
             foreach($deleted_inventories as $deleted_inventory) {
                $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
                $options = $inventory->parseOptions($deleted_inventory->get('inventory_id'));
                if (!empty($options))
                foreach($options as $key => $option) {
                    if (strstr($option,$properties['optclass'])) {
                        unset($options[$key]);
                    }
                }
                $inventory_id = $properties['product_id'];
                if (!empty($options)) {
                    foreach($options as $option) {
                        $inventory_id .= "|".$option;
                    }
                }
                $inventory->set("properties",$deleted_inventory->get('properties'));
                $inventory->set("inventory_id",$inventory_id);     
                $deleted_inventory->delete();
                if (!$deleted_inventory->find("inventory_id = '".$inventory_id."'")) {
                    $inventory->create();
                }
            }

    }
    
    function cloneInventory($product, $options = false)
    {
         $id = $product->get("product_id");

         if (!$options)
        {
            $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
            if(!$inventory->find("inventory_id = '".$this->get("product_id")."'")) {
                return $product;
            }
            $inventory->read();
            $clone_inventory = $inventory;
            $clone_inventory->set("inventory_id", $id);
            $clone_inventory->create();
            $product->set("tracking", $this->get("tracking"));
            $product->update();
            return $product;
        } else 
        {
            $inventories = new XLite_Module_InventoryTracking_Model_Inventory();
            $options_inventory = $inventories->findAll("inventory_id LIKE '".$this->get("product_id")."|%'", "order_by");        
            foreach ($options_inventory as $option_inventory) {
                $option_inventory->read();
                $inventories = $option_inventory;

                $inventory_id = explode("|", $inventories->get("inventory_id"), 2);
                $inventory_id[0] = $id;
                $inventory_id = implode("|", $inventory_id);
                
                $inventories->set("inventory_id", $inventory_id);
                $inventories->create();
            }
            $product->set("tracking", $this->get("tracking"));
            $product->update();

            return $product;
        }
    }
    
    function cloneObject()
    {    
        $this->xlite->set("ITisCloneProduct", true);
        $p = parent::cloneObject();

        $p->set("tracking", 0);
        $p->update(); 
        if ($this->config->getComplex('InventoryTracking.clone_inventory')) {
            $p  = $this->cloneInventory($p);
        }
        $this->xlite->set("ITisCloneProduct", false);
        return $p;
    }        

    function createDefaultInventory()
    {
        
        if (!$this->xlite->get("ITisCloneProduct") && $this->config->getComplex('InventoryTracking.create_inventory')) {
            $inventory = new XLite_Module_InventoryTracking_Model_Inventory();
            $inventory->set("inventory_id",$this->get("product_id"));
            $inventory->set("amount",$this->config->getComplex('InventoryTracking.inventory_amount'));
            $inventory->set("low_avail_limit",$this->config->getComplex('InventoryTracking.low_amount'));
            $inventory->set("enabled",1);
            $inventory->create();
        }
    }

    function create()
    {
        $result = parent::create();

        $this->createDefaultInventory();

        return $result;
    }

    function collectGarbage()
    {
        parent::collectGarbage();

        $products_table = $this->db->getTableByAlias("products");
        $inventories_table = $this->db->getTableByAlias("inventories");
        $sql = "SELECT i.inventory_id FROM $inventories_table i LEFT OUTER JOIN $products_table p ON i.inventory_id=p.product_id WHERE p.product_id IS NULL";
        $result = $this->db->getAll($sql);

        if (is_array($result) && count($result) > 0) {
            foreach ($result as $info) {
                $pi = new XLite_Module_InventoryTracking_Model_Inventory($info["inventory_id"]);
                $pi->delete();
            }
        }
    }

    function advancedSearch($substring, $sku = null, $category_id = null, $subcategory_search = false, $fulltext = false, $onlyindexes = false) 
    {
        if ($this->xlite->get("ProductOptionsEnabled")) {
            return $this->advancedSearchWithSku($substring, $sku, $category_id, $subcategory_search, $fulltext, $onlyindexes);
        }
        return parent::advancedSearch($substring, $sku, $category_id, $subcategory_search, $fulltext, $onlyindexes);
    } 

    function advancedSearchWithSku($substring, $sku = null, $category_id = null, $subcategory_search = false, $fulltext = false, $onlyindexes = false) 
    {
        // compatibility check:
        if (method_exists($this, "_beforeAdvancedSearch")) {
            $this->_beforeAdvancedSearch($substring, $sku, $category_id, $subcategory_search, $fulltext, $onlyindexes);
        }

        if (empty($category_id)) { // is an empty string
            $category_id = null;
        }
        if (empty($subcategory_search)) { // is an empty string
            $subcategory_search = false;
        }
        $query = null; 
        $table = $this->db->getTableByAlias($this->alias);
        if (!empty($substring)) {
            $substring = addslashes($substring);
            $query = "$table.name LIKE '%$substring%' OR $table.brief_description LIKE '%$substring%' OR $table.description LIKE '%$substring%' OR $table.sku LIKE '%$substring%'";

            $condition = $this->getProductSkuCondition($substring);
            if (!empty($condition)) {
                $query .= " OR ($condition)";
            }
            $query = "($query)";
        } elseif (!is_null($sku) && !empty($sku)) {
            // search by SKU only
            $query = "$table.sku LIKE '%$sku%'";

            $condition = $this->getProductSkuCondition($sku);
            if (!empty($condition)) {
                $query .= " OR ($condition)";
            }
            $query = "($query)";
        }
        if (!is_null($category_id)) {
            $category = new XLite_Model_Category($category_id);
            $result = $category->getProducts($query, null, false);
            $result = $this->_assocArray($result, "product_id");
            $categories = $category->getSubcategories();
            if ($subcategory_search) {
                for ($i=0; $i<count($categories); $i++) {
                    $res1 = $this->advancedSearchWithSku($substring, $sku, $categories[$i]->get("category_id"), true, true, $onlyindexes);
                    $result = array_merge($result, $this->_assocArray($res1, "product_id"));
                }
            }
            return array_values($result);
        } else {
            $p = new XLite_Model_Product();
            $p->fetchKeysOnly = true;
            if ($onlyindexes) {
                $p->fetchObjIdxOnly = true;
            }
            $result = $p->findAll($query);
        }
        return $result;
    } 
 
    function getProductSkuCondition($substring, $where = null) 
    {
        $condition = "sku_variants LIKE '%$substring%'";
        return $condition;
    } 

/**
    function changeInventorySku($old_sku, $new_sku) 
    {
        $skus = $this->get("sku_variants");
        $sku_array = explode("|", $skus);

        // remove empty elements
        foreach ($sku_array as $k=>$v) {
            if (empty($v)) unset($sku_array[$k]);
        }
        // reindex array
        $sku_array = array_values($sku_array);

        $index = array_search($old_sku, $sku_array);
        if ($index !== false) {
            if (empty($new_sku)) {
                // remove found sku
                array_splice($sku_array, $index, 1);
            } else {
                // replace found sku
                array_splice($sku_array, $index, 1, $new_sku);
            }
        } else {
            $index = array_search($old_sku, $sku_array);
            if ($index === false) {
                // add a new sku
                $sku_array[] = $new_sku;
            } else {
                // do nothing
                return false;
            }
        }
        $skus = implode("|", $sku_array);
        $this->set("sku_variants", "|$skus|");
        return true;
    } 
/**/

    function updateInventorySku() 
    {
        $product_id = addslashes($this->get("product_id"));
        $inv = new XLite_Module_InventoryTracking_Model_Inventory();
        $invs = $inv->findAll("inventory_id LIKE '$product_id|%'");
        $sku_variants = "|";
        foreach ($invs as $i) {
            $sku = $i->get("inventory_sku");
            if (!empty($sku)) {
                $sku_variants .= $i->get("inventory_sku")."|";
            }
        }
        $this->set("sku_variants", $sku_variants);
        $this->update();
    } 

    function _constructSearchArray($start_price, $end_price, $start_weight, $end_weight, $sku) 
    {
        // check for AdvancedSearch module version compatibility:
        $parent_class = get_parent_class($this);
        $classMethods = array_map("strtolower", get_class_methods($parent_class));
        if (!in_array(strtolower("_constructSearchArray"), $classMethods)) return array();

        $result = parent::_constructSearchArray($start_price, $end_price, $start_weight, $end_weight, $sku);
        if (empty($sku)) return $result;
        if (!$this->xlite->get("ProductOptionsEnabled")) return $result;

        // extend default advanced search conditions:
        $old_sku_condition = "sku LIKE '%".addslashes($sku)."%'";
        $conditions = array();
        $conditions[] = $old_sku_condition;
        $conditions[] = "sku_variants LIKE '%".addslashes($sku)."%'";
        $new_sku_condition = "(".implode(" OR ", $conditions).")";

        $index = array_search($old_sku_condition, $result);
        if ($index === false) {
            $result[] = $new_sku_condition;
        } else {
            array_splice($result, $index, 1, $new_sku_condition);
        }

        return $result;
    } 

}
