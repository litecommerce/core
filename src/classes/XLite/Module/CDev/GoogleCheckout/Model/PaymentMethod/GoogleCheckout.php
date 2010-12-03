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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\GoogleCheckout\Model\PaymentMethod;

define('CALLBACK_ERROR_BAD_MERCHANT_NOTE', 1);
define('CALLBACK_ERROR_BAD_ORDER_ID', 2);
define('CALLBACK_ERROR_NON_EXISTENT_ORDER_ID', 3);
define('CALLBACK_ERROR_ILLEGAL_ORDER_ID', 4);

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class GoogleCheckout extends \XLite\Model\PaymentMethod 
{
    public $configurationTemplate = "modules/CDev/GoogleCheckout/config.tpl";
    public $processorName = "GoogleCheckout";

    public $avs_info = array(
        "Y" => "Full AVS match (address and postal code)",
        "P" => "Partial AVS match (postal code only)",
        "A" => "Partial AVS match (address only)",
        "N" => "No AVS match",
        "U" => "AVS not supported by issuer"
    );

    public $cvn_info = array(
        "M" => "CVN match",
        "N" => "No CVN match",
        "U" => "CVN not available",
        "E" => "CVN error"
    );

    function get($name)
    {
        if ($name == "enabled" && !$this->xlite->is('adminZone')) {
            return false;
        }

        if ($name == "parent_enabled") {
            return parent::get('enabled');
        }

        return parent::get($name);
    }

    function getXMLDataByPath(&$xmlData, $path)
    {
        if (!is_array($xmlData)) {
            return null;
        }
        $path = strval($path);
        if (strlen($path) == 0) {
            return null;
        }

        $path = explode('/', $path);
        $elem = $xmlData;
        foreach ($path as $pathElm) {
    		if (isset($elem[$pathElm])) {
    			$elem = $elem[$pathElm];
    		} else {
                return null;
    		}
        }

        return $elem;
    }

    function _errorHandleCallback($error, $fatal = true)
    {
        switch ($error) {
            case CALLBACK_ERROR_BAD_MERCHANT_NOTE:
    			$this->xlite->logger->log("ERROR: Received data (merchant-note) could not be identified correctly.");
    		break;
            case CALLBACK_ERROR_BAD_ORDER_ID:
    			$this->xlite->logger->log("ERROR: Received data (order ID) could not be identified correctly.");
    		break;
    		case CALLBACK_ERROR_NON_EXISTENT_ORDER_ID:
    			$this->xlite->logger->log("ERROR: Received data - non-existent order ID.");
    		break;
    		case CALLBACK_ERROR_ILLEGAL_ORDER_ID:
    			$this->xlite->logger->log("ERROR: Received data - illegal order ID.");
    		break;
        }

        if ($fatal) {
            exit;
        }
    }

    function getOrderFromCallback(&$xmlData, $method, $fatal=true)
    {
        $paymentParams = $this->get('params');

        $merchantNote = $this->getXMLDataByPath($xmlData, "$method/SHOPPING-CART/MERCHANT-PRIVATE-DATA/MERCHANT-NOTE");
        if (!isset($merchantNote)) {
            $this->_errorHandleCallback(CALLBACK_ERROR_BAD_MERCHANT_NOTE, $fatal);
            return null;
        }

        $orderID = explode(" (", $merchantNote);
        if (count($orderID) != 2) {
            $this->_errorHandleCallback(CALLBACK_ERROR_BAD_ORDER_ID, $fatal);
            return null;
        }
        if (strlen($paymentParams['order_prefix']) > 0) {
            if (substr($orderID[0], 0, strlen($paymentParams['order_prefix'])) != $paymentParams['order_prefix']) {
                $this->_errorHandleCallback(CALLBACK_ERROR_BAD_ORDER_ID, $fatal);
                return null;
            }

            $orderID[0] = intval(substr($orderID[0], strlen($paymentParams['order_prefix'])));
            if ($orderID[0] <= 0) {
                $this->_errorHandleCallback(CALLBACK_ERROR_BAD_ORDER_ID, $fatal);
                return null;
            }
        }

        $order = new \XLite\Model\Order($orderID[0]);
        if (!$order->isExists()) {
            $this->_errorHandleCallback(CALLBACK_ERROR_NON_EXISTENT_ORDER_ID, $fatal);
            return null;
        }

        $orderMerchantNote = $this->getOrderMerchantNote($order, $paymentParams);
        if ($orderMerchantNote != $merchantNote) {
            $this->_errorHandleCallback(CALLBACK_ERROR_ILLEGAL_ORDER_ID, $fatal);
            return null;
        }

        return $order;
    }

    function handleCallback(&$xmlData)
    {
        $method = key($xmlData);

        if (trim($method) == "") {
            $this->xlite->logger->log('Callback method not set. Possible incorrect XML data.');
            exit;
        }

        $xmlResponse = "";
        switch ($method) {
            case "MERCHANT-CALCULATION-CALLBACK":
                // 1. The customer creates a Google account to complete an order using Google Checkout. 
                // 2. The customer signs in to Google Checkout to complete an order. 
                // 3. The customer enters a new shipping address on the Place Order page. 
                // 4. The customer enters a coupon or gift certificate code. 

                $addresses = $this->getXMLDataByPath($xmlData, "MERCHANT-CALCULATION-CALLBACK/CALCULATE/ADDRESSES/ANONYMOUS-ADDRESS");
                $shippings = $this->getXMLDataByPath($xmlData, "MERCHANT-CALCULATION-CALLBACK/CALCULATE/SHIPPING/METHOD");
                $discounts = $this->getXMLDataByPath($xmlData, "MERCHANT-CALCULATION-CALLBACK/CALCULATE/MERCHANT-CODE-STRINGS/MERCHANT-CODE-STRING");

                if (!is_array($addresses) || count($addresses) <= 0) {
                    $this->xlite->logger->log("MERCHANT-CALCULATION-CALLBACK: Addresses missed. ");
                    exit;
                }

                if (!is_array($shippings) || count($shippings) <= 0) {
                    $this->xlite->logger->log("MERCHANT-CALCULATION-CALLBACK: Shipping methods missed. ");
                    exit;
                }

                $order = $this->getOrderFromCallback($xmlData, "MERCHANT-CALCULATION-CALLBACK");
                $xmlResponse = $order->getGoogleCheckoutXML('Calculation', $addresses, $shippings, $discounts);
            break;

            case "NEW-ORDER-NOTIFICATION":
                require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';
                GoogleCheckout_new_order_notification($this, $xmlData);
            break;

            case "RISK-INFORMATION-NOTIFICATION":
                require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';
                GoogleCheckout_risk_information_notification($this, $xmlData);
            break;

            case "ORDER-STATE-CHANGE-NOTIFICATION":
                require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';
                GoogleCheckout_order_state_change_notification($this, $xmlData);
            break;

            case "CHARGE-AMOUNT-NOTIFICATION":
                require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';
                GoogleCheckout_order_charge_amount_notification($this, $xmlData);
            break;

            case "REFUND-AMOUNT-NOTIFICATION":
                require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';
                GoogleCheckout_order_refund_amount_notification($this, $xmlData);
            break;

            default:
                $this->xlite->logger->log("Unknown notification method: $method");
            break;
        }


        // Response "notification-acknowledgment"
        if (preg_match("/^[A-Z-]+-NOTIFICATION$/", $method)) {
                $xmlResponse = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<notification-acknowledgment xmlns="http://checkout.google.com/schema/2"/>
EOT;
        }

        $this->xlite->logger->log("ECHO: ".var_export($xmlResponse, true));

        echo "$xmlResponse";

        // logging execution time stamp
        global $gcheckout_timestamp;
        list($usec, $sec) = explode(' ',microtime());
        $dt = ((float)$usec + (float)$sec) - $gcheckout_timestamp;
        $this->xlite->logger->log("Callback execution time: ".sprintf("%.03f", $dt)." sec");

        if ($method == "NEW-ORDER-NOTIFICATION") {
            $google_id = $this->getXMLDataByPath($xmlData, "NEW-ORDER-NOTIFICATION/GOOGLE-ORDER-NUMBER");
            $order = $this->getOrderFromCallback($xmlData, "NEW-ORDER-NOTIFICATION", false);
            if ($order != null) {
                $order_num = $this->getComplex('params.order_prefix').$order->get('order_id');
                GoogleCheckout_OrderMerchantOrderNumber($this, $google_id, $order_num);
            }
        }
    }

    function handleRequest(\XLite\Model\Cart $order)
    {
        $response = $this->sendGoogleCheckoutRequest($order);

        $url = $response["CHECKOUT-REDIRECT"]["REDIRECT-URL"];
        if ($url) {
            // when PHP5 is used with libxml 2.7.1, HTML entities are stripped from any XML content
            // this is a workaround for https://qa.mandriva.com/show_bug.cgi?id=43486
            if (strpos($url, "shoppingcartshoppingcart") !== false) {
            	$url = str_replace('shoppingcartshoppingcart', "shoppingcart&shoppingcart", $url);
            }

?>
<HTML>
<BODY onload="javascript: document.location='<?php echo $url; ?>'">
<font style="FONT-SIZE: 12px; FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif">
<b>Redirecting to Google checkout...</b><br>
If you are not redirected automatically, <a href="<?php echo $url; ?>">click on this link to go to Google checkout.</a><br>
</font>
</BODY>
</HTML>
<?php

            return self::PAYMENT_SILENT;
        } else {
            $error = $response['ERROR']["ERROR-MESSAGE"];
            $order->setComplex('details.error', (($error) ? $error : "Unknown"));
            $order->setComplex('detailLabels.error', "Error");
            $order->update();

            return self::PAYMENT_FAILURE;
        }

    }

    function sendGoogleCheckoutRequest($order)
    {
        require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';
        return GoogleCheckout_sendGoogleCheckoutRequest($this, $order);
    }

    function handleConfigRequest()
    {
        $params = $_POST['params'];
        $subparams = $this->get('params');

        $statuses = array('chargeable', "charged", "failed");
        foreach ($statuses as $name) {
            $field = "status_" . $name;
            $result = $params[$field];
            if ($this->xlite->AOMEnabled) {
                $status = new \XLite\Module\CDev\AOM\Model\OrderStatus();
                if ($status->find("status='".$params[$field]."'")) {
                    if ($status->get('parent')) {
                        $params[$field] = $status->get('parent');
                        $result = $status->get('status');
                    }
                }
            }
            $params["sub".$field] = $result;
        }

        $pm = \XLite\Model\PaymentMethod::factory('google_checkout');
        $pm->set('params', $params);
        $pm->update();

        // dublicate "default_shipping_cost" in config
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
            array(
                'category' => 'GoogleCheckout',
                'name'     => 'default_shipping_cost',
                'value'    => $params['default_shipping_cost']
            )
        );
    }

    function isCheckAvs($value)
    {
        if (!isset($this->params['check_avs']) || (isset($this->params['check_avs']) && !is_array($this->params['check_avs']))) {
            return false;
        }

        return in_array($value, $this->params['check_avs']);
    }

    function isCheckCvn($value)
    {
        if (!isset($this->params['check_cvn']) || (isset($this->params['check_cvn']) && !is_array($this->params['check_cvn']))) {
            return false;
        }

        return in_array($value, $this->params['check_cvn']);
    }

    function getDefaultShippingCost()
    {
        $origValue = $this->params['default_shipping_cost'];
        $value = doubleval($origValue);
        if ($value < 0) {
            $value = 0;
        }

        if ($origValue != $value) {
            $this->params['default_shipping_cost'] = $value;
            $this->set('params', $this->params);
            $this->update();
        }

        return $value;
    }

    function getChargeableStatus()
    {
        return ($this->params['substatus_chargeable'] && $this->xlite->AOMEnabled) ? $this->params['substatus_chargeable'] : $this->params['status_chargeable'];
    }

    function getChargedStatus()
    {
        return ($this->params['substatus_charged'] && $this->xlite->AOMEnabled) ? $this->params['substatus_charged'] : $this->params['status_charged'];
    }

    function getFailedStatus()
    {
        return ($this->params['substatus_failed'] && $this->xlite->AOMEnabled) ? $this->params['substatus_failed'] : $this->params['status_failed'];
    }

    function isOnlineShippingsActive()
    {
        $so = new \XLite\Model\Shipping();
        foreach ($so->get('modules') as $module) {
            $class_name = $module->get('class');
            if ($class_name == "offline") {
                continue;
            }

            $shipping = new \XLite\Model\Shipping();
            if ($shipping->count("enabled=1 AND class='$class_name'") > 0) {
                return true;
            }
        }

        return false;
    }

    function google_encode($str) {
        return str_replace(array('&', "<", ">"), array("&#x26;", "&#x3c;", "&#x3e;"), $str);
    }

    function getCallbackURL()
    {
        $secureTestmode = "s";
        if ($this->getComplex('params.testmode') == "Y") {
            if ((bool) $this->getComplex('params.secure_testmode')) {
                $secureTestmode = "";
            }
        }

        $subpath = "";
        $xlite = \XLite::getInstance();

        if ($xlite->getOptions(array('primary_installation', 'path'))) {
            // deal with ASPE shop
            $subpath = "/admin.php?target=payment_method&action=callback&payment_method=google_checkout";
        } else {
            $subpath = "/classes/modules/GoogleCheckout/callback.php";
        }

        return "http" . $secureTestmode . "://" . $xlite->getOptions(array('host_details', 'https_host')) . $xlite->getOptions(array('host_details', 'web_dir_wo_slash')) . $subpath;
    }

    function getOrderMerchantNote($order, $paymentParams)
    {
        // switch to customer area for correct order items fingerprint calc.
        $is_admin = $this->xlite->is('adminZone');
        $this->xlite->set('adminZone', false);

        $fingerprint = "";
        if (!method_exists($order, "getItemsFingerprint")) {
            $fingerprint = $order->google_getItemsFingerprint();
        } else {
            $fingerprint = $order->getItemsFingerprint();
        }

        $id = $order->get('order_id');
        $idText = $paymentParams['order_prefix'] . $id;
        $idKey = array();
        $idKey[] = "OrderID=" . $id;
        $idKey[] = "OrderPrefix=" . $paymentParams['order_prefix'];
        $idKey[] = "OrderDate=" . $order->get('date');
        $idKey[] = "OrderItems=" . $fingerprint;
        $idKey = strrev(md5(implode("|", $idKey)));
        $idText .= " ($idKey)";

        $this->xlite->set('adminZone', $is_admin);

        return $idText;
    }

    function getOrderCheckoutRequest($order, $paymentParams)
    {
        $shop_url = htmlentities($this->xlite->getShopUrl('cart.php'));
        $merchantNote = $this->getOrderMerchantNote($order, $paymentParams);
        
        $order_total = $order->get('total');

        $items = $order->getGoogleCheckoutXML('items');
        $shippings = $order->getGoogleCheckoutXML('shippings');
        $tax = $order->getGoogleCheckoutXML('tax');

        $callbackURL = htmlentities($this->getCallbackURL());

        $discount_coupon = (($order->is('googleDiscountCouponsAvailable')) ? "true" : "false");
        $gift_certificate = (($order->is('googleGiftCertificatesAvailable')) ? "true" : "false");

        return <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<checkout-shopping-cart xmlns="http://checkout.google.com/schema/2">
    <shopping-cart>
        <merchant-private-data>
            <merchant-note>$merchantNote</merchant-note>
        </merchant-private-data>
        <items>
$items
        </items>
    </shopping-cart>
    <checkout-flow-support>
        <merchant-checkout-flow-support>
            <platform-id>429557754556845</platform-id>
            <request-buyer-phone-number>true</request-buyer-phone-number>
$shippings
            <merchant-calculations>
                <merchant-calculations-url>$callbackURL</merchant-calculations-url>
                <accept-merchant-coupons>$discount_coupon</accept-merchant-coupons>
                <accept-gift-certificates>$gift_certificate</accept-gift-certificates>
            </merchant-calculations>
            <edit-cart-url>$shop_url?target=cart</edit-cart-url>
            <continue-shopping-url>$shop_url</continue-shopping-url>
$tax
        </merchant-checkout-flow-support>
    </checkout-flow-support>
</checkout-shopping-cart>		
EOT;
    }

}
