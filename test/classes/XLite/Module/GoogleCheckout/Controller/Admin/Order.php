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
class XLite_Module_GoogleCheckout_Controller_Admin_Order extends XLite_Controller_Admin_Order implements XLite_Base_IDecorator
{
    public $page = "order_info";
    public $payment_method = null;
    public $googleId = null;

    function init()
    {
        if ($this->xlite->get('AOMEnabled')) {
            $this->pages['google_checkout'] = "Google Checkout";
            $this->pageTemplates['google_checkout'] = "modules/GoogleCheckout/order.tpl";
        } else {
            $this->pages = array(
                    "order_info"	=> "Order #%s Info",
                    "google_checkout"	=> "Google Checkout"
                );

            $this->pageTemplates = array(
                    "order_info"	=> "order/order.tpl",
                    "google_checkout"	=> "modules/GoogleCheckout/order.tpl"
                );
        }

        if (!in_array('page', $this->params)) {
            $this->params[] = "page";
        }

        parent::init();

        if (!$this->xlite->get('AOMEnabled')) {
            foreach ($this->pages as $key => $page) {
                $this->pages[$key] = sprintf($page,$this->order_id);
            }
        }

        if (!$this->is('validGoogleOrder')) {
            unset($this->pages['google_checkout']);
            unset($this->pageTemplates['google_checkout']);
            return;
        } else {
            if ($this->xlite->get('AOMEnabled')) {
    			unset($this->pages['order_edit']);
    			unset($this->pageTemplates['order_edit']);
    			if ($this->get('page') == "order_edit") {
                    $this->redirect("admin.php?target=order&order_id=".$this->get('order_id')."&page=order_info");
    				return;
    			}
            }
        }

        if ($this->get('action')) {
            $this->payment_method = new XLite_Model_PaymentMethod('google_checkout');
            require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';
        }

        $this->googleId = addslashes($this->get('orderGoogleId'));
    }

    function getOrderGoogleId()
    {
        return $this->getComplex('order.google_id');
    }

    function isValidGoogleOrder()
    {
        return ($this->getComplex('order.google_id') > 0) ? true : false;
    }


    function isGoogleAllowCharge()
    {
        return ((in_array($this->getComplex('order.google_details.financial_state'), array('REVIEWING', "CHARGEABLE", "CHARGED")) && $this->getComplex('order.googleRemainCharge') > 0) ? true : false);
    }

    function isGoogleAllowRefund()
    {
        // disallow refund order if order total equal or lower than zero
        // in case order payed by bonus points
        if ($this->getComplex('order.total') <= 0) {
            return false;
        }

        return (($this->getComplex('order.google_details.financial_state') == "CHARGED" && in_array($this->getComplex('order.google_status'), array("", "P"))) ? true : false);
    }

    function isGoogleAllowCancel()
    {
        // disallow cancel order if order total equal or lower than zero
        // in case order payed by bonus points
        if ($this->getComplex('order.total') <= 0) {
            return false;
        }

        return ((in_array($this->getComplex('order.google_details.financial_state'), array('CHARGEABLE', "PAYMENT_DECLINED")) || $this->getComplex('order.google_status') == "R") ? true : false);
    }

    function isGoogleAllowDeliver()
    {
        return ((in_array($this->getComplex('order.google_details.fulfillment_state'), array('NEW', "PROCESSING"))) ? true : false);
    }

    function isGoogleAllowProcess()
    {
        return (($this->getComplex('order.google_details.fulfillment_state') == "NEW") ? true : false);
    }

    function isGoogleAllowAcrhive()
    {
        return ((in_array($this->getComplex('order.google_details.fulfillment_state'), array('DELIVERED', "WILL_NOT_DELIVER")) || in_array($this->getComplex('order.google_details.financial_state'), array('PAYMENT_DECLINED', "CANCELLED", "CANCELLED_BY_GOOGLE"))) ? true : false);
    }

    function isGoogleOrderCanceled()
    {
        return (($this->getComplex('order.google_status') == "C") ? true : false);
    }


    // Success handling
    function setGoogleSuccess($var_code)
    {
        if (!in_array('success', $this->params)) {
            $this->params[] = "success";
        }

        $this->valid = true;
        $this->success = $var_code;
    }

