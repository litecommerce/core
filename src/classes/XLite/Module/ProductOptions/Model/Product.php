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

namespace XLite\Module\ProductOptions\Model;

/**
 * Product
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Product extends \XLite\Model\Product implements \XLite\Base\IDecorator
{
    /**
     * Option groups (relation)
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @OneToMany (targetEntity="XLite\Module\ProductOptions\Model\OptionGroup", mappedBy="product", cascade={"persist","remove"})
     */
    protected $optionGroups;

    /**
     * Product options list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $productOptions = null;

    /**
     * Constructor
     * 
     * @param mixed $id Object id
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->fields['expansion_limit'] = 0;
    }
    
    /**
     * Get product options list
     * 
     * @return array of \XLite\Module\ProductOptions\Model\ProductOption
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProductOptions()
    {
        if (is_null($this->productOptions)) {
            $po = new \XLite\Module\ProductOptions\Model\ProductOption();
            $this->productOptions = $po->findAll('product_id = \'' . $this->get('product_id') . '\'');
        }

        return $this->productOptions;
    }

    /**
     * Get default product options 
     * 
     * @return array (pairs optclass => option_id)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDefaultProductOptions()
    {
        $options = array();

        foreach ($this->getProductOptions() as $option) {
            if (
                $option->get('opttype') == 'SelectBox'
                || $option->get('opttype') == 'Radio button'
            ) {
                foreach ($option->getProductOptions() as $opt) {
                    $options[$option->get('optclass')] = $opt->option_id;
                    break;
                }
            }
        }

        if ($this->hasExceptions()) {
            // TODO - add exception checking
        }

        return $options;
    }

    /**
     * Get product options list length 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProductOptionsNumber()
    {
        $list = $this->getProductOptions();

        return is_array($list) ? count($list) : 0;
    }

    /**
     * Check - has product options list or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasOptions()
    {
        $po = new \XLite\Module\ProductOptions\Model\ProductOption();
        return $po->hasOptions($this->get('product_id'));
    }

    function getOptionExceptions()
    {
        $pe = new \XLite\Module\ProductOptions\Model\OptionException();
        return $pe->findAll("product_id='".$this->get('product_id')."'");
    }

    function hasExceptions()
    {
        $pe = new \XLite\Module\ProductOptions\Model\OptionException();
        return $pe->hasExceptions($this->get('product_id'));
    }

    function hasOptionValidator()
    {
        $pv = new \XLite\Module\ProductOptions\Model\OptionValidator();
        $pv->set('product_id', $this->get('product_id'));
        return strlen(trim($pv->get('javascript_code')));
    }

    function getOptionValidator()
    {
        $pv = new \XLite\Module\ProductOptions\Model\OptionValidator();
        $pv->set('product_id', $this->get('product_id'));

        return $pv->get('javascript_code');
    }

    function isDisplayPriceModifier()
    {
        return $this->xlite->get('WholesaleTradingEnabled') ? $this->is('priceAvailable') : true;
    }

    function delete()
    {
        // delete inventory card set with this product options
        // NOTE: this requires InventoryTracking module turned ON
        if ($this->xlite->get('InventoryTrackingEnabled')) {
            $inventory = new \XLite\Module\InventoryTracking\Model\Inventory();
            $product_id = $this->get('product_id');
            $inventories = $inventory->findAll("inventory_id='$product_id' OR inventory_id LIKE '$product_id" . "|%'");
            if (is_array($inventories)) {
                foreach ($inventories as $inventory_) {
                    $inventory_->delete();
                }
            }
        }
        // delete product options, exceptions and javascript validator
        $option = new \XLite\Module\ProductOptions\Model\ProductOption();
        $exception = new \XLite\Module\ProductOptions\Model\OptionException();
        $validator = new \XLite\Module\ProductOptions\Model\OptionValidator();
        $this->db->query("DELETE FROM ".$option->getTable(). " WHERE product_id='".$this->get('product_id')."'");
        $this->db->query("DELETE FROM ".$exception->getTable(). " WHERE product_id='".$this->get('product_id')."'");
        $this->db->query("DELETE FROM ".$validator->getTable(). " WHERE product_id='".$this->get('product_id')."'");

        // delete product
        parent::delete();
    }
    
    function cloneObject()
    {
        $p = parent::cloneObject();

        if ($this->config->ProductOptions->clone_product_options) {

            $id = $p->get('product_id');

            $clone_option = new \XLite\Module\ProductOptions\Model\ProductOption();
            $options = $clone_option->findAll("product_id='".$this->get('product_id')."'");
        
            if (empty($options))    return $p;
            foreach ($options as $option) {
                $clone_option = new \XLite\Module\ProductOptions\Model\ProductOption();
                $clone_option->set('properties',$option->get('properties'));
                $clone_option->set('option_id',"");
                $clone_option->set('product_id',$id);
                $clone_option->create();
            }
 
            // Clone validator JS code
            $validator = new \XLite\Module\ProductOptions\Model\OptionValidator($this->get('product_id'));
            $js_code = $validator->get('javascript_code');
            if ( strlen(trim($js_code)) > 0 ) {
                $validator->set('product_id', $id);
                $validator->set('javascript_code', $js_code);
                $validator->create();
            }
            
            // Clone options exceptions
            $foo = new \XLite\Module\ProductOptions\Model\OptionException();
            $exceptions = $foo->findAll("product_id = '" . $this->get('product_id') . "'");
            foreach ($exceptions as $exception) {
                $optionException = new \XLite\Module\ProductOptions\Model\OptionException();
                $optionException->set('product_id', $id);
                $optionException->set('exception', $exception->get('exception'));
                $optionException->create();
            }
            
            if ($this->xlite->get('InventoryTrackingEnabled')&& $this->config->InventoryTracking->clone_inventory) {
                $this->cloneInventory($p, true);
            }
        }
        return $p;
    }

    /**
    * Remove unused ProductOptions records
    */
    function collectGarbage()
    {
        parent::collectGarbage();

        $classes = array(
            "ProductOption" => array(
                "key" => "option_id",
                "table" => "product_options",
            ),
            "OptionException" => array(
                "key" => "option_id",
                "table" => "product_options_ex",
            ),
            "OptionValidator" => array(
                "key" => "product_id",
                "table" => "product_options_js",
            ),
        );

        $products_table = $this->db->getTableByAlias('products');
        foreach ($classes as $class_name => $desc) {
            $check_table = $this->db->getTableByAlias($desc['table']);
            $sql = "SELECT o.product_id AS object_product_id, o.".$desc['key']." AS object_key FROM $check_table o LEFT OUTER JOIN $products_table p ON o.product_id=p.product_id WHERE p.product_id IS NULL";
            $result = $this->db->getAll($sql);

            if (is_array($result) && count($result) > 0) {

                $class = '\XLite\Module\ProductOptions\Model\\' . $class_name;

                foreach ($result as $info) {
                    if ($class_name == "ProductOption" && $info['object_product_id'] == 0) {
                        continue;    // global product option
                    }
                    $obj = new $class($info['object_key']);
                    $obj->delete();
                }
            }
        }
    }

    public function isInStock()
    {
        return $this->xlite->getComplex('mm.activeModules.InventoryTracking')
            ? parent::isInStock()
            : true;
    }

    public function isOutOfStock()
    {
        return !$this->isInStock();
    }

    function _importCategory($product, $properties, $default_category)
    {
        $oldCategories = array();
        $categories = $product->get('categories');
        if (is_array($categories)) {
            foreach ($categories as $cat) {
                $oldCategories[] = $cat->get('category_id');
            }
        }

        parent::_importCategory($product, $properties, $default_category);

        $product->updateGlobalProductOptions($oldCategories);
    }

    function updateGlobalProductOptions($oldCategories = array())
    {
        $product = $this;

        $newCategories = array();
        $categories = $product->get('categories');

        if (is_array($categories)) {
            foreach ($categories as $cat) {
                $newCategories[] = $cat->get('category_id');
            }
        }

        $deleteOnly = array_diff($oldCategories, $newCategories);
        $addOnly = array_diff($newCategories, $oldCategories);

        if (count($deleteOnly) > 0) {
            $productOptions = $product->getProductOptions();
            $globalProductOptions = array();
            foreach ($productOptions as $po) {
                if ($po->get('parent_option_id') > 0) {
                    $gpo = new \XLite\Module\ProductOptions\Model\ProductOption($po->get('parent_option_id'));
                    $categories = $gpo->getCategories();
                    $result = array_intersect($categories, $deleteOnly);
                    if (count($result) > 0 && count(array_intersect($categories, $newCategories)) == 0) {
                        $po->delete();
                    }
                }
            }
        }

        if (count($addOnly) > 0) {
            $gpo = new \XLite\Module\ProductOptions\Model\ProductOption();
            $gpos = $gpo->findAll("product_id='0' AND parent_option_id='0'");
            if (is_array($gpos)) {
                foreach ($gpos as $gp) {
                    $categories = $gp->getCategories();
                    $result = array_intersect($categories, $addOnly);

                    if (count($result) > 0 || (is_array($categories) && count($categories) == 0)) {
                        $productOptions = $product->getProductOptions();
                        $need_update = true;
                        foreach ($productOptions as $po) {
                            if ($po->get('parent_option_id') == $gp->get('option_id')) {
                            $need_update = false;
                            }
                        }
                        if ($need_update) {
                            $po = new \XLite\Module\ProductOptions\Model\ProductOption();
                            $po->set('properties', $gp->get('properties'));
                            $po->set('option_id', null);
                            $po->set('product_id', $this->get('product_id'));
                            $po->set('parent_option_id', $gp->get('option_id'));
                            $po->create();
                        }
                    }
                }
            }
        }
    }
}
