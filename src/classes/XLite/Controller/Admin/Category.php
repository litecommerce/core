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
     * FIXME- backward compatibility
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    public $params = array('target', 'category_id', 'mode');


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
        $category = ('add_child' === \XLite\Core\Request::getInstance()->mode)
            ? new \XLite\Model\Category()
            : parent::getCategory();

        $category->setEditLanguageCode($this->getCurrentLanguage());

        return $category;
    }

    /**
     * Return TRUE if category can have image
     * Return FALSE if category cannot have image (new or root one)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.7
     */
    public function hasImage()
    {
        return 'add_child' !== \XLite\Core\Request::getInstance()->mode
            && $this->getRootCategoryId() !== $this->getCategoryId();
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
            ? static::t('Add category')
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
            $this->addLocationNode(static::t('Root category'));
        }

        return ('add_child' === \XLite\Core\Request::getInstance()->mode)
            ? static::t('Add category')
            : static::t('Details');
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
        $properties = $this->validateCategoryData(true);

        if ($properties) {

            $category = \XLite\Core\Database::getRepo('\XLite\Model\Category')->insert(
                array('parent_id' => $this->getCategoryId()) + $properties
            );

            $category->setEditLanguageCode($this->getCurrentLanguage());
            \XLite\Core\Database::getRepo('\XLite\Model\Category')->update(
                $category,
                $properties
            );

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
        $properties = $this->validateCategoryData();

        if ($properties) {

            \XLite\Core\Database::getRepo('\XLite\Model\Category')
                ->update($this->getCategory(), $properties);

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
