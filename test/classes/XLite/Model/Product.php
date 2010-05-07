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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Product 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Product extends XLite_Model_Abstract
{
    /**
     * Object properties (table filed => default value)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $fields = array(
        'product_id'        => 0,    // primary key
        'sku'               => '',
        'name'              => '',
        'description'       => '',
        'brief_description' => '',
        'meta_tags'         => '',
        'meta_title'        => '',
        'meta_desc'         => '',
        'price'             => 0.00,
        'sale_price'        => 0.00,
        'enabled'           => '1',
        'order_by'          => 0,
        'thumbnail_type'    => '',
        'weight'            => 0,
        'image_type'        => '',
        'tax_class'         => '',
        'free_shipping'     => 0,
        'clean_url'         => '',
    );    

    /**
     * Auto-increment file name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $autoIncrement = 'product_id';

    /**
     * Table alias 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $alias = 'products';

    /**
     * Default order file name
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $defaultOrder = 'order_by, name';    

    /**
     * Product image 
     * 
     * @var    XLite_Model_Image
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $image = null;

    /**
     * Thumbnail 
     * 
     * @var    XLite_Model_Image
     * @access protected
     * @since  3.0.0
     */
    protected $thumbnail = null;

    /**
     * Check - product has thumbnail or not
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function hasThumbnail()
    {
        return '' != $this->get('thumbnail_type');
    }

    /**
     * Return the Thumbnail image instance for this product 
     * 
     * @return XLite_Model_Image
     * @access public
     * @since  3.0.0
     */
    public function getThumbnail()
    {
        if (!isset($this->thumbnail)) {
            $this->thumbnail = new XLite_Model_Image('product_thumbnail', $this->get('product_id'));
        }

        return $this->thumbnail;
    }

    /**
     * Get product image 
     * 
     * @return XLite_Model_Image
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getImage()
    {
        if (is_null($this->image)) {
            $this->image = new XLite_Model_Image('product_image', $this->get('product_id'));
        }

        return $this->image;
    }

    /**
    * Removes all products that have no corresponding link to category(ies).
    */
    function collectGarbage() // {{{
    {
        $products_table = $this->db->getTableByAlias("products");
        $product_links_table = $this->db->getTableByAlias("product_links");
        $sql = "SELECT p.product_id " . 
               "FROM $products_table p " . 
               "LEFT OUTER JOIN $product_links_table l " . 
               "ON p.product_id=l.product_id " . 
               "WHERE l.product_id IS NULL";
        $result = $this->db->getAll($sql);
        foreach ($result as $info) {
            $product = new XLite_Model_Product($info["product_id"]);
            $product->_collectGarbage();
        }

        $ef = new XLite_Model_ExtraField();
        $ef->collectGarbage();
    } // }}}

    function _collectGarbage() // {{{
    {
        $this->delete();
    } // }}}

    /**
     * Delete product
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function delete()
    {
        $table = $this->db->getTableByAlias('product_links');

        $sql=  'DELETE FROM ' . $table . ' WHERE product_id = \'' . $this->get('product_id') . '\'';
        $this->db->query($sql);

        if ($this->hasThumbnail()) {
            $tn = $this->getThumbnail();
            $tn->delete();
        }

        if ($this->hasImage()) {
            $image = $this->getImage();
            $image->delete();
        }

        parent::delete();

        // delete extra fields 
        $fv = new XLite_Model_FieldValue();
        $table = $this->db->getTableByAlias($fv->alias);
        $sql = 'DELETE FROM ' . $table . ' WHERE product_id = \''. $this->get('product_id') . '\'';
        $this->db->query($sql);
    }

    /**
     * Delete all products
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function deleteAll()
    {
        $sql = 'SELECT product_id FROM ' . $this->getTable();
        foreach ($this->db->getAll($sql) as $p) {
            $product = new XLite_Model_Product($p['product_id']);
            if ($product->isExists()) {
                $product->delete();
            }
        }
    }
    
    /**
    * Returns the product clone copy.
    */
    function cloneObject() // {{{
    {
        $p = parent::cloneObject();
        $id = $p->get("product_id");
        $image = $this->getImage();
        $image->copyTo($id);
        $image = $this->getThumbnail();
        $image->copyTo($id);
        $ef = new XLite_Model_ExtraField();
        $it = $ef->findAll("product_id='". $this->get("product_id")."'");
        foreach($it as $extra_field) {
            $ef = $extra_field->cloneObject();
            $ef->set("product_id", $id);
            $ef->update();

            $fv = new XLite_Model_FieldValue();
            if ($fv->find("field_id='".$extra_field->get("field_id")."' AND product_id='".$this->get("product_id")."'")) {
                $fv->read();
                $fv_new = $fv;
                $fv_new->set("product_id", $id);
                $fv_new->set("field_id", $ef->get("field_id"));
                $fv_new->create();
            }
        } 
        return $p;
    } // }}}

    function _beforeAdvancedSearch($substring, $sku = null, $category_id = null, $subcategory_search = false, $fulltext = false, $onlyindexes = false)
    {
        $this->xlite->set("GlobalQuickCategoriesNumber", false);
    }
    
    /**
     * Get categories list
     * 
     * @param string  $where    Filter string
     * @param string  $orderby  Sort string
     * @param boolean $useCache Caching flag
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategories($where = null, $orderby = null, $useCache = true)
    {
        if (empty($orderby)) {
            $orderby = $this->defaultOrder;
        }    

        global $categories;

        $id = $this->get('product_id');

        // reset cached result for admin zone
        if (XLite::isAdminZone() || !$useCache) {
            if (isset($categories[$id][$where][$orderby])) {
                unset($categories[$id][$where][$orderby]);
            }
        }

        $result = array();

        if (!isset($categories[$id][$where][$orderby])) {

            if (!empty($categories[$id]) && !is_array($categories[$id])) {
                $categories[$id] = array();
            }

            if (!empty($categories[$id][$where]) && !is_array($categories[$id][$where])) {
                $categories[$id][$where] = array();
            }

            if (!empty($categories[$id][$where][$orderby]) && !is_array($categories[$id][$where][$orderby])) {
                $categories[$id][$where][$orderby] = $useCache ? array() : null;
            } 

            if ($this->isPersistent) {

                if (!isset($this->_CategoriesFromProducts)) {
                    $this->_CategoriesFromProducts = new XLite_Model_CategoriesFromProducts();
                }
                $this->_CategoriesFromProducts->prodId = $this->get("product_id");
                $result = $this->_CategoriesFromProducts->findAll($where, $orderby);
                if (!XLite::isAdminZone() || $useCache) {
                    $categories[$id][$where][$orderby] = $result;
                }

            } else {

                if (!XLite::isAdminZone() || $useCache) {
                    $categories[$id][$where][$orderby] = $result;
                }
            }

        } else {

            $result = $categories[$id][$where][$orderby];

        }

        return $result;
    }

    function isQuickCategoriesNumber()
    {
        $statusGlobal = $this->xlite->get("GlobalQuickCategoriesNumber");

        return isset($statusGlobal) ? $statusGlobal : (bool)$this->config->General->direct_product_url;
    }

    function getCategoriesNumber($where = null, $orderby = null, $useCache = false)
    {
        if (!$this->isQuickCategoriesNumber()) {
            return count($this->getCategories($where, $orderby, $useCache));
        }

        if (empty($orderby)) {
            $orderby = $this->defaultOrder;
        }    

        $id = $this->get("product_id");    
        // reset cached result for admin zone
        if (XLite::isAdminZone() || !$useCache) {
            if (isset($categoriesNumber[$id][$where][$orderby])) {
                unset($categoriesNumber[$id][$where][$orderby]);
            }
        }

        if (!isset($categoriesNumber[$id][$where][$orderby])) {
            if (!is_array($categoriesNumber[$id])) {
                $categoriesNumber[$id] = array();
            }

            if (!is_array($categoriesNumber[$id][$where])) {
                $categoriesNumber[$id][$where] = array();
            }

            if (!is_array($categoriesNumber[$id][$where][$orderby])) {
                $categoriesNumber[$id][$where][$orderby] = array();
            } 

            if ($this->isPersistent) {
                $p = new XLite_Model_CategoriesFromProducts();
                $p->prodId = $this->get("product_id");
                $categoriesNumber[$id][$where][$orderby] = $p->count($where);

            } else {
                $categoriesNumber[$id][$where][$orderby] = 0;
            }
        }

        $result = $categoriesNumber[$id][$where][$orderby];

        return $result;
    }

    function getCategory($where = null, $orderby = null, $useCache = true) // {{{
    {
        if (empty($orderby)) {
            $orderby = $this->defaultOrder;
        }    

        global $categories;
        $id = $this->get("product_id");
        // reset cached result for admin zone
        if ($this->xlite->is("adminZone") || !$useCache) {
            if (isset($categories[$id][$where][$orderby])) {
                unset($categories[$id][$where][$orderby]);
            }
        }
        if (!isset($categories[$id][$where][$orderby])) {
            $this->getCategories($where, $orderby, $useCache);
        }
        return $categories[$id][$where][$orderby][0];
    } // }}}

    /**
    * Checks that the product belongs to the given category directly (no subcategories).
    */
    function inCategory($c) // {{{
    {
        if ($this->isPersistent && is_object($c)) {
            $p = new XLite_Model_CategoriesFromProducts();
            $p->prodId = $this->get("product_id");
            if ($p->findAll("links.category_id='" . $c->get("category_id") . "'")) {
                return true;
            }
        }
        return false;
    } // }}}

    /**
    * Includes the product into the specified category.
    */
    function addCategory($category) // {{{
    {
        $link_table = $this->db->getTableByAlias("product_links");
        if (!$this->db->getOne("SELECT COUNT(*) FROM $link_table WHERE product_id=".$this->get("product_id")." AND category_id=".$category->get("category_id"))) {
            $this->db->query("INSERT INTO $link_table (product_id,category_id) VALUES ('".$this->get("product_id")."', '".$category->get("category_id")."')");
        }
    } // }}}

    /**
    * Sets the product category.
    */
    function setCategory($category) // {{{
    {
        $categories = $this->get("categories");
        for ($i = 0; $i < count($categories); $i++) {
            $this->deleteCategory($categories[$i]);
        }
        $this->addCategory($category);
    } // }}}

    /**
    * Removes the category <-> product link for the specified category.
    */
    function deleteCategory($category) // {{{
    {
        $link_table = $this->db->getTableByAlias("product_links");
        $this->db->query("DELETE FROM $link_table WHERE product_id='".$this->get("product_id")."' AND category_id='".$category->get("category_id")."'");
    } // }}}

    function hasImage() // {{{
    {
        return $this->get("image_type")!="";
    } // }}}

    function getThumbnailURL() // {{{
    {
        return $this->getComplex('thumbnail.url');
    } // }}}

    function getImageURL() // {{{
    {
        return $this->getComplex('image.url');
    } // }}}

    function getTaxedPrice() // {{{
    {
        if (!$this->config->Taxes->prices_include_tax) {
            return parent::get("price");
        }

        if (!isset($this->_taxRates)) {
            $this->_taxRates = new XLite_Model_TaxRates();
        }
        if ($this->auth->is("logged")) {
            $cart = XLite_Model_Cart::getInstance();
            if (!$cart->isEmpty()) {
                $profile = $cart->get("profile");
            } else {
                $profile = $this->auth->get("profile");
            }
        } else {
            $profile = new XLite_Model_Profile();
            $profile->set("shipping_country", $this->config->getComplex('General.default_country'));
            $profile->set("billing_country", $this->config->getComplex('General.default_country'));
        }
        // setup customer's info
        $order = new XLite_Model_Order();
        $order->set("profile", $profile);
        $this->_taxRates->set("order", $order);
        $this->_taxRates->_conditionValues["product class"] = $this->get("tax_class");
        // categories
        $categories = array();
        foreach ($this->get("categories") as $category) {
            $categories[] = $category->get("category_id");
        }
        $this->_taxRates->_conditionValues["category"] = join(',', $categories);
        $this->_taxRates->_conditionValues["cost"] = $price = $this->get("price");
        $this->_taxRates->calculateTaxes();
        $this->_taxes = $this->_taxRates->getAllTaxes();
        return $price + (isset($this->_taxes["Tax"]) ? $this->_taxes["Tax"] : 0);
    } // }}}

    function getListPrice() // {{{
    {
        return $this->getTaxedPrice();
    } // }}}

    function getPriceMessage() // {{{
    {
        if ($this->config->Taxes->prices_include_tax) {
            if (!isset($this->_taxes)) {
                $this->get("listPrice");
            }
            if (isset($this->_taxes["Tax"]) && $this->_taxes["Tax"]!=0) {
                return $this->config->getComplex('Taxes.include_tax_message');
            }
        }
        return "";
    } // }}}

    function isAvailable() // {{{
    {
        return $this->is("exists") && $this->filter();
    } // }}}

    function populateExtraFields()
    {
        $product_categories = $this->getCategories();
        $ef = new XLite_Model_ExtraField();
        $extraFields = $ef->findAll("product_id=0");
        if (is_array($extraFields))
        {
            foreach ($extraFields as $idx => $extraField) 
            {
                $extraFields_categories = $extraField->getCategories();
                if (count($extraFields_categories) > 0)
                {
                    $found = false;
                    foreach($product_categories as $cat)
                    {
                        if (in_array($cat->get("category_id"), $extraFields_categories))
                        {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found)
                    {
                        unset($extraFields[$idx]);
                    }
                }
                else
                {
                    $found = true;
                }
                if ($found)
                {
                    $ef_child = new XLite_Model_ExtraField();
                    if (!$ef_child->find("product_id='".$this->get("product_id")."' AND parent_field_id='".$extraField->get("field_id")."'"))
                    {
                        $obj_fields = array_keys($extraField->properties);
                        foreach($obj_fields as $obj_field)
                        {
                            $obj_field_updated = false;
                            $obj_field_value = $extraField->get($obj_field);
                            switch($obj_field)
                            {
                                case "field_id":
                                case "product_id":
                                case "parent_field_id":
                                break;
                                default:
                                    $obj_field_updated = true;
                                break;
                            }
                            if ($obj_field_updated)
                            {
                                $ef_child->setComplex($obj_field, $obj_field_value);
                            }
                        }
                        $ef_child->set("product_id", $this->get("product_id"));
                        $ef_child->set("parent_field_id", $extraField->get("field_id"));
                        $ef_child->create();
                    }
                }
            }
        }
    }

    function getExtraFields($enabledOnly=null) // {{{
    {
        if (is_null($enabledOnly)) {
            $enabledOnly = !$this->xlite->is("adminZone");
        }
        $extraFields = array();
        $ef = new XLite_Model_ExtraField();
        if ($enabledOnly) {
            $filter = " AND enabled=1";
        } else {
            $filter = '';
        }
        $extraFields = $ef->findAll("product_id=".$this->get("product_id") ." OR product_id=0");
        // removing parents
        $extraFieldsRoot = array();
        foreach ($extraFields as $idx => $extraField) {
            if ($extraField->get("parent_field_id") == 0) {
                $extraFieldsRoot[$extraField->get("field_id")] = $idx;
            }
        }
        foreach ($extraFields as $idx => $extraField) {
            if ($extraField->get("parent_field_id") != 0 && isset($extraFieldsRoot[$extraField->get("parent_field_id")])) {
                unset($extraFields[$extraFieldsRoot[$extraField->get("parent_field_id")]]);
            }
        }
        $extraFieldsWrongGlobal = array();
        foreach ($extraFields as $idx => $extraField) {
            if ($extraField->get("product_id") == 0) {
                $categories = $this->getCategories();
                if (is_array($categories)) {
                    foreach($categories as $cat) {
                        if (!$extraField->isCategorySelected($cat->get("category_id"))) {
                            $extraFieldsWrongGlobal[$extraField->get("field_id")] = $idx;
                        }
                    }
                }
            }
        }
        foreach ($extraFields as $idx => $extraField) {
            if ($extraField->get("product_id") == 0 && isset($extraFieldsWrongGlobal[$extraField->get("field_id")])) {
                unset($extraFields[$extraFieldsWrongGlobal[$extraField->get("field_id")]]);
            }
        }
        // fill with stored / default values
        foreach ($extraFields as $idx => $extraField) {
            $fv = new XLite_Model_FieldValue();
            if ($fv->find("field_id=".$extraField->get("field_id")." AND product_id=".$this->get("product_id"))) {
                $extraFields[$idx]->set("value", $fv->get("value"));
            } else {
                $extraFields[$idx]->set("value", $extraField->get("default_value"));
            }
        }
        return $extraFields;
    } // }}}
        
    function toXML() // {{{
    {
        $id = "product_" . $this->get("product_id");
        $xml = parent::toXML();
        return "<product id=\"$id\">\n$xml</product>\n";
    } // }}}
    
    function fieldsToXML() // {{{
    {
        $xml = "";

        // dump product categories
        $categories = $this->get("categories");
        $xml .="<categories>\n";
        foreach ($categories as $category) {
            $xml .= "<category id=\"category_".$category->get("category_id")."\"/>\n";
        }
        $xml .= "</categories>\n";
 
        if ($this->hasThumbnail()) {
            // include thumbnail in XML dump
            $thumbnail = $this->getThumbnail();
            if ($thumbnail->get("source") == "D") {
                $xml .= "<thumbnail><![CDATA[".base64_encode($thumbnail->get("data"))."]]></thumbnail>\n";
            }
        }
        if ($this->hasImage()) {
            // include image in XML dump
            $image = $this->getImage();
            if ($image->get("source") == "D") {
                $xml .= "<image><![CDATA[".base64_encode($image->get("data"))."]]></image>\n";
                
            }
        }
        return parent::fieldsToXML() . $xml;
    } // }}}

    // PRODUCT EXPORT functions {{{

    function getImportFields($layout = null) // {{{
    {
        if (isset($layout)) return parent::getImportFields($layout);

        
        $layout = array();
        if (isset($this->config->ImportExport->product_layout)) {
            $layout = explode(',', $this->config->ImportExport->product_layout);
        }    
        // build import fields list
        $fields = array();
        $fields["NULL"] = false;
        $fields["category"] = false;
        $result = array();
        // get object properties ad prepare import fields list
        foreach ($this->fields as $name => $value) {
            if ($name == "product_id" || $name == "category_id" || $name == "thumbnail_type" || $name == "image_type") continue;
            $fields[$name] = false; 
        }
        // add "images" fields
        $fields["thumbnail"] = false;
        $fields["image"] = false;
        // get count(fields) of fields array
        foreach ($fields as $field) {
            $result[] = $fields;
        }
        // fill fields array with the default layout
        foreach ($result as $id => $fields) {
            if (isset($layout[$id])) {
                $selected = $layout[$id];
                $result[$id][$selected] = true;
            }    
        }

        return $result;
    } // }}}

    function _export($layout, $delimiter) // {{{
    {
        $data = array();
        $values = $this->getProperties();
        foreach ($layout as $field) {
            if ($field == "NULL") {
                continue; // skip empty
            } elseif (method_exists($this, "_export$field")) {
                $method = "_export$field";
                $data[] = $this->$method($layout, $delimiter);
            } elseif (isset($values[$field])) {
                $data[] =  $values[$field];
            }
        }
        return $data;
    } // }}}

    function _exportThumbnail() // {{{
    {
        $thumbnail = "";
        if ($this->hasThumbnail()) {
            $tn = $this->getThumbnail(); 
            // include only thumbnail name from file system
            if ($tn->get("source") == "F") {
                $thumbnail = $tn->get("data");
            }
        }
        return $thumbnail;
    } // }}}
    
    function _exportImage() // {{{
    {
        $image = "";
        if ($this->hasImage()) {
            $img = $this->getImage(); 
            // include only thumbnail name from file system
            if ($img->get("source") == "F") {
                $image = $img->get("data");
            }
        }
        return $image;
    } // }}}

    function _exportCategory($layout=null, $delimiter=null) // {{{
    {
        if (!isset($this->_CategoriesFromProducts)) {
            $this->_CategoriesFromProducts = new XLite_Model_CategoriesFromProducts();
        }
        return $this->_CategoriesFromProducts->createCategoryField($this->get("categories"));
    } // }}}

    // END PRODUCT EXPORT functions }}}

    // PRODUCT IMPORT functions {{{

    function import(array $options) // {{{
    {
        if (isset($options["delete_products"]) && $options["delete_products"] === true) {
            $this->deleteAll();
        }
        parent::import($options);
    } // }}}

    function findImportedProduct($_sku, $categoryString, $_productName, $createCategories, $field="") // {{{
    {
        $sku = $_sku;
        $productName = $_productName;
        $fsku = "sku";
        $fname = "name";

        if ($field == "sku" && !$_sku)
            $field = "name";

        switch ($field) {
            case "sku":
                $productName = $_sku;
                $fname = "sku";
            break;
            case "name":
                $sku = $_productName;
                $fsku = "name";
            break;
            default:
                $field = "";
        }

        $product = new XLite_Model_Product();
        // search for product by SKU 
        if (!empty($sku) && $product->find($fsku."='".addslashes($sku)."'")) {
            return $product;
        }
        // or by category/NAME combination
        elseif (!empty($categoryString) && !empty($productName) && $product->find($fname."='".addslashes($productName)."'")) {
            $cat = new XLite_Model_Category();
            $slashedName = addslashes($productName);
            $categoryIds = array();
            foreach ($cat->parseCategoryField($categoryString, true) as $path) {
                if ($createCategories) {
                    $category = $cat->createRecursive($path);
                    $categoryIds[] = $category->get("category_id");
                } else {
                    $category = $cat->findCategory($path);
                    if (!is_null($category)) {
                        $categoryIds[] = $category->get("category_id");
                    }
                }
            }
            if (count($categoryIds)) {
                $link_table = $this->db->getTableByAlias("categories");
                $where = "$link_table.category_id in (".implode(',',$categoryIds).")";
                $p = new XLite_Model_Product();
                foreach($p->findAll($fname."='$slashedName'") as $product) {
                    if (count($product->getCategories($where))) {
                        return $product;
                    }
                }
            }
        }
        // or by NAME
        elseif (!empty($productName) && $product->find("name='".addslashes($productName)."'") && !$field) {
            return $product;
        }
        return null;
    } // }}}

    /**
     * Find product by clean URL
     * 
     * @param string $url Clean URL
     *  
     * @return XLite_Model_Product
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByCleanUrl($url)
    {
        return $this->find('clean_url = \'' . $url . '\'');
    }

    function _import(array $options) // {{{
    {
        $properties       = $options["properties"];
        $default_category = $options["default_category"];
        $image = new XLite_Model_Image();
        $images_directory = ($options["images_directory"] != "") ? $options["images_directory"] : XLite_Model_Image::IMAGES_DIR;
        $save_images      = $options["save_images"];
        $uniq_identifier  = $options["unique_identifier"];

        $this->_convertProperties($properties);
        $existent = false;
        $product = $this->findImportedProduct($properties["sku"], $properties["category"], $properties["name"], true, $uniq_identifier);
        if (is_null($product)) {
            $product = new XLite_Model_Product();
        }
        static $line_no;
        if (!isset($line_no)) $line_no = 1; else $line_no++;
        echo "<b>Importing CSV file line# $line_no: </b>";

        // Update product properties / create product
        $product->set("properties", $properties);
        if ($product->isPersistent) {
            echo "Update product: ";
            $product->update();
        } else {
            echo "Create product: ";
            $product->create();
        }
        echo  $product->get("name") . "<br>\n";
 
        // Update product thumbnail and image
        if (!empty($images_directory)) {
            // update images base directory
            $cfg = new XLite_Model_Config();
            if ($cfg->find("name='images_directory'")) {
                $cfg->set("value", $images_directory);
                $cfg->update();
            } else {
                $cfg->set("name", "images_directory");
                $cfg->set("category", "Images");
                $cfg->set("value", $images_directory);
                $cfg->create();
            }
            // re-read config data
            $this->xlite->config = $cfg->readConfig();
            $this->config = $this->xlite->config;
        }
        if (!empty($properties["thumbnail"])) {
            $this->_importImage($product, "thumbnail", $properties["thumbnail"], $save_images);
        }
        if (!empty($properties["image"])) {
            $this->_importImage($product, "image", $properties["image"], $save_images);
        }

        // Update create product categories
        $this->_importCategory($product, $properties, $default_category);
        $product->populateExtraFields();
    } // }}}

    function _convertProperties(&$p) // {{{
    {
        // X-CART Gold/Pro compatibility check for product import
        if (!empty($p["enabled"]) && ($p["enabled"] == "Y" || $p["enabled"] == "N")) {
            $p["enabled"] = $p["enabled"] == "Y" ? 1 : 0;
        }
    } // }}}

    function _importImage($product, $type, $name, $save_images) // {{{
    {
        $i = new XLite_Model_Image();
        $name = trim($name);
        $image_path = $i->getFilePath($name);
        echo ">> Import product $type, name $image_path<br>\n";
        $method = "get$type";
        $image = $product->$method();
        if ($save_images) {
            // import image to database
            $image->import($image_path);
        } else {
            // update image info
            $image->set("data", $name);
            $image->set("source", "F");
            $image->set("type", $image->getImageType($image_path));
            $image->update();
        }
    } // }}}
    
    function _importCategory($product, $properties, $default_category) // {{{
    {
        $category_id = null;
        $category = new XLite_Model_Category();
        if (!empty($properties["category"])) {
            $newCategories = $category->parseCategoryField($properties["category"], true);
            // convert paths to categories
            for ($i=0; $i<count($newCategories); $i++) {
                $newCategories[$i] = $category->createRecursive($newCategories[$i]);
            }
        } elseif (!is_null($default_category) && $category->find("category_id='$default_category'")) {
            $newCategories = array( new XLite_Model_Category($default_category));
        } else {
            echo "Category unspecified or invalid data for product ".$product->get("name") . "<br>Properties dump:<pre>";
            print_r($properties);
            die("</pre>");
        }
        // get product categories
        $categories = $product->get("categories");
        foreach ($categories as $cat) {
            $product->deleteCategory($cat);
        }
        foreach ($newCategories as $c) {
            $product->addCategory($c);
            echo ">> Product category set to " . $c->get("name") . "<br>\n";
        }
    } // }}}

    // END PRODUCT IMPORT functions }}}

    function create()
    {
        $flag = $this->xlite->get("ProductGarbageCleaned");
        if (!$flag) {
            $this->xlite->set("ProductGarbageCleaned", true);
            $this->collectGarbage();
        }

        $result = parent::create();

        return $result;
    }



    /**
     * Restrictions for product 
     * 
     * @return bool
     * @access public
     * @since  3.0
     */
    public function filter()
    {
        // NOTE - due to speedup we do not check if product is assigned for at least one category 

        return XLite::isAdminZone() ? parent::filter() : $this->get('enabled');
    }

    /**
    * Searches and return products match the specified query.
    *
    * @param $subcategory_search boolean search subcategories of $category_id
    */
    /**
     * Searches and return products match the specified query 
     * 
     * @param string substring          "substring" param
     * @param string sku                "sku" param
     * @param int    category_id        "category_id" param
     * @param bool   subcategory_search "subcategory_search" param
     * @param bool   fulltext           "fulltext" param
     * @param bool   onlyindexes        "onlyindexes" param
     *  
     * @return void
     * @access public
     * @since  3.0
     */
    public function advancedSearch(
        $substring,
        $sku = null,
        $category_id = null,
        $subcategory_search = false,
        $fulltext = false,
        $onlyindexes = false
    ) {
        $this->_beforeAdvancedSearch($substring, $sku, $category_id, $subcategory_search, $fulltext, $onlyindexes);

        $query = null;
        $table = $this->db->getTableByAlias($this->alias);

        if (!empty($substring)) {

            $substring = addslashes($substring);
            $query = '(' . $table . '.name LIKE \'%' . $substring . '%\''
                     . ' OR ' . $table . '.brief_description LIKE \'%' . $substring . '%\''
                     . ' OR ' . $table . '.description LIKE \'%' . $substring . '%\''
                     . ' OR ' . $table . '.sku LIKE \'%' . $substring . '%\')';

        } elseif (!empty($sku)) {

            // search by SKU only
            $query = $table . '.sku LIKE \'%' . $sku . '%\'';
        }

        if (!empty($category_id)) {

            $category = new XLite_Model_Category($category_id);
            $result = $category->getProducts($query, null, false);
            $result = $this->_assocArray($result, "product_id");
            $categories = $category->getSubcategories();
            if (!empty($subcategory_search)) {
                for ($i=0; $i<count($categories); $i++) {
                    $res1 = $this->advancedSearch($substring, $sku, $categories[$i]->get("category_id"), true, true, $onlyindexes);
                    $result = array_merge($result, $this->_assocArray($res1, "product_id"));
                }
            }
            $result = array_values($result);

        } else {

            $p = new XLite_Model_Product();
            $result = $p->findAll($query);
        }

        return $result;
    }

    /**
     * Get products list sort criterions list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    static public function getSortCriterions()
    {
        return array(
            'price' => 'Price',
            'name'  => 'Product name',
            'sku'   => 'SKU',
        );
    }

    /**
     * Get default sort criterion 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    static public function getDefaultSortCriterion()
    {
        return 'name';
    }

    /**
     * Get default sort order
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    static public function getDefaultSortOrder()
    {
        return 'asc';
    }

} 