    // Error handling
    function setGoogleError($var_code, $result)
    {
        $this->valid = false;
        $this->error = "message_send_failed";
        $this->errorMessage = $result;

        if (!in_array('error', $this->params)) {
            $this->params[] = "error";
        }

        if (!in_array('errorMessage', $this->params)) {
            $this->params[] = "errorMessage";
        }
    }


    function action_gcheckout_charge_order()
    {
        $result = GoogleCheckout_OrderCharge($this->payment_method, $this->googleId, $this->get('charge_amount'));
        if ($result === true) {
            $this->setGoogleSuccess('order_charge');
        } else {
            $this->setGoogleError('order_charge_failed', $result);
        }
    }

    function action_gcheckout_refund_order()
    {
        $result = GoogleCheckout_OrderRefund($this->payment_method, $this->googleId, $this->get('refund_amount'), $this->get('refund_reason'), $this->get('refund_comment'));
        if ($result === true) {
            $this->setGoogleSuccess('order_refund');
        } else {
            $this->setGoogleError('order_refund_failed', $result);
        }
    }

    function action_gcheckout_cancel_order()
    {
        $result = GoogleCheckout_OrderCancel($this->payment_method, $this->googleId, $this->get('cancel_reason'), $this->get('cancel_comment'));
        if ($result === true) {
            $this->setGoogleSuccess('order_cancel');
        } else {
            $this->setGoogleError('order_cancel_failed', $result);
        }
    }

    function action_gcheckout_process_order()
    {
        $result = GoogleCheckout_OrderProcess($this->payment_method, $this->googleId);
        if ($result === true) {
            $this->setGoogleSuccess('order_processed');
        } else {
            $this->setGoogleError('order_process_failed', $result);
        }
    }

    function action_gcheckout_tracking_data()
    {
        $tracking = trim(addslashes($this->get('tracking_no')));
        $carrier = $this->get('google_carrier');

        $result = GoogleCheckout_OrderAddTrackingData($this->payment_method, $this->googleId, $tracking, $carrier);
        if ($result === true) {
            $order = $this->get('order');

            $message_code  = ($order->get('tracking') ? "order_update_tracking" : "order_add_tracking");
            $order->set('tracking', $tracking);
            $order->set('google_carrier', $carrier);
            $order->update();

            $this->setGoogleSuccess("$message_code");
        } else {
            $this->setGoogleError('order_tracking_failed', $result);
        }
    }

    function action_gcheckout_deliver_order()
    {
        $result = GoogleCheckout_OrderDeliver($this->payment_method, $this->googleId, (($this->get('deliver_email')) ? true : false));

        if ($result === true) {
            $this->setGoogleSuccess('order_deliver');
        } else {
            $this->setGoogleError('order_deliver_failed', $result);
        }
    }

    function action_gcheckout_send_messge()
    {
        $message = addslashes(trim($this->get('message')));
        $result = GoogleCheckout_OrderSendMessage($this->payment_method, $this->googleId, $message, (($this->get('message_email') ? true : false)));

        if ($result === true) {
            $this->setGoogleSuccess('message_sent');
        } else {
            $this->setGoogleError('message_send_failed', $result);
        }
    }

    function action_gcheckout_archive_order()
    {
        $result = GoogleCheckout_OrderArchive($this->payment_method, $this->googleId);

        if ($result === true) {
            $order = $this->get('order');
            $order->setComplex('google_details.google_archived', 1);
            $order->update();

            $this->setGoogleSuccess('order_archived');
        } else {
            $this->setGoogleError('order_archive_failed', $result);
        }
    }

    function action_gcheckout_unarchive_order()
    {
        $result = GoogleCheckout_OrderUnArchive($this->payment_method, $this->googleId);

        if ($result === true) {
            $order = $this->get('order');
            $order->setComplex('google_details.google_archived', 0);
            $order->update();

            $this->setGoogleSuccess('order_unarchived');
        } else {
            $this->setGoogleError('order_unarchive_failed', $result);
        }
    }



    function getGoogleRefundReasons()
    {
        return array(
            "Not as described/expected",
            "Wrong size",
            "Found better prices elsewhere",
            "Product is missing parts",
            "Product is defective/damaged",
            "Took too long to deliver",
            "Item out of stock",
            "Customer request to cancel",
            "Item discontinued",
        );
    }

    function getGoogleCancelReasons()
    {
        return $this->get('googleRefundReasons');
    }

    function getGoogleCarriersList()
    {
        return array('DHL', "FedEx", "UPS", "USPS", "Other");
    }
}
