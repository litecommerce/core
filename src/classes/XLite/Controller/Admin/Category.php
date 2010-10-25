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

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Category extends \XLite\Controller\Admin\Catalog
{
    /**
     * getModelObject
     *
     * @return \XLite\Model\AModel
     * @access protected
     * @since  3.0.0
     */
    protected function getModelObject()
    {
        return $this->getCategory();
    }



    public $page = "category_modify";
    public $pages = array(
        "category_modify" => "Add/Modify category",
    );

    public $pageTemplates = array(
        "category_modify" => "categories/add_modify_body.tpl",
        "extra_fields"    => "categories/category_extra_fields.tpl",
    );

    public $params = array('target', 'category_id', 'mode', 'message', 'page');
    public $order_by = 0;

    protected $addModes = array(
        'add_child',
        'add_before',
        'add_after'
    );

    /**
     * Initialize controller 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function init()
    {
        parent::init();

        if (!in_array($this->mode, $this->addModes) && $this->mode == "modify") {

            $this->pages['category_modify'] = "Modify category";

            if ($this->config->General->enable_categories_extra_fields) {
                $this->pages['extra_fields'] = "Extra fields";
            }

        } else {
            $this->pages['category_modify'] = "Add new category";
        }
    }

    function getExtraFields()
    {
        if (is_null($this->extraFields)) 
        {
            $ef = new \XLite\Model\ExtraField();
            $extraFields = $ef->findAll("product_id=0");  // global fields
            foreach ($extraFields as $extraField_key => $extraField)
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

    /**
     * Get category_id of the parent category
     * 
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getParentCategoryId($categoryId)
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategoryId($categoryId ? $categoryId : $this->getCategoryId());
    }
    
    /**
     * Get \XLite\Model\Category object of current category
     * 
     * @return \XLite\Model\Category
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategory()
    {
        if (is_null($this->category) && !in_array($this->mode, $this->addModes)) {

            $this->category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategory($this->getCategoryId());
        }

        if (is_null($this->category)) {
            $this->category = new \XLite\Model\Category();
        }

        return $this->category;
    }

    /**
     * Prepare location path from category path 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLocationPath()
    {
        $result = array();

        $categoryPath = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryPath($this->getCategoryId());

        if (is_array($categoryPath)) {
            
            foreach ($categoryPath as $category) {
                $result[$category->name] = 'admin.php?target=categories&category_id=' . $category->category_id;
            }
        }

        return $result;
    }

    /**
     * Validate values passed from the REQUEST for updating/creating category 
     * 
     * @param bool $isNewObject Flag - is a data for a new category or for updaing existing category
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function validateCategoryData($isNewObject = false)
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        $data = array();
        $isValid = true;

        $fieldsSet = array(
            'name',
            'description',
            'meta_tags',
            'meta_desc',
            'meta_title',
            'enabled',
            'membership_id',
            'clean_url',
            'show_title',
        );

        if (!$isNewObject) {
            $data['category_id'] = intval($postedData['category_id']);
        }

        foreach ($fieldsSet as $field) {

            if (isset($postedData[$field])) {
                $data[$field] = $postedData[$field];
            }

            // 'Clean URL' is optional field and must be a unique
            if ('clean_url' === $field && isset($data['clean_url'])) {

                $data['clean_url'] = $this->sanitizeCleanURL($data['clean_url']);

                if (!empty($data['clean_url']) && !$this->isCleanURLUnique($data['clean_url'], (!$isNewObject ? $data['category_id'] : null))) {

                    \XLite\Core\TopMessage::getInstance()->add(
                        'The Clean URL you specified is already in use. Please specify another Clean URL',
                        \XLite\Core\TopMessage::ERROR
                    );

                    $isValid = false;
                }

            // 'Name' is a mandatory field
            } elseif ('name' === $field) {

                if (!isset ($data['name']) || 0 == strlen(trim($data['name']))) {

                    \XLite\Core\TopMessage::getInstance()->add(
                        'Not empty category name must be specified',
                        \XLite\Core\TopMessage::ERROR
                    );

                    $isValid = false;
                }

            // 'Enabled' field value must be either 0 or 1
            } elseif ('enabled' === $field) {
                $data['enabled'] = isset($data['enabled']) && $data['enabled'] == '1' ? 1 : 0;
            }
        }

        return $isValid ? $data : false;
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
    protected function isCleanURLUnique($cleanURL, $categoryId = null)
    {
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryByCleanUrl($cleanURL);

        return !isset($result) || (!is_null($categoryId) && intval($categoryId ) == intval($result->getCategoryId()));
    }

    /**
     * action_modify 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_modify()
    {
        if ($properties = $this->validateCategoryData()) {

            $code = $this->getCurrentLanguage();

            // update category
            $category = \XLite\Core\Database::getEM()->find('XLite\Model\Category', $properties['category_id']);

            $category->map($properties);
            $category->getTranslation($code)->name = $properties['name'];
            $category->getTranslation($code)->description = $properties['description'];
            $category->getTranslation($code)->meta_tags = $properties['meta_tags'];
            $category->getTranslation($code)->meta_desc = $properties['meta_desc'];
            $category->getTranslation($code)->meta_title = $properties['meta_title'];

            \XLite\Core\Database::getEM()->persist($category);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\Database::getRepo('XLite\Model\Category')->cleanCache();

            // update category image
//            $image = $category->get('image');
//            $image->handleRequest();
        }
    }

    /**
     * add_child action
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_add_child()
    {
        $this->addCategory('child');
    }

    /**
     * add_before action
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_add_before()
    {
        $this->addCategory('before');
    }

    /**
     * add_after action
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_add_after()
    {
        $this->addCategory('after');
    }

    /**
     * Add category common action
     * 
     * @param string $operation Name of method
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addCategory($operation)
    {
        if (in_array($operation, array('child', 'before', 'after'))) {

            if ($properties = $this->validateCategoryData(true)) {

                // create category
                if ('child' == $operation) {
                    $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->addChild($this->getCategoryId());

                } else {
                    $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->addSibling($this->getCategoryId(), ('before' == $operation));
                }

                // Add category properties
                $category->map($properties);

                $code = $this->getCurrentLanguage();
                $category->getTranslation($code)->name = $properties['name'];
                $category->getTranslation($code)->description = $properties['description'];
                $category->getTranslation($code)->meta_tags = $properties['meta_tags'];
                $category->getTranslation($code)->meta_desc = $properties['meta_desc'];
                $category->getTranslation($code)->meta_title = $properties['meta_title'];

                \XLite\Core\Database::getEM()->persist($category);
                \XLite\Core\Database::getEM()->flush();

                \XLite\Core\Database::getRepo('XLite\Model\Category')->cleanCache();
            }

            $this->redirect('admin.php?target=categories&category_id=' . $category->category_id);
        }
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
        $_postData = \XLite\Core\Request::getInstance()->getData();
        foreach ($_postData as $post_key => $post_value)
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
                $ef = new \XLite\Model\ExtraField();
                $ef->set('properties', $_postData);
                $ef->setCategoriesList($categories);
                $ef->create();
            }
            else
            {
                // buld add
                $categories = (array)$this->get('add_categories');
                if (!empty($categories)) {
                    foreach ($categories as $categoryID) {
                        $category = new \XLite\Model\Category($categoryID);
                        foreach ((array)$category->get('products') as $product) {
                            $ef = new \XLite\Model\ExtraField();
                            $ef->set('properties', $_postData);
                            $ef->set('product_id', $product->get('product_id'));
                            $ef->create();
                        }
                    }
                } else {
                    $ef = new \XLite\Model\ExtraField();
                    $ef->set('properties', $_postData);
                    $ef->create();
                }
            }
        }
        // DELETE field
        elseif (!is_null($this->get('delete_field'))) {
            foreach ((array)$this->get('add_categories') as $categoryID) {
                $category = new \XLite\Model\Category($categoryID);
                foreach ((array)$category->get('products') as $product) {
                    $ef = new \XLite\Model\ExtraField();
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
                $ef = new \XLite\Model\ExtraField($id);
                $categories = $ef->getCategories();
                if ( !is_array($categories) || count($categories) == 0 ) {
                    $cat = new \XLite\Model\Category();
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

                $ef->set('categories', $data);
                $ef->update();
            }
        }
        elseif (!is_null($this->get('update'))) 
        {
            foreach ((array)$this->get('extra_fields') as $id => $data) 
            {
                $ef = new \XLite\Model\ExtraField($id);
                $ef->set('categories_old', $ef->get('categories'));
                $ef->set('properties', $data);
                $ef->update();
            }
        }
    }

}
