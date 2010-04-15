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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Customer_Callback extends XLite_Controller_Customer_Abstract
{
    function action_callback()
    {
        if (isset($_REQUEST["order_id_name"])) {
            // some of gateways can't accept return url on run-time and
            // use the one set in merchant account, so we can't pass
            // 'order_id' in run-time, instead pass the order id parameter name
            $order_id_name = $_REQUEST["order_id_name"];
        } else {
            $order_id_name = "order_id";
        }
        if (!isset($_REQUEST[$order_id_name])) {
            $this->doDie("The order ID variable '$order_id_name' is not found in request");
        }
        $cart = new XLite_Model_Order($_REQUEST[$order_id_name]);
        if (!$cart->is("exists")) {
            $this->doDie("Order #".$cart->get("order_id")." was not found. Please contact administrator.");
        }

		// FIXME - orginal code; the "handleRequest" function is not exists for the "PaymentMethod" class
        $cart->getPaymentMethod()->handleRequest($cart);

        $this->set("silent", true);
    }
}
