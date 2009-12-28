<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class Category provides access to shopping cart category.
*
* @package Kernel
* @version $Id$
*/
class XLite_Model_Category extends XLite_Model_Abstract
{
    /**
    * @var array $fields The category properties
    * @access private
    */
    var $fields = array(
                    "category_id"           => 0,
                    "image_width"           => 0,
                    "image_height"          => 0,
                    "name"                  => "",
                    "description"           => "",
                    "meta_tags"             => "",
					"meta_title"			=> "",
					"meta_desc"				=> "",
                    "enabled"               => 1,
                    "views_stats"           => 0,
                    "order_by"              => 0,
                    "membership"            => "%",
                    "threshold_bestsellers" => 1,
                    "parent"                => 0,
                    "image_type"            => ""
                    );
                    
    /**
    * @var string $autoIncrement The category database table primary key
    * @access private
    */
    var $autoIncrement = "category_id";

    /**
    * @var string $defaultOrder The category database records default order
    * @access private
    */
    var $defaultOrder = "order_by,name";

    /**
    * @var string $alias The category database table alias
    * @access public
    */
    var $alias = "categories";
    var $parent = null;
    var $image = null;
	var $_string_path = null;
    
    function cloneObject()
    {       
        $c = parent::cloneObject();
        $id = $c->get("category_id");
        $image = $this->get("image");
        $image->copyTo($id);
        return $c;
    }       
    
    /**
    * Checks whether image for this category is set
    * 
    * @access public
    * @return boolean
    */
    function hasImage() // {{{
    {
        if ($this->get("category_id")==0)
            return false;
        $image = $this->get("image");
        $data = $image->get("data");
        return !empty($data);
    } // }}}
    
    /**
    * Returns the Image instance for this category image.
    * 
    * @access public
    * @return Image the Image instance.
    */
    function getImage() // {{{
    {   
        if (is_null($this->image)) {
            $this->image = new XLite_Model_Image("category", $this->get("category_id"));
        }
        return $this->image;
    } // }}}

	function getImageURL() // {{{
	{
        return $this->get("image.url");
	} // }}}

    /**
    * Returns the path to the current category.
    *
    * @access public
    * @return array The array with category path.
    */
    function getPath() // {{{
    {
        $path = array();
        $parent = $this;
        do {
            $path[] = $parent;
            $parent = $parent->get("parentCategory");
        } while (!is_null($parent));
        return array_reverse($path);
    } // }}}

    /**
    * Gets full path to the category in form 
    * "parent category/.../category name"
    *
    * @access public
    * @return string The category path.
    */
    function getStringPath() // {{{
    {
        if (is_null($this->_string_path)) {
            $path = $this->getPath();
            $location = "";
            for ($i=0; $i<count($path); $i++) {
                if ($i) {
                    $location .= "/";
                }
                $location .= $path[$i]->get("name");
            }
            $this->_string_path = $location;
        }
        return (string) $this->_string_path;
    } // }}}
    
    /**
    * Returns the parent category instance for the current category
    * 
    * @access public
    * @return Category The parent category instance.
    */
    function getParentCategory() // {{{
    {
        if (is_null($this->parent) && $this->get("category_id") && $this->get("parent")) {
            $this->parent = new XLite_Model_Category($this->get("parent"));
        }
        return $this->parent;
    } // }}}
    
    function setParentCategory($v) // {{{
    {
        $this->parent = $v;
        $this->set("parent", $v->get("category_id"));
    } // }}}

    function isEmpty() // {{{
    {
        return !$this->get("products") && !$this->get("subcategories");
    } // }}}

