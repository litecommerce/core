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
 * @since     3.0.0
 */

namespace XLite\View;

/**
 * Delete category widget
 * 
 * @see   ____class_see____
 * @since 3.0.0
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class DeleteCategory extends \XLite\View\SimpleDialog
{
    /**
     * Return list of allowed targets
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();

        $list[] = 'categories';

        return $list;
    }


    /**
     * Return title 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Confirmation';
    }

    /**
     * Return file name for the center part template 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBody()
    {
        return 'categories/delete_confirmation.tpl';
    }

    /**
     * Get subcategories paameter
     * 
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSubcats()
    {
        return \XLite\Core\Request::getInstance()->subcats;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && 'delete' == \XLite\Core\Request::getInstance()->mode;
    }
}

