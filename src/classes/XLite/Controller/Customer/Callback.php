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

/**
 * Payment method callback
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Customer_Callback extends XLite_Controller_Customer_ACustomer
{
    /**
     * This controller is always accessible
     * TODO - check if it's really needed; remove if not
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function checkStorefrontAccessability()
    {
        return true;
    }

    /**
     * Callback 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionCallback()
    {
        if (isset(XLite_Core_Request::getInstance()->order_id_name)) {
            /**
             * some of gateways can't accept return url on run-time and
             * use the one set in merchant account, so we can't pass
             * 'order_id' in run-time, instead pass the order id parameter name
             */
            $orderIdName = XLite_Core_Request::getInstance()->order_id_name;

        } else {
            $orderIdName = 'order_id';
        }

        if (!isset(XLite_Core_Request::getInstance()->$orderIdName)) {
            $this->doDie('The order ID variable \'' . $orderIdName . '\' is not found in request');
        }

        $cart = new XLite_Model_Order(XLite_Core_Request::getInstance()->$orderIdName);
        if (!$cart->isExists()) {
            $this->doDie('Order #' . $cart->get('order_id') . ' was not found. Please contact administrator.');
        }

        $paymentMethod = $cart->getPaymentMethod();
        if (!($paymentMethod instanceof XLite_Model_PaymentMethod_CreditCard)) {
            $this->doDie(
                'Order #' . $cart->get('order_id') . ' has not assigned online payment methods.'
                . ' Please contact administrator.'
            );
        }

        $cart->getPaymentMethod()->handleRequest($cart, XLite_Model_PaymentMethod_CreditCard::CALL_BACK);

        $this->set('silent', true);
    }
}