    /**
    * Returns the product list for the current category.
    *
    * @access public
    * @return array The array of Product instances.
    */
    function getProducts($where = null, $orderby = null, $useCache = true) // {{{
    {
        if (empty($orderby)) {
            $orderby = $this->defaultOrder;
        }    
        global $products;
        $id = $this->get("category_id");
        if ($this->xlite->is("adminZone") || !$useCache) {
            if (isset($products[$id][$where][$orderby])) {
            	unset($products[$id][$where][$orderby]);
            }
        }
        if (!isset($products[$id][$where][$orderby])) {
            if ($this->isPersistent) {
                $p = new XLite_Model_ProductFromCategory($id);
                $products[$id][$where][$orderby] = $p->findAll($where, $orderby);
            } else {
                $products[$id][$where][$orderby] = array();
            }    
        }
        return $products[$id][$where][$orderby];
    } // }}}

    function getProductsNumber()
    {
        $id = $this->get("category_id");
        if ($this->isPersistent) {
            $p = new XLite_Model_ProductFromCategory($id);
            return $p->getProductsNumber(false);
        }    
        return 0;
    }

    /**
    * Returns the subcategories list for the current category.
    *
    * @access public
    * @return array The array of Category instances.
    */
    function getSubcategories($where = 1) // {{{
    {
        global $subcategories;
        $id = $this->get("category_id");
        $condition = "parent='".$this->get("category_id")."' AND $where";
        if ($this->xlite->is("adminZone")) {
            if (isset($subcategories[$id][$condition])) {
            	unset($subcategories[$id][$condition]);
            }
        }
        if (!isset($subcategories[$id][$condition])) {
            $subcategories[$id][$condition] = $this->findAll($condition, "order_by, name");
        }
        return $subcategories[$id][$condition];
    } // }}}

    /**
    * Attempts to delete the current category from the database.
    *
    * @access public
    */
    function delete() // {{{
    {
        // remove all products from the category
        $products = $this->get("products");
        for ($i=0; $i<count($products); $i++) {
            $products[$i]->deleteCategory($this);
        }
        $subcategories = $this->get("subcategories");
        for ($i = 0; $i < count($subcategories); $i++) {
            $category = $subcategories[$i];
            $category->delete();
        }
        $product = new XLite_Model_Product();
        $product->collectGarbage();
		$image = $this->get("image");
		$image->delete();
        parent::delete();
    } // }}}

    function getTopCategory() // {{{
    {
        return new XLite_Model_Category(0);
    } // }}}

    /* 
        Parse $data due to the following grammar:
    
            NAME_CHAR ::= ( [^/] | "//" | "||")
            CATEGORY_NAME ::= NAME_CHAR CATEGORY_NAME | NAME_CHAR
            CATEGORY_PATH ::= CATEGORY_NAME "/" CATEGORY_PATH | CATEGORY_NAME
        
        If $allowMiltyCategories == true, then

            DATA ::= CATEGORY_PATH "|" DATA | CATEGORY_PATH
        
        If $allowMiltyCategories == false, then

            DATA ::= CATEGORY_PATH

    */
    function parseCategoryField($data, $allowMiltyCategories) // {{{
    {
        $i = 0;
        $state = "S";
        $path = array();
        $list = array();
        $lastSlash = -1;
        $lastDiv = -1;
        $word = "";
        for ($i=0; $i<=strlen($data); $i++) {
            if ($i == strlen($data)) $char = "";
            else $char = $data{$i};
            if ($char == "/") {
                if ($state == "/") {
                    $word .= "/";
                    $state = "S";
                } else {
                    $state = "/";
                }
            } else if ($char == "|") {
                if ($state == "|") {
                    $word .= "|";
                    $state = "S";
                } else {
                    $state = "|";
                }
            } else {
                if ($state == "/") {
                    $path[] = $word;
                    $word = $char;
                    $state = "S";
                } else if ($state == "|" || $char == "") {
                    $path[] = $word;
        			if ($allowMiltyCategories) {
                    	$list[] = $path;
                    	$path = array();
                    }
                    $word = $char;
                    $state = "S";
                } else {
                    $word .= $char;
                }
            }
        }
        if ($allowMiltyCategories) 
            return $list;
        else
            return $path;
    } // }}}

