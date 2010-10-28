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
class Categories extends \XLite\Controller\Admin\Catalog
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

    /**
     * doActionDelete 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        $parentId = $this->getCategory()->getParent()->getCategoryId();
        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategory($this->getCategoryId());

        \XLite\Core\Database::getRepo('XLite\Model\Category')
            ->{'delete' . (((bool) \XLite\Core\Request::getInstance()->subcats) ? 'Subcategories' : '')}($category);

        $this->setReturnUrl($this->buildURL('categories', '', array('category_id' => $parentId)));
    }


    /**
     * Get categories list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategories()
    {
        return $this->getCategory()->getSubcategories();
    }

    /**
     * Get all memberships
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMemberships()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Membership')->findAllMemberships();
    }
}
