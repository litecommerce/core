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
 * Categories page controller
 *
 */
class Categories extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        $category = $this->getCategory();

        // DO NOT use "!==" here
        return ($category && $this->getRootCategoryId() != $category->getCategoryId()) 
            ? $category->getName() 
            : 'Manage categories';
    }

    /**
     * Get all memberships
     *
     * @return array
     */
    public function getMemberships()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Membership')->findAllMemberships();
    }

    /**
     * Return current category Id
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return parent::getCategoryId() ?: $this->getRootCategoryId();
    }

    /**
     * Return current (or default) category object
     *
     * @return \XLite\Model\Category
     */
    public function getCategory()
    {
        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategory($this->getCategoryId());

        if (!isset($category)) {
            $category = new \XLite\Model\Category();
        }

        return $category;
    }

    /**
     * Return full list of categories
     *
     * @param integer $rootId ID of the subtree root OPTIONAL
     *
     * @return array
     */
    public function getCategories($categoryId)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Category')->getCategories($categoryId);
    }

    /**
     * doActionDelete
     *
     * @return void
     */
    protected function doActionDelete()
    {
        $parent = $this->getCategory()->getParent();

        if ((bool) \XLite\Core\Request::getInstance()->subcats) {
            \XLite\Core\Database::getRepo('\XLite\Model\Category')->deleteSubcategories($this->getCategoryId());

        } else {
            \XLite\Core\Database::getRepo('\XLite\Model\Category')->delete($this->getCategory());
        }

        $this->setReturnURL(
            $this->buildURL('categories', '', $parent ? array('category_id' => $parent->getCategoryId()) : array())
        );
    }

    /**
     * Update "position" fields
     *
     * @return void
     */
    protected function doActionUpdate()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Category')->updateInBatchById($this->getPostedData());

        $this->setReturnURL(
            $this->buildURL('categories', '', array('category_id' => \XLite\Core\Request::getInstance()->category_id))
        );
    }
}
