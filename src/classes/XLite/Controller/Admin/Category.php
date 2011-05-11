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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Category page controller
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Category extends \XLite\Controller\Admin\Base\Catalog
{

    /**
     * Get pages sections
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPages()
    {
        return array(
            'category_modify' => ($this->getCategory()->getCategoryId()) ? 'Modify category' : 'Add new category'
        );
    }

    /**
     * Get pages templates
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPageTemplates()
    {
        return array(
            'category_modify' => 'categories/add_modify_body.tpl',
            'default'         => 'categories/add_modify_body.tpl',
        );
    }

    /**
     * Return current (or default) category object
     *
     * @return \XLite\Model\Category
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCategory()
    {
        return ('add_child' === \XLite\Core\Request::getInstance()->mode) 
            ? new \XLite\Model\Category()
            : parent::getCategory();
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return ('add_child' === \XLite\Core\Request::getInstance()->mode) 
            ? $this->t('Add category')
            : parent::getCategory()->getName();
    }


    /**
     * Common method to determine current location 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        if (
            'modify' == \XLite\Core\Request::getInstance()->mode 
            && $this->getRootCategoryId() == $this->getCategoryId()
        ) {
            $this->addLocationNode($this->t('Root category'));
        }

        return ('add_child' === \XLite\Core\Request::getInstance()->mode) 
            ? $this->t('Add category')
            : $this->t('Details');
    }

    /**
     * Create/update image
     *
     * @param integer $categoryId Image category ID OPTIONAL
     *
     * @return \XLite\Model\Image\Category\Image
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function saveImage($categoryId = null)
    {
        if (empty($categoryId)) {
            $categoryId = $this->getCategoryId();
        }   

        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->find($categoryId);

        $img = $category->getImage();

        if (!$img) {
            $img = new \XLite\Model\Image\Category\Image();
        }

        if ($img->loadFromRequest('postedData', 'image')) {
            if (!$img->getCategory()) {
                $img->setCategory($category);
                $category->setImage($img);
                \XLite\Core\Database::getEM()->persist($img);
            }   
        }   

        return $img;
    }

    /**
     * doActionAddChild 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionAddChild()
    {
        if ($properties = $this->validateCategoryData(true)) {

            $category = \XLite\Core\Database::getRepo('XLite\Model\Category')
                ->insert(array('parent_id' => $this->getCategoryId()) + $properties);

            $this->saveImage($category->getCategoryId());

            $this->setReturnURL($this->buildURL('categories', '', array('category_id' => $category->getCategoryId())));
        }
    }

    /**
     * doActionModify 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionModify()
    {
        if ($properties = $this->validateCategoryData()) {

            $this->saveImage();

            \XLite\Core\Database::getRepo('XLite\Model\Category')
                ->updateById($properties['category_id'], $properties);

            $this->setReturnURL($this->buildURL('categories', '', array('category_id' => $properties['category_id'])));
        }
    }

    /**
     * Validate values passed from the REQUEST for updating/creating category 
     * 
     * @param boolean $isNewObject Flag - is a data for a new category or for updaing existing category OPTIONAL
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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

                if (
                    !empty($data['clean_url']) 
                    && !$this->isCleanURLUnique($data['clean_url'], (!$isNewObject ? $data['category_id'] : null))
                ) {

                    \XLite\Core\TopMessage::addError(
                        'The Clean URL you specified is already in use. Please specify another Clean URL'
                    );

                    $isValid = false;
                }

            } elseif ('name' === $field) {
                // 'Name' is a mandatory field

                if (!isset ($data['name']) || 0 == strlen(trim($data['name']))) {

                    \XLite\Core\TopMessage::addError(
                        'Not empty category name must be specified'
                    );

                    $isValid = false;
                }

            } elseif ('enabled' === $field) {
                // 'Enabled' field value must be either 0 or 1
                $data['enabled'] = ((isset($data['enabled']) && $data['enabled'] == '1') ? 1 : 0);
            }
        }

        return $isValid ? $data : false;
    }

    /**
     * Check - specified clean URL unique or not
     * 
     * @param string  $cleanURL   Clean URL
     * @param integer $categoryId Category Id OPTIONAL
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isCleanURLUnique($cleanURL, $categoryId = null)
    {
        $result = \XLite\Core\Database::getRepo('XLite\Model\Category')->findOneByCleanURL($cleanURL);

        return !isset($result)
            || (!is_null($categoryId) && intval($categoryId) == intval($result->getCategoryId()));
    }
}
