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
 * @since     1.0.18
 */

namespace XLite\View;

/**
 * Administrator panel 
 * 
 * @see   ____class_see____
 * @since 1.0.18
 *
 * @ListChild (list="main", weight="100", zone="admin")
 */
class AdminPanel extends \XLite\View\AView
{

    const ITEM_TEMPLATE    = 'template';
    const ITEM_TARGET      = 'target';
    const ITEM_ICON        = 'icon';
    const ITEM_TITLE       = 'title';
    const ITEM_DESCRIPTION = 'description';

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'css/admin_panel.css';

        return $list;
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'main/parts/panel.tpl';
    }

    /**
     * Get items 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.18
     */
    protected function getItems()
    {
        $list = array();

        foreach ($this->defineItems() as $item) {
            if (!isset($item[self::ITEM_TEMPLATE])) {
                $item[self::ITEM_TEMPLATE] = $this->getDefaultItemTemplate();
            }
            if (!isset($item[self::ITEM_ICON])) {
                $item[self::ITEM_ICON] = 'images/spacer.gif';
            }

            if ($this->checkItemACL($item)) {
                $list[] = $item;
            }
        }

        return $list;
    }

    /**
     * Define items 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.18
     */
    protected function defineItems()
    {
        return array(
            array(
                self::ITEM_TARGET      => 'order_list',
                self::ITEM_ICON        => 'images/menu/icon_orders.gif',
                self::ITEM_TITLE       => 'Orders',
                self::ITEM_DESCRIPTION => 'Manage orders placed at your store',
            ),
            array(
                self::ITEM_TARGET      => 'settings',
                self::ITEM_ICON        => 'images/menu/icon_general.gif',
                self::ITEM_TITLE       => 'General Settings',
                self::ITEM_DESCRIPTION => 'Configure your store',
            ),
            array(
                self::ITEM_TARGET      => 'categories',
                self::ITEM_ICON        => 'images/menu/icon_categories.gif',
                self::ITEM_TITLE       => 'Categories',
                self::ITEM_DESCRIPTION => 'Online catalog structure set-up',
            ),
            array(
                self::ITEM_TARGET      => 'orders_stats',
                self::ITEM_ICON        => 'images/menu/icon_statistics.gif',
                self::ITEM_TITLE       => 'Statistics',
                self::ITEM_DESCRIPTION => 'Review statistics on various aspects of your store\'s operation',
            ),
            array(
                self::ITEM_TARGET      => 'product_list',
                self::ITEM_ICON        => 'images/menu/icon_products.gif',
                self::ITEM_TITLE       => 'Products',
                self::ITEM_DESCRIPTION => 'Manage your product inventory',
            ),
            array(
                self::ITEM_TARGET      => 'addons_list_installed',
                self::ITEM_ICON        => 'images/menu/icon_modules.gif',
                self::ITEM_TITLE       => 'Add-ons',
                self::ITEM_DESCRIPTION => 'Expand the functionality of your store by installing and using add-on modules',
            ),
            array(
                self::ITEM_TARGET      => 'profile_list',
                self::ITEM_ICON        => 'images/menu/icon_users.gif',
                self::ITEM_TITLE       => 'Users',
                self::ITEM_DESCRIPTION => 'Manage customer and administrator accounts',
            ),
            array(
                self::ITEM_TARGET      => 'db_backup',
                self::ITEM_ICON        => 'images/menu/icon_catalog.gif',
                self::ITEM_TITLE       => 'Store Maintenance',
                self::ITEM_DESCRIPTION => 'Make back-up of your store database',
            ),
        );
    }

    /**
     * Get default item template 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.18
     */
    protected function getDefaultItemTemplate()
    {
        return 'menu_item.tpl';
    }

    /**
     * Check item access
     * 
     * @param array $item Item
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.18
     */
    protected function checkItemACL(array $item)
    {
        $auth = \XLite\Core\Auth::getInstance();

        $result = $auth->isPermissionAllowed(\XLite\Model\Role\Permission::ROOT_ACCESS);

        if (!$result) {
            $catalog = array('categories', 'product_list');
            $orders = array('order_list', 'orders_stats');
            $users = array('profile_list');

            $result = (in_array($item[self::ITEM_TARGET], $orders) && $auth->isPermissionAllowed('manage orders'))
                || (in_array($item[self::ITEM_TARGET], $catalog) && $auth->isPermissionAllowed('manage catalog'))
                || (in_array($item[self::ITEM_TARGET], $users) && $auth->isPermissionAllowed('manage users'));
        }

        return $result;
    }
}

