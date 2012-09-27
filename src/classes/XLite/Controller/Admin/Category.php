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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller\Admin;

/**
 * Category page controller
 *
 */
class Category extends \XLite\Controller\Admin\Base\Catalog
{
    /**
     * Backward compatibility
     *
     * @var array
     */
    public $params = array('target', 'category_id', 'mode');

    // {{{ Abstract method implementations

    /**
     * Check if we need to create new product or modify an existsing one
     *
     * NOTE: this function is public since it's neede for widgets
     *
     * @return boolean
     */
    public function isNew()
    {
        return !$this->getCategory()->isPersistent();
    }

    /**
     * Return class name for the controller main form
     *
     * @return string
     */
    protected function getFormClass()
    {
        return '\XLite\View\Form\Category\Modify\Single';
    }

    /**
     * Alias
     *
     * @return \XLite\Model\Category
     */
    protected function getEntity()
    {
        return $this->getCategory();
    }

    // }}}

    // {{{ Pages

    /**
     * Get pages sections
     *
     * @return array
     */
    public function getPages()
    {
        $list = parent::getPages();
        $list['category_modify'] = $this->getCategory()->isPersistent() ? 'Modify category' : 'Add new category';

        return $list;
    }

    /**
     * Get pages templates
     *
     * @return array
     */
    protected function getPageTemplates()
    {
        $list = parent::getPageTemplates();
        $list['category_modify'] =
        $list['default']         = 'categories/modify/body.tpl';

        return $list;
    }

    // }}}

    // {{{ Data management

    /**
     * Check current category
     *
     * @return boolean
     */
    public function isRoot()
    {
        // DO NOT use "===" here
        return $this->getCategory()->getCategoryId() == $this->getRootCategoryId();
    }

    /**
     * Return current (or default) category object
     *
     * @return \XLite\Model\Category
     */
    public function getCategory()
    {
        $category = parent::getCategory();

        if (!isset($category)) {
            $category = new \XLite\Model\Category();
        }

        return $category;
    }

    /**
     * Return ID for parent category
     *
     * @return integer
     */
    public function getParentCategoryId()
    {
        $result = intval(\XLite\Core\Request::getInstance()->parent_id);

        if (0 >= $result) {
            $result = $this->getRootCategoryId();
        }

        return $result;
    }

    /**
     * Check if category has an image
     *
     * @return boolean
     */
    public function hasImage()
    {
        return !$this->isNew() && !$this->isRoot();
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->isNew() ? 'Add category' : $this->getCategory()->getName();
    }

    /**
     * Get posted data
     *
     * @param string $field Name of the field to retrieve OPTIONAL
     *
     * @return mixed
     */
    protected function getPostedData($field = null)
    {
        $result = parent::getPostedData($field);

        if (!isset($field) || 'membership' === $field) {
            $membership = \XLite\Core\Database::getRepo('\XLite\Model\Membership')->find(
                isset($field) ? $result : $result['membership']
            );

            if (isset($field)) {
                $result = $membership;

            } else {
                $result['membership'] = $membership;
            }
        }

        return $result;
    }

    // }}}

    // {{{ Action handlers

    /**
     * doActionAdd
     *
     * @return void
     */
    protected function doActionAdd()
    {
        $category = \XLite\Core\Database::getRepo('\XLite\Model\Category')->insert(
            array('parent_id' => $this->getParentCategoryId()) + $this->getPostedData()
        );

        if (isset($category)) {
            \XLite\Core\TopMessage::addInfo('New category has been added successfully');

            $this->setReturnURL($this->buildURL('categories', '', array('category_id' => $category->getCategoryId())));
        }
    }

    /**
     * doActionUpdate
     *
     * @return void
     */
    protected function doActionUpdate()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Category')->update($this->getCategory(), $this->getPostedData());

        \XLite\Core\TopMessage::addInfo('Category has been successfully updated');
    }

    // }}}
}
