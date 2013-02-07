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

namespace XLite\Module\Education\Skin;

/**
 * Skin customization module
 *
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @return string
     */
    public static function getAuthorName()
    {
        return 'Creative Development LLC';
    }

    /**
     * Module name
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'Skin customization';
    }

    /**
     * Get module major version
     *
     * @return string
     */
    public static function getMajorVersion()
    {
        return '1.1';
    }

    /**
     * Module version
     *
     * @return string
     */
    public static function getMinorVersion()
    {
        return '0';
    }

    /**
     * Module description
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'Skin customization module for educational use only';
    }

    /**
     * Register the module skins.
     *
     * @return array
     */
    public static function getSkins()
    {
        return array(
            \XLite::CUSTOMER_INTERFACE => array(
                'education.skin.example',
                'education.skin.example2',
            ),
        );
    }

    /**
     * Decorator run this method at the end of cache rebuild
     *
     * @return void
     */
    public static function runBuildCacheHandler()
    {
        parent::runBuildCacheHandler();

        \XLite\Core\Layout::getInstance()->removeClassFromList(
             'XLite\Module\CDev\Bestsellers\View\Bestsellers',
             'sidebar.first',
             \XLite\Model\ViewList::INTERFACE_CUSTOMER
        );
        
        \XLite\Core\Layout::getInstance()->addClassToList(
             'XLite\Module\CDev\Bestsellers\View\Bestsellers',
             'sidebar.second',
             array(
                 'zone'   => \XLite\Model\ViewList::INTERFACE_CUSTOMER,
                 'weight' => 200,
             )
        );

        \XLite\Core\Layout::getInstance()->removeClassFromList(
             'XLite\View\Minicart',
             'layout.header.right',
             \XLite\Model\ViewList::INTERFACE_CUSTOMER
        );
        
        \XLite\Core\Layout::getInstance()->addClassToList(
             'XLite\View\Minicart',
             'sidebar.second',
             array(
                 'zone'   => \XLite\Model\ViewList::INTERFACE_CUSTOMER,
             )
        );
        
        \XLite\Core\Layout::getInstance()->removeTemplateFromList(
             'items_list/product/parts/common.display-modes.tpl',
             'itemsList.product.grid.customer.header',
             \XLite\Model\ViewList::INTERFACE_CUSTOMER
        );

        \XLite\Core\Layout::getInstance()->addTemplateToList(
             'items_list/product/parts/common.display-modes.tpl',
             'itemsList.product.grid.customer.header',
             array(
                 'zone'   => \XLite\Model\ViewList::INTERFACE_CUSTOMER,
                 'weight' => 10,
             )
        );
        
        \XLite\Core\Layout::getInstance()->removeTemplateFromList(
             'items_list/product/parts/common.sort-options.tpl',
             'itemsList.product.grid.customer.header',
             \XLite\Model\ViewList::INTERFACE_CUSTOMER
        );

        \XLite\Core\Layout::getInstance()->addTemplateToList(
             'items_list/product/parts/common.sort-options.tpl',
             'itemsList.product.grid.customer.header',
             array(
                 'zone'   => \XLite\Model\ViewList::INTERFACE_CUSTOMER,
                 'weight' => 20,
             )
        );        
    }
}
