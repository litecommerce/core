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
class XLite_Module_ProductAdviser_Main extends XLite_Module_Abstract
{
    /**
     * Module type
     *
     * @var    int
     * @access protected
     * @since  3.0
     */
    public static function getType()
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
    public static function getVersion()
    {
        return '2.12.RC4';
    }

    /**
     * Module description
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getDescription()
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
    public static function showSettingsForm()
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
        /*$w = new XLite_View_Abstract();
        $widgetMethods = array_map('strtolower', get_class_methods($w));
        if (!in_array('isarraypointernth', $widgetMethods)) {
        } else {
            $this->xlite->set('PAPartialWidget', true);
        }
        if ($this->xlite->is('adminZone')) {
        }*/

        /////////////////////////////////////
        // "RelatedProducts" section
        if ($this->xlite->is('adminZone')) {
        }
        /////////////////////////////////////

        /////////////////////////////////////
        // "Recently viewed" section
        if ($this->xlite->is('adminZone')) {
            $this->validateConfig('number_recently_viewed');
        }
        /////////////////////////////////////

        /////////////////////////////////////
        // "New Arrivals" section
        if ($this->xlite->is('adminZone')) {
            $this->validateConfig('number_new_arrivals');
            $this->validateConfig('period_new_arrivals');
        }
        /////////////////////////////////////

        /////////////////////////////////////
        // "Product also buy" section
        if ($this->xlite->is('adminZone')) {
            if ($this->config->ProductAdviser->admin_products_also_buy_enabled != 'Y') {
                XLite_Core_Database::getRepo('XLite_Model_Config')->createOption(
                    array(
                        'category' => 'ProductAdviser',
                        'name'     => 'products_also_buy_enabled',
                        'value'    => 'N'
                    )
                );
            }
        }
        /////////////////////////////////////

        /////////////////////////////////////
        // "Customer Notifications" section
        if ($this->xlite->is('adminZone')) {
            $this->validateConfig('number_notifications', 1);
            $customer_notifications_enabled = ($this->config->ProductAdviser->customer_notifications_mode == '0') ? 'N' : 'Y';
            XLite_Core_Database::getRepo('XLite_Model_Config')->createOption(
                array(
                    'category' => 'ProductAdviser',
                    'name'     => 'customer_notifications_enabled',
                    'value'    => $customer_notifications_enabled
                )
            );
        }
        /////////////////////////////////////

        $inventorySupport = XLite_Core_Operator::isClassExists('XLite_Module_InventoryTracking_Model_Inventory');
        $this->xlite->set('PA_InventorySupport', $inventorySupport);
        if ($inventorySupport) {
            if (!$this->xlite->is('adminZone')) {
            }
        }
        if ($this->xlite->is('adminZone')) {
        }
        $this->xlite->set('ProductAdviserEnabled', true);
    }

    function validateConfig($option, $limit=0)
    {
        $number_orig = $this->config->ProductAdviser->$option;
        $number = intval($number_orig);
        $number_updated = false;
        if ($number < $limit) {
            $number = $limit;
            $number_updated = true;
        } else {
            if (strval($number) != strval($number_orig)) {
            	$number_updated = true;
            }
        }
        if ($number_updated) {
            XLite_Core_Database::getRepo('XLite_Model_Config')->createOption(
                array(
                    'category' => 'ProductAdviser',
                    'name'     => $option,
                    'value'    => $number
                )
            );
        }
    }
}
