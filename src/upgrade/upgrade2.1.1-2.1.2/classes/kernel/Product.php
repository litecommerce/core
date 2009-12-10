<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class represents the shopping cart product.
*
* @package kernel
* @access public
* @version $Id: Product.php,v 1.1 2004/11/22 09:19:48 sheriff Exp $
*/
class Product extends Base
{
    var $fields = array(
            "product_id"        => 0,    // primary key
            "sku"               => "",
            "name"              => "",
            "description"       => "",
            "brief_description" => "",
            "meta_tags"         => "",
            "price"             => 0.00,
            "enabled"           => "1",
            "order_by"          => 0,
            "thumbnail_type"    => "",
            "weight"            => 0,
            "image_type"        => "",
            "tax_class"         => ""
            );

    var $autoIncrement = "product_id";
    var $alias = "products";
    var $defaultOrder = "order_by,name";

    var $image = null;
    var $thumbnail = null;

    /**
    * Return the Image instance for this product.
    *
    * Example: $image =& $product->getImage();
    * $image->show();
    * $image->set("image", file_get_contents($upload_file));
    */
    function &getImage() // {{{
    {
        if (is_null($this->image)) {
            $this->image = func_new("Image", 'product_image', $this->get("product_id"));
        }
        return $this->image;
    } // }}}

    /**
    * Return the Thumbnai image instance for this product.
    */
    function &getThumbnail() // {{{
    {
        if (is_null($this->thumbnail)) {
            $this->thumbnail = func_new("Image","product_thumbnail", $this->get("product_id"));
        }
        return $this->thumbnail;
    } // }}}

    /**
    * Removes all products that have no corresponding link to category(ies).
    */
    function collectGarbage() // {{{
    {
        $products_table = $this->db->getTableByAlias("products");
        $product_links_table = $this->db->getTableByAlias("product_links");
        $sql = "SELECT p.product_id FROM $products_table " . 
               "p LEFT OUTER JOIN $product_links_table l " . 
               "ON p.product_id=l.product_id WHERE l.product_id IS NULL";
        $result = $this->db->getAll($sql);
        foreach ($result as $info) {
            $product =& func_new("Product",$info["product_id"]);
            $product->_collectGarbage();
        }
    } // }}}

    function _collectGarbage() // {{{
    {
        $this->delete();
    } // }}}

    /**
    * Deletes product from the database.
    */
    function delete() // {{{
    {
        $table = $this->db->getTableByAlias("product_links");
        $sql="DELETE FROM $table WHERE product_id=" . $this->get("product_id");
        $this->db->query($sql);
		if ($this->hasThumbnail()) {
			$tn =& $this->getThumbnail();
			$tn->delete();
		}
		if ($this->hasImage()) {
			$image =& $this->getImage();
			$image->delete();
		}
        parent::delete();
        // delete extra fields 
        $fv =& func_new("FieldValue");
        $table = $this->db->getTableByAlias($fv->alias);
        $sql = "DELETE FROM $table WHERE product_id=". $this->get("product_id");
        $this->db->query($sql);
    } // }}}

    /**
    * Deletes ALL products from the database.
    */
    function deleteAll() // {{{
    {
        $sql = "SELECT product_id FROM " . $this->getTable();
        $result = $this->db->getAll($sql);
        foreach ($result as $p) {
            $product = func_new("Product",$p['product_id']);
            if ($product->isExists()) {
                $product->delete();
            }
        }
    } // }}}
    
    /**
    * Returns the product clone copy.
    */
    function clone() // {{{
    {
        $p = parent::clone();
        $id = $p->get("product_id");
        $image = $this->getImage();
        $image->copyTo($id);
        $image = $this->getThumbnail();
        $image->copyTo($id);
        $ef = & func_new("ExtraField");
        $it = $ef->findAll("product_id=0 OR product_id=". $this->get("product_id"));
        foreach($it as $extra_field) {
            $ef = & func_new("ExtraField");
            $ef->set("properties",$extra_field->get("properties"));
            $ef->set("properties.field_id","");
            $ef->set("product_id",$id);
            $ef->create();
        } 
        return $p;
    } // }}}
    
