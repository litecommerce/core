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
 * @subpackage Controller
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
class XLite_Controller_Admin_Category extends XLite_Controller_Admin_Abstract
{
    public $page = "category_modify";
    public $pages = array
    (
        "category_modify"        => "Add/Modify category",
    );
    public $pageTemplates = array
    (
        "category_modify"          => "categories/add_modify_body.tpl",
        "extra_fields"          => "categories/category_extra_fields.tpl",
    );

    public $params = array('target', 'category_id', 'mode', 'message', 'page');
    public $order_by = 0;

    function init()
    {
        parent::init();

        if ($this->mode != "add" && $this->mode == "modify") {
            $this->pages['category_modify'] = "Modify category";
            if ($this->config->getComplex('General.enable_categories_extra_fields')) {
                $this->pages['extra_fields'] = "Extra fields";
            }
        } else {
            $this->pages['category_modify'] = "Add new category";
        }
    }

    function fillForm()
    {
        $this->set("properties", $this->getComplex('category.properties'));
    }

    function getCategories()
    {
        $c = new XLite_Model_Category();
        $this->categories = $c->findAll();
        $names = array();
        $names_hash = array();
        for ($i = 0; $i < count($this->categories); $i++) 
        {
            $name = $this->categories[$i]->get('stringPath');
            while (isset($names_hash[$name]))
            {
                $name .= " ";
            }
            $names_hash[$name] = true;
            $names[] = $name;
        }
        array_multisort($names, $this->categories);

        return $this->categories;
    }

    function getExtraFields()
    {
        if (is_null($this->extraFields)) 
        {
            $ef = new XLite_Model_ExtraField();
            $extraFields = $ef->findAll("product_id=0");  // global fields
            foreach($extraFields as $extraField_key => $extraField)
            {
                if (!$extraField->isCategorySelected($this->category_id))
                {
                    unset($extraFields[$extraField_key]);
                }
            }

            $this->extraFields = (count($extraFields) > 0) ? $extraFields : null;
        }
        return $this->extraFields;
    }

    // FIXME - check this method
    function getParentCategory()
    {
        if (is_null($this->parentCategory)) {
            $this->parentCategory = new XLite_Model_Category($this->category_id);
        }
        return $this->parentCategory;
    }
    
    function getCategory()
    {
        if (is_null($this->category)) {
            if ($this->get('mode') == "add") {
                $this->category = new XLite_Model_Category(); // empty category
            } else {
                $categoryID = 0;
                if (isset(XLite_Core_Request::getInstance()->category_id)) {
                    $categoryID = XLite_Core_Request::getInstance()->category_id;
                }
                $this->category = new XLite_Model_Category($categoryID);
            }
        }
        return $this->category;
    }

    function getLocationPath()
    {
        $result = array();
        if ($this->get('mode') == "add" && $this->getComplex('parentCategory.category_id') != 0) {
            foreach ($this->getComplex('parentCategory.path') as $category) {
                $name = $category->get('name');
                while (isset($result[$name])) {
                    $name .= " ";
                }
                $result[$name] = "admin.php?target=categories&category_id=" . $category->get('category_id');
            }
        } else if ($this->getComplex('category.category_id') != 0) {
            foreach ($this->getComplex('category.path') as $category) {
                $name = $category->get('name');
                while (isset($result[$name])) {
                    $name .= " ";
                }
                $result[$name] = "admin.php?target=categories&category_id=" . $category->get('category_id');
            }
        }
        return $result;
    }

    function action_modify()
    {
        $valid = (bool) isset($this->name) && strlen(trim($this->name));

        if (!$valid) {
            $this->set("valid", $valid);
            return;
        }

        // update category
        $category = new XLite_Model_Category();
        $properties = XLite_Core_Request::getInstance()->getData();

        // Sanitize
        if (isset($properties['clean_url'])) {
            $properties['clean_url'] = $this->sanitizeCleanURL($properties['clean_url']);
            if (!$this->checkCleanURLUnique($properties['clean_url'])) {

                // TODO - add top message
                $this->set('valid', false);
                return;
            }
        }


        if (empty($properties['parent'])) $properties['parent'] = 0;
        $category->setProperties($properties);
        $category->update();

        // update category image
        $image = $category->get('image');
        $image->handleRequest();
        
        $this->set("message", "updated");
    }