    /* if $categorySet is an array, creates the string in c1|c2|...|cn format
    due to the specification given above. If $categorySet is a single category,
    creates an export string for the single category in format component1/...
    */
    function createCategoryField($categorySet) // {{{
    {
        if (is_array($categorySet)) {
            $paths = array();
            foreach ($categorySet as $category) {
                $paths[] = $this->createCategoryField($category);
            }
            return implode("|", $paths);
        }
        $path = $categorySet->get("path");
        for($i = 0; $i<count($path); $i++) {
            $path[$i] = str_replace("/", "//", str_replace("|", "||", $path[$i]->get("name")));
        }
        return implode("/", $path);
    } // }}}
    
    function createRecursive($name) // {{{
    {
        if (!is_array($name)) {
            $path = $this->parseCategoryField($name, false);
        } else {
            $path = $name;
        }
        $topID = $this->get("topCategory.category_id");
        $category_id = $topID;
        foreach ($path as $n) {
            $category = func_new("Category");
            if ($category->find("name='".addslashes($n)."' AND parent=$category_id")) {
                $category_id = $category->get("category_id");
                continue;
            } 
            $category->set("name", $n);
            $category->set("parent", $category_id);
            $category->create();
            $category_id = $category->get("category_id");
        }
        return func_new("Category",$category_id);
    } // }}}

    function findCategory($path) // {{{
    {
        if (!is_array($path)) {
            $path = $this->parseCategoryField($path, false);
        }
        $topID = $this->get("topCategory.category_id");
        $category_id = $topID;
        foreach ($path as $n) {
            $category = func_new("Category");
            if ($category->find("name='".addslashes($n)."' AND parent=$category_id")) {
                $category_id = $category->get("category_id");
                continue;
            } 
            return null;
        }
        return func_new("Category",$category_id);
    } // }}}

    function filterRule()
    {
    	$result = true;

        if ($this->auth->is("logged")) {
            $membership = $this->auth->get("profile.membership");
        } else {
            $membership = '';
        }
		if (!$this->is("enabled") || trim($this->get("name")) == "" || !$this->_compareMembership($this->get("membership"), $membership)) {
			$result = false;
		}

		return $result;
    }

    function filter() // {{{
    {
        $result = parent::filter(); // default
        if ($result && !$this->xlite->is("adminZone")) {
            if ($this->db->cacheEnabled) {
                global $categoriesFiltered;
                if (!isset($categoriesFiltered) || (isset($categoriesFiltered) && !is_array($categoriesFiltered))) {
                	$categoriesFiltered = array();
                }

                $cid = $this->get("category_id");
                if (isset($categoriesFiltered[$cid])) {
                	return $categoriesFiltered[$cid];
                }
            }

            $result = $this->filterRule();
			if ($result) {
				// check parent categories
				$c = $this->get("parentCategory");
                if (!is_null($c)) {
                    if (!$c->filter()) {
                        $result = false;
                    }
                }
            }

            if ($this->db->cacheEnabled) {
        		$categoriesFiltered[$cid] = $result;
        	}
        }
        return $result;
    } // }}}
    
    function _compareMembership($categoryMembership, $userMembership) // {{{
    {
        return $categoryMembership == 'all' || $categoryMembership == '%' || $categoryMembership == '_%' && $userMembership || $categoryMembership == $userMembership;
    } // }}}

    function toXML() // {{{
    {
        $id = "category_" . $this->get("category_id");
        $xml = parent::toXML();
        return "<category id=\"$id\">\n$xml\n</category>\n";
    } // }}}
    
    function fieldsToXML() // {{{
    {
        $xml = "";
        if ($this->hasImage()) {
            // include image in XML dump
            $image = $this->getImage();
            if ($image->get("source") == "D") {
                $xml .= "<image><![CDATA[".base64_encode($image->get("data"))."]]></image>";
                
            }
        }
        return parent::fieldsToXML() . $xml;
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
