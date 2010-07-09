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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GoogleCheckout_Main extends XLite_Module_AModule
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
        return '2.1.RC2';
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
        return 'GoogleCheckout module';
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
     * Return link to settings form
     *
     * @return string
     * @access public
     * @since  3.0
     */
    public static function getSettingsForm()
    {
       return "admin.php?target=payment_method&payment_method=google_checkout";
    }

    /**
     * Perform some actions at startup
     * FIXME: must be completely revised
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public function init()
    {
        parent::init();

        $this->registerPaymentMethod('google_checkout');

        $payment_method = XLite_Model_PaymentMethod::factory('google_checkout');
        $params = $payment_method->get('params');

        if (!empty($params['disable_customer_notif'])) {
            $this->xlite->set('gcheckout_disable_customer_notif', true);
        }

        if (!$this->xlite->is('adminZone')) {
            if (!empty($params['display_product_note']) && $payment_method->is('parent_enabled')) {
                $this->xlite->set('gcheckout_display_product_note', true);
            }

            $currency = empty($params['currency']) ?: $params['currency'];
            switch ($currency) {
                case "USD":
                case "GBP":
                break;
                default:
                    $currency = "USD";
                break;
            }
            $this->xlite->set('gcheckout_currency', $currency);
            $this->xlite->set('gcheckout_remove_discounts', !empty($params['remove_discounts']));
        }

        $this->xlite->set('GoogleCheckoutEnabled',true);
    }
}