    /**
    * Searches and return products match the specified query.
    *
    * @param $subcategory_search boolean search subcategories of $category_id
    */
    function &advancedSearch($substring, $sku = null, $category_id = null, $subcategory_search = false, $fulltext = false) // {{{
    {
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
            /* full text search (N/A) {{{
            if ($fulltext) {
                $query = "MATCH ($table.name, $table.description, $table.brief_description) AGAINST ('$substring')";
                $this->defaultOrder = ""; // use default ranking order
            } else {
            }
            }}} */
            $query = "($table.name LIKE '%$substring%' OR $table.brief_description LIKE '%$substring%' OR $table.description LIKE '%$substring%' OR $table.sku LIKE '%$substring%')";
        } elseif (!is_null($sku) && !empty($sku)) {
            // search by SKU only
            $query = "$table.sku LIKE '%$sku%'";
        }
        if (!is_null($category_id)) {
            $category =& func_new("Category", $category_id);
            $result =& $category->getProducts($query, null, false);
            $result =& $this->_assocArray($result, "product_id");
            $categories =& $category->getSubcategories();
            if ($subcategory_search) {
                for ($i=0; $i<count($categories); $i++) {
                    $res1 =& $this->advancedSearch($substring, $sku, $categories[$i]->get("category_id"), true, true);
                    $result = array_merge($result, $this->_assocArray($res1, "product_id"));
                }
            }
            return array_values($result);
        } else {
            $p =& func_new("Product");
            $p->fetchKeysOnly = true;
            $result =& $p->findAll($query);
        }
        return $result;
    } // }}}
    
    function getCategories($where = null, $orderby = null, $useCache = true) // {{{
    {
        if (empty($orderby)) {
            $orderby = $this->defaultOrder;
        }    
        global $categories;
        $id = $this->get("product_id");
        // reset cached result for admin zone
        if ($this->xlite->is("adminZone") || !$useCache) {
            unset($categories[$id][$where][$orderby]);
        }
        if (!isset($categories[$id][$where][$orderby])) {
            if ($this->isPersistent) {
                $p =& func_new("_CategoriesFromProducts");
                $p->prodId = $this->get("product_id");
                $categories[$id][$where][$orderby] = $p->findAll($where, $orderby);
            } else {
                $categories[$id][$where][$orderby] = array();
            }
        }
        $result = $categories[$id][$where][$orderby];
        return $result;
    } // }}}

	/**
	* Checks that the product belongs to the given category directly (no subcategories).
	*/
	function inCategory($c) // {{{
	{
		if ($this->isPersistent) {
			$p =& func_new("_CategoriesFromProducts");
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

    function hasThumbnail() // {{{
    {
        return $this->get("thumbnail_type")!="";
    } // }}}

    function hasImage() // {{{
    {
        return $this->get("image_type")!="";
    } // }}}

	function getThumbnailURL() // {{{
	{
        return $this->get("thumbnail.url");
	} // }}}

	function getImageURL() // {{{
	{
        return $this->get("image.url");
	} // }}}

	function getTaxedPrice() // {{{
    {
        if (!$this->config->get("Taxes.prices_include_tax")) {
            return parent::get("price");
        }

        if (!isset($this->_taxRates)) {
            $this->_taxRates = func_new("TaxRates");
        }
        if ($this->auth->is("logged")) {
            $profile =& $this->auth->get("profile");
        } else {
            $profile =& func_new("Profile");
            $profile->set("shipping_country", $this->config->get("General.default_country"));
            $profile->set("billing_country", $this->config->get("General.default_country"));
        }
        // setup customer's info
        $order = func_new("Order");
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
        if ($this->config->get("Taxes.prices_include_tax")) {
            if (!isset($this->_taxes)) {
                $this->get("listPrice");
            }
            if (isset($this->_taxes["Tax"]) && $this->_taxes["Tax"]!=0) {
                return $this->config->get("Taxes.include_tax_message");
            }
        }
        return "";
    } // }}}

    function filter() // {{{
    {
        if (!$this->xlite->is("adminZone")) {
            if (!$this->get("enabled")) {
                return false;
            }
            if (count($this->get("categories")) == 0) {
                return false;
            }
        }
        return parent::filter();
    } // }}}

    function isAvailable() // {{{
    {
        return $this->is("exists") && $this->filter();
    } // }}}

    function &getExtraFields($enabledOnly=null) // {{{
    {
        if (is_null($enabledOnly)) {
            $enabledOnly = !$this->xlite->is("adminZone");
        }
        $extraFields = array();
        $ef =& func_new("ExtraField");
        if ($enabledOnly) {
            $filter = " AND enabled=1";
        } else {
            $filter = '';
        }
        $extraFields = $ef->findAll("product_id=0 OR product_id=".$this->get("product_id"));
        // fill with stored / default values
        foreach ($extraFields as $idx => $extraField) {
            $fv =& func_new("FieldValue");
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
        $categories =& $this->get("categories");
        $xml .="<categories>\n";
        foreach ($categories as $category) {
            $xml .= "<category id=\"category_".$category->get("category_id")."\"/>\n";
        }
        $xml .= "</categories>\n";
 
        if ($this->hasThumbnail()) {
            // include thumbnail in XML dump
            $thumbnail =& $this->getThumbnail();
            if ($thumbnail->get("source") == "D") {
                $xml .= "<thumbnail><![CDATA[".base64_encode($thumbnail->get("data"))."]]></thumbnail>\n";
            }
        }
        if ($this->hasImage()) {
            // include image in XML dump
            $image =& $this->getImage();
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
            $tn =& $this->getThumbnail(); 
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
            $img =& $this->getImage(); 
            // include only thumbnail name from file system
            if ($img->get("source") == "F") {
                $image = $img->get("data");
            }
        }
        return $image;
    } // }}}

    function _exportCategory($layout=null, $delimiter=null) // {{{
    {
        $cat = func_new("Category");
        return $cat->createCategoryField($this->get("categories"));
    } // }}}

    // END PRODUCT EXPORT functions }}}

    // PRODUCT IMPORT functions {{{

    function import(&$options) // {{{
    {
        if (isset($options["delete_products"]) && $options["delete_products"] === true) {
            $this->deleteAll();
        }
        parent::import($options);
    } // }}}

    function &findImportedProduct($sku, $categoryString, $productName, $createCategories) // {{{
    {
        $product = func_new("Product");
        // search for product by SKU 
        if (!empty($sku) && $product->find("sku='".addslashes($sku)."'")) {
            return $product;
        }
        // or by category/NAME combination
        elseif (!empty($categoryString) && !empty($productName) && $product->find("name='".addslashes($productName)."'")) {
            $cat = func_new("Category");
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
                $where = 'xlite_categories.category_id in ('.implode(',', $categoryIds).')';
                $p = func_new("Product");
                foreach($p->findAll("name='$slashedName'") as $product) {
                    if (count($product->getCategories($where))) {
                        return $product;
                    }
                }
            }
        }
        // or by NAME
        elseif (!empty($productName) && $product->find("name='".addslashes($productName)."'")) {
            return $product;
        }
        return null;
    } // }}}

    function _import(&$options) // {{{
    {
        $properties       = $options["properties"];
        $default_category = $options["default_category"];
        $images_directory = $options["images_directory"];
        $save_images      = $options["save_images"];

        $this->_convertProperties($properties);
        $existent = false;
        $product = $this->findImportedProduct($properties["sku"], $properties["category"], $properties["name"], true);
        if (is_null($product)) {
            $product = func_new("Product");
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
            $this->config = func_new("Config");
            if ($this->config->find("name='images_directory'")) {
                $this->config->set("value", $images_directory);
                $this->config->update();
            } else {
                $this->config->set("name", "images_directory");
                $this->config->set("category", "Images");
                $this->config->set("value", $images_directory);
                $this->config->create();
            }
            // re-read config data
            $this->config->readConfig();
        }
        if (!empty($properties["thumbnail"])) {
            $this->_importImage($product, "thumbnail", $properties["thumbnail"], $save_images);
        }
        if (!empty($properties["image"])) {
            $this->_importImage($product, "image", $properties["image"], $save_images);
        }
        // Update create product categories
        $this->_importCategory($product, $properties, $default_category);
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
        

        $images_directory = isset($this->config->Images->images_directory) ?
                                   $this->config->Images->images_directory : "";
        $image_path = empty($images_directory) ? $name : "$images_directory/$name";
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
        $category = func_new("Category");
        if (!empty($properties["category"])) {
            $newCategories = $category->parseCategoryField($properties["category"], true);
            // convert paths to categories
            for ($i=0; $i<count($newCategories); $i++) {
                $newCategories[$i] = $category->createRecursive($newCategories[$i]);
            }
        } elseif (!is_null($default_category) && $category->find("category_id=$default_category")) {
            $newCategories = array(func_new("Category", $default_category));
        } else {
            echo "Category unspecified or invalid data for product ".$product->get("name") . "<br>Properties dump:<pre>";;
            print_r($properties);
            die("</pre>");
        }
        // get product categories
        $categories =& $product->get("categories");
        foreach ($categories as $cat) {
            $product->deleteCategory($cat);
        }
        foreach ($newCategories as $c) {
            $product->addCategory($c);
            echo ">> Product category set to " . $c->get("name") . "<br>\n";
        }
    } // }}}

    // END PRODUCT IMPORT functions }}}

} 

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