    function action_add()
    {
        $valid = (bool) isset($this->name) && strlen(trim($this->name));

        if (!$valid) {
            $this->set("valid", $valid);
            return;
        }

        // add category
        $category = new XLite_Model_Category();
        $properties = XLite_Core_Request::getInstance()->getData();

        // Sanitize
        if (isset($properties['clean_url'])) {
            $properties['clean_url'] = $this->sanitizeCleanURL($properties['clean_url']);
            if (!$this->checkCleanURLUnique($properties['clean_url'])) {

                // TODO - add top message
                $this->set('valid', false);
                return;
            }
        }

        $category->set("properties", $properties);
        $category->set("category_id", null);
        if (empty(XLite_Core_Request::getInstance()->parent)) XLite_Core_Request::getInstance()->parent = 0;
        $category->set("parent", XLite_Core_Request::getInstance()->parent);
        $category->create();

        // upload category image
        $image = $category->get('image');
        $image->handleRequest();

        // switch to modify page
        $this->set("category_id", $category->get('category_id'));
        $this->set("mode", "modify");
        $this->set("message", "added");
    }

    function action_delete()
    {
        $category = $this->get('category');
        // return to categories listing
        $this->set("target", "categories");
        $this->set("category_id", $category->get('parent'));
        $category->delete();
    }

    function action_icon()
    {
        $category = $this->get('category');
        // delete category image
        $image = $category->get('image');
        $image->handleRequest();
    }

    function action_add_field()
    {
        $_postData = XLite_Core_Request::getInstance()->getData();
        foreach($_postData as $post_key => $post_value)
        {
            if (strcmp(substr($post_key, 0, 7), "add_ef_") == 0)
            {
                $_postData[substr($post_key, 7)] = $post_value;
                unset($_postData[$post_key]);
            }
        }
        // ADD field
        if (!is_null($this->get('add_field'))) 
        {
            $categories = (array)$this->get('add_categories');
            if (!empty($categories)) 
            {
                $ef = new XLite_Model_ExtraField();
                $ef->set("properties", $_postData);
                $ef->setCategoriesList($categories);
                $ef->create();
            }
            else
            {
                // buld add
                $categories = (array)$this->get('add_categories');
                if (!empty($categories)) {
                    foreach ($categories as $categoryID) {
                        $category = new XLite_Model_Category($categoryID);
                        foreach ((array)$category->get('products') as $product) {
                            $ef = new XLite_Model_ExtraField();
                            $ef->set("properties", $_postData);
                            $ef->set("product_id", $product->get('product_id'));
                            $ef->create();
                        }
                    }
                } else {
                    $ef = new XLite_Model_ExtraField();
                    $ef->set("properties", $_postData);
                    $ef->create();
                }
            }
        }
        // DELETE field
        elseif (!is_null($this->get('delete_field'))) {
            foreach ((array)$this->get('add_categories') as $categoryID) {
                $category = new XLite_Model_Category($categoryID);
                foreach ((array)$category->get('products') as $product) {
                    $ef = new XLite_Model_ExtraField();
                    if ($ef->find("name='".addslashes($this->get('name'))."' AND product_id=".$product->get('product_id'))) {
                        $ef->delete();
                    }
                }
            }
        }
    }

    function action_update_fields()
    {
        if (!is_null($this->get('delete')) && !is_null($this->get('delete_fields'))) 
        {
            $category_id = $this->get('category_id');
            foreach ((array)$this->get('delete_fields') as $id) {
                $data = array();
                $ef = new XLite_Model_ExtraField($id);
                $categories = $ef->getCategories();
                if ( !is_array($categories) || count($categories) == 0 ) {
                    $cat = new XLite_Model_Category();
                    $cats = $cat->findAll();
                    $categories = array();
                    foreach ($cats as $v)
                        $categories[] = $v->get('category_id');
                }

                $data = array_diff($categories, array($category_id));
                if ( !is_array($data) || count($data) == 0 ) {
                    $ef->delete();
                    return;
                }

                $ef->set("categories", $data);
                $ef->update();
            }
        }
        elseif (!is_null($this->get('update'))) 
        {
            foreach ((array)$this->get('extra_fields') as $id => $data) 
            {
                $ef = new XLite_Model_ExtraField($id);
                $ef->set("categories_old", $ef->get('categories'));
                $ef->set("properties", $data);
                $ef->update();
            }
        }
    }

    /**
     * Check - specified clean URL unique or not
     * 
     * @param string $cleanURL Clean URL
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkCleanURLUnique($cleanURL)
    {
        $category = new XLite_Model_Category();

        return !$category->find('clean_url = \'' . $cleanURL . '\'');
    }
}
