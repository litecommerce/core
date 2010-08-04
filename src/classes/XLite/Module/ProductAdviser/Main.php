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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\ProductAdviser;

define('CUSTOMER_NOTIFICATION_PRODUCT', 'product');
define('CUSTOMER_NOTIFICATION_PRICE', 'price');
define('CUSTOMER_REQUEST_QUEUED', 'Q');
define('CUSTOMER_REQUEST_UPDATED', 'U');
define('CUSTOMER_REQUEST_SENT', 'S');
define('CUSTOMER_REQUEST_DECLINED', 'D');

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Main extends \XLite\Module\AModule
{
    /**
     * Module type
     *
     * @var    int
     * @access protected
     * @since  3.0
     */
    public function getModuleType()
    {
        return self::MODULE_GENERAL;
    }

    /**
     * Module version
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public function getVersion()
    {
        return '3.0';
    }

    /**
     * Module description
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public function getDescription()
    {
        return 'ProductAdviser add-on introduces multiple cross-selling features and a customer notification mechanism';
    }

    /**
     * Determines if we need to show settings form link
     *
     * @return bool
     * @access public
     * @since  3.0
     */
    public function showSettingsForm()
    {
        return true;
    }

    /**
     * Perform some actions at startup
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public function init() 
    {
        parent::init();

        // FIXME - trying to instantiate abstract class
        // TODO - check if this code is really needed
        /*$w = new \XLite\View\AView();
        $widgetMethods = array_map('strtolower', get_class_methods($w));
        if (!in_array('isarraypointernth', $widgetMethods)) {
        } else {
            \XLite::getInstance()->set('PAPartialWidget', true);
        }
        if (\XLite::getInstance()->is('adminZone')) {
        }*/

        /////////////////////////////////////
        // "RelatedProducts" section
        if (\XLite::getInstance()->is('adminZone')) {
        }
        /////////////////////////////////////

        /////////////////////////////////////
        // "Recently viewed" section
        if (\XLite::getInstance()->is('adminZone')) {
            $this->validateConfig('number_recently_viewed');
        }
        /////////////////////////////////////

        /////////////////////////////////////
        // "New Arrivals" section
        if (\XLite::getInstance()->is('adminZone')) {
            $this->validateConfig('number_new_arrivals');
            $this->validateConfig('period_new_arrivals');
        }
        /////////////////////////////////////

        /////////////////////////////////////
        // "Product also buy" section
        /* TODO - rework
        if (
            \XLite::getInstance()->isAdminZone()
            && \XLite\Core\Config::getInstance()->ProductAdviser->admin_products_also_buy_enabled != 'Y'
        ) {
            \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
                array(
                    'category' => 'ProductAdviser',
                    'name'     => 'products_also_buy_enabled',
                    'value'    => 'N'
                )
            );
        }
        */
        /////////////////////////////////////

        /////////////////////////////////////
        // "Customer Notifications" section
        if (\XLite::getInstance()->isAdminZone()) {
            $this->validateConfig('number_notifications', 1);
            $customer_notifications_enabled = (\XLite\Core\Config::getInstance()->ProductAdviser->customer_notifications_mode == '0') ? 'N' : 'Y';
            /* TODO - rework
            \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
                array(
                    'category' => 'ProductAdviser',
                    'name'     => 'customer_notifications_enabled',
                    'value'    => $customer_notifications_enabled
                )
            );
            */
        }
        /////////////////////////////////////

        $inventorySupport = \XLite\Core\Operator::isClassExists('\XLite\Module\InventoryTracking\Model\Inventory');
        \XLite::getInstance()->set('PA_InventorySupport', $inventorySupport);
        if ($inventorySupport) {
            if (!\XLite::getInstance()->is('adminZone')) {
            }
        }
        if (\XLite::getInstance()->is('adminZone')) {
        }
        \XLite::getInstance()->set('ProductAdviserEnabled', true);
    }

    function validateConfig($option, $limit=0)
    {
        $number_orig = \XLite\Core\Config::getInstance()->ProductAdviser->$option;
        $number = intval($number_orig);
        $number_updated = false;
        if ($number < $limit) {
            $number = $limit;
            $number_updated = true;

        } elseif (strval($number) != strval($number_orig)) {
          	$number_updated = true;
        }

        if ($number_updated) {
            \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
                array(
                    'category' => 'ProductAdviser',
                    'name'     => $option,
                    'value'    => $number
                )
            );
        }
    }
}
