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
class XLite_Model_Category extends XLite_Model_Abstract
{
    /**
     * Returns the subcategories list for the current category 
     * 
     * @param string $where SQL "where" condition_
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getSubcategories($where = null)
    {
        $categoryId =  $this->get('category_id');

        return XLite_Model_CachingFactory::getObjectFromCallback(
            __METHOD__ . $categoryId . $where,
            $this,
            'findAll',
            array('parent = \'' . $categoryId . '\'' . (isset($where) ? ' AND ' . $where : ''))
        );
    }

    /**
     * Returns the Image instance for this category image 
     * 
     * @return XLite_Model_Image
     * @access public
     * @since  3.0.0
     */
    public function getImage()
    {
        $categoryId = $this->get('category_id');

        return XLite_Model_CachingFactory::getObject(
            __METHOD__ . $categoryId,
            'XLite_Model_Image',
            array('category', $categoryId)
        );
	}

	/**
	 * Checks whether image for this category is set 
	 * 
	 * @return bool
	 * @access public
	 * @since  3.0.0
	 */
	public function hasImage()
    {
		return (0 == $this->get('category_id')) ? false : !is_null($this->getImage()->get('data'));
	}

    /**
     * Return image URL
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getImageURL()
    {
        return $this->getImage()->getURL();
    }

	/**
	 * Check if category has neither products nor subcategories 
	 * 
	 * @return bool
	 * @access public
	 * @since  3.0.0
	 */
	public function isEmpty()
    {
        return !$this->getProducts() && !$this->getSubcategories();
    } 




    /**
    * @var array $fields The category properties
    * @access private
    */
    protected $fields = array(
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
    public $autoIncrement = "category_id";

    /**
    * @var string $defaultOrder The category database records default order
    * @access private
    */	
    public $defaultOrder = "order_by,name";

    /**
    * @var string $alias The category database table alias
    * @access public
    */	
    public $alias = "categories";	
    public $parent = null;	
    public $image = null;	
	public $_string_path = null;
    
    function cloneObject()
    {       
        $c = parent::cloneObject();
        $id = $c->get("category_id");
        $image = $this->get("image");
        $image->copyTo($id);
        return $c;
    }       
    
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
            $parent = $parent->getParentCategory();
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
        $parent = $this->get('parent');

        return ($this->get('category_id') && $parent)
            ? XLite_Model_CachingFactory::getObject(__METHOD__ . $parent, 'XLite_Model_Category', array($parent))
            : null;
    } // }}}
    
    function setParentCategory($v) // {{{
    {
        $this->set("parent", $v->get("category_id"));
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
        $topID = $this->getComplex('topCategory.category_id');
        $category_id = $topID;
        foreach ($path as $n) {
            $category = new XLite_Model_Category();
            if ($category->find("name='".addslashes($n)."' AND parent=$category_id")) {
                $category_id = $category->get("category_id");
                continue;
            } 
            $category->set("name", $n);
            $category->set("parent", $category_id);
            $category->create();
            $category_id = $category->get("category_id");
        }
        return new XLite_Model_Category($category_id);
    } // }}}

    function findCategory($path) // {{{
    {
        if (!is_array($path)) {
            $path = $this->parseCategoryField($path, false);
        }
        $topID = $this->getComplex('topCategory.category_id');
        $category_id = $topID;
        foreach ($path as $n) {
            $category = new XLite_Model_Category();
            if ($category->find("name='".addslashes($n)."' AND parent=$category_id")) {
                $category_id = $category->get("category_id");
                continue;
            } 
            return null;
        }
        return new XLite_Model_Category($category_id);
    } // }}}

    function filterRule()
    {
    	$result = true;

        if ($this->auth->is("logged")) {
            $membership = $this->auth->getComplex('profile.membership');
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
                $parent = $this->getParentCategory();
                if (isset($parent)) {
                    $result = $result && XLite_Model_CachingFactory::getObjectFromCallback(
                        __METHOD__ . $parent->get('category_id'), $parent, 'filter'
                    );  
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
