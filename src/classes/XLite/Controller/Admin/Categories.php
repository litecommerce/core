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
 * Categories page controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Categories extends \XLite\Controller\Admin\Base\Catalog
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return ($this->getCategory() && $this->getRootCategoryId() !== $this->getCategory()->getCategoryId())
            ? $this->getCategory()->getName()
            : 'Manage categories';
    }

    /**
     * :FIXME: must be removed
     *
     * @param string $method Method to call
     * @param array  $args   Call arguments OPTIONAL
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __call($method, array $args = array())
    {
        $repo = \XLite\Core\Database::getRepo('\XLite\Model\Category');

        return method_exists($repo, $method)
            ? call_user_func_array(array($repo, $method), $args)
            : parent::__call($method, $args);
    }

    /**
     * Get all memberships
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMemberships()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Membership')->findAllMemberships();
    }


    /**
     * doActionDelete
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDelete()
    {
        $parent = $this->getCategory()->getParent();

        \XLite\Core\Database::getRepo('\XLite\Model\Category')->deleteCategory(
            $this->getCategoryId(),
            (bool) \XLite\Core\Request::getInstance()->subcats
        );

        $this->setReturnURL(
            $this->buildURL(
                'categories',
                '',
                $parent ? array('category_id' => $parent->getCategoryId()) : array()
            )
        );
    }

    /**
     * Update "position" fields
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUpdate()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Category')->updateInBatchById($this->getPostedData());

        $this->setReturnURL(
            $this->buildURL(
                'categories',
                '',
                array('category_id' => \XLite\Core\Request::getInstance()->category_id)
            )
        );
    }
}
