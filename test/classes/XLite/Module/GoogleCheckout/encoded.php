<?php

/*
*
* @version $Id$
*
*/

/*
* Hidden methods
*/

function GoogleCheckout_text_decryption($text)
{
    $len = strlen($text);
    $result = "";
    for ($i=0; $i<$len; $i+=2) {
    	$result .= chr(hexdec(substr($text, $i, 2)));
    }
    return strrev(base64_decode($result));
}

function GoogleCheckout_text_encryption($text)
{
    return bin2hex(base64_encode(strrev($text)));
}

function GoogleCheckout_encode_utf8_string($str)
{
    $value = strval($str);
    $value = str_replace("\n", " ", $value);
    $value = str_replace("\r", " ", $value);
    $value = str_replace("\t", " ", $value);
    $valueLength = strlen($value);
    $newValue = "";
    for ($i=0; $i<$valueLength; $i++) {
        $symbol = $value{$i};
        $symbolCode = ord($symbol);
        if (($symbolCode>=0 && $symbolCode<=31) || $symbolCode>=127) {
            $newValue .= "&#" . sprintf("%02d", $symbolCode) . ";";
        } else {
            $newValue .= $symbol;
        }
    }

    $str = htmlspecialchars($newValue);
    return preg_replace("/[^\w.,;:\"\'#$%@!-+*\/\(\)&\^\[\]]/", " ", $str);
}

function GoogleCheckout_parseResponse($_this, $response)
{
    preg_match("/<.*[^>].*>/msx", $response, $res);
    $response = $res[0];

    $_this->error = "";
    $_this->xmlError = false;
    $xml = GoogleCheckout_getXML_Object();
    $tree = $xml->parse($response);
    if (!$tree) {
        $_this->error = $xml->error;
        $_this->xmlError = true;
        $_this->response = $xml->xml;
        return array();
    }

    return $tree;
}


function GoogleCheckout_sendRequest($_this, &$payment, &$data)
{
    $auth = base64_encode($payment['merchant_id'].":".$payment['merchant_key']);
    $h = array(
        "Authorization" => "Basic ".$auth,
        "Accept" => "application/xml"
    );

    $https = GoogleCheckout_getHTTPS_Object();
    $https->data        = $data;
    $https->method      = "POST";
    $https->conttype    = "application/xml";
    $https->urlencoded  = true;
    $https->headers		= $h;
    // Use testing sanbox or live environment
    if ($payment['testmode'] == "Y") {
    	$gcheckout_env = "sandbox";
    	$gcheckout_sbx = "checkout/";
    }
    else {
    	$gcheckout_env = "checkout";
    	$gcheckout_sbx = "";
    }
    $https->url = "https://$gcheckout_env.google.com:443/{$gcheckout_sbx}cws/v2/Merchant/".$payment['merchant_id']."/request";

    $_this->error = null;
    $_this->xlite->logger->log("Sending request to: " . $https->url);
    if ($https->request() == XLite_Model_HTTPS::HTTPS_ERROR) {
        $_this->xlite->logger->log("HTTPS_ERROR: " . $https->error);
        $_this->error = $https->error;
        return array();
    }

    $_this->xlite->logger->log("Response from GoogleCheckout:\n" . $https->response);
    return GoogleCheckout_parseResponse($_this, $https->response);
}

function GoogleCheckout_getHTTPS_Object()
{
    return new XLite_Model_HTTPS();
}

function GoogleCheckout_getXML_Object()
{
    $obj = new XLite_Model_XML();
    if (method_exists($obj, "_compileTreeNode")) {
        return $obj;
    }

    $obj = null;

    return new XLite_Module_GoogleCheckout_Model_XML();
}

function GoogleCheckout_sendGoogleCheckoutRequest($_this, $order)
{
    $params = $_this->get('params');

    $session_id = base64_encode(strrev($_this->session->getID()));
    $order->setComplex('google_details.gid', $session_id);
    $order->update();

    $request = $_this->getOrderCheckoutRequest($order, $params);
    $_this->xlite->logger->log("Request to GoogleCheckout:\n" . $request);

    return GoogleCheckout_sendRequest($_this, $params, $request);
}


////////////////////////////////////////////////////////////////////////////////////
///////////////////// Google request\callback\response methods /////////////////////
////////////////////////////////////////////////////////////////////////////////////

function GoogleCheckout_getGoogleCheckoutXML_Calculation($_this, $address, $shipping, $discounts)
{
    // switch to customer area
    $_old_admin_zone = $_this->xlite->get('adminZone');
    $_this->xlite->set('adminZone', false);

    $cart = XLite_Model_Cart::getInstance();
    $cart = new XLite_Model_Cart($_this->get('order_id')); // do not insert &

    $pmGC = XLite_Model_PaymentMethod::factory('google_checkout');
    $params = $pmGC->get('params');
    $currency = $params['currency'];
    $xmlDiscounts = array();

// debug code
//$discounts = array();
//$discounts[] = array("CODE" => "22");

    // Process coupon discount
    if ($_this->xlite->get('PromotionEnabled') && is_array($discounts) && count($discounts) > 0 && is_null($_this->get('DC'))) {
        if (!is_null($_this->get('DC'))) {
//			$_this->DC->delete();
//			$_this->DC = null;
        }

        $coupon_applied = false;
        foreach ($discounts as $id=>$discount) {
            $coupon_code = addslashes(trim($discount['CODE']));
            $discount_value = 0;

            // validate discount coupon
            if ($cart->validateDiscountCoupon($coupon_code)) {
                continue;
            }

            // get coupon object and validate
            $coupon = new XLite_Module_Promotion_Model_DiscountCoupon();
            if (!$coupon->find("coupon='".addslashes($coupon_code)."' AND order_id='0'"))
                continue;

            $discounts[$id]['type'] = "coupon";

            if (!$coupon->checkCondition($_this)) {
                continue;
            }

            if ($coupon->get('applyTo') != "total" && $coupon->get('type') != "freeship") {
                continue;
            }

            if ($coupon_applied) {
                $discounts[$id]['redundancy'] = true;
                continue;
            }

            if (in_array($coupon->get('applyTo'), array('product', "category"))) {
                $total_items_amount = 0;
                foreach ($_this->get('items') as $item) {
                    $total_items_amount += $item->get('total');
                }
            }

            $_this->set('discountCoupon', $coupon->get('coupon_id'));
            $_this->DC = $coupon;

            // calc discount value for valid coupon
            $_this->calcDiscount();

            // calc discount value
            $discount_value = 0;
            if (in_array($coupon->get('applyTo'), array('product', "category"))) {
                $discounted_items_amount = 0;
                foreach ($_this->get('items') as $item) {
                    $discounted_items_amount += $item->get('total');
                }
                $discount_value = max(0, ($total_items_amount - $discounted_items_amount));
            } else {
                $discount_value = $_this->get('discount');
            }


            $msg = GoogleCheckout_getCouponApplyDescription($coupon);

            // valid coupon
            $xmlDiscounts[] = <<<EOT
                <coupon-result>
                    <valid>true</valid>
                    <code>$coupon_code</code>
                    <calculated-amount currency="$currency">$discount_value</calculated-amount>
                    <message>$msg</message>
                </coupon-result>
EOT;

            unset($discounts[$id]);
            $coupon_applied = true;
        }
    }	// Process coupon discount


    // process GiftCertificates
    if ($_this->xlite->get('GiftCertificatesEnabled') && is_array($discounts) && count($discounts) > 0) {
        $cert_applied = false;
        foreach ($discounts as $id=>$discount) {
            $cert_code = addslashes(trim($discount['CODE']));

            $cert = new XLite_Module_GiftCertificates_Model_GiftCertificate();
            if (!$cert->find("gcid='".addslashes($cert_code)."'"))
                continue;

            $discounts[$id]['type'] = "cert";

            if ($cert->validate() != XLite_Module_GiftCertificates_Model_GiftCertificate::GC_OK || $cert->get('debit') <= 0)
                continue;

            if ($cert_applied) {
                $discounts[$id]['redundancy'] = true;
                continue;
            }

            $cert_value = $cert->get('debit');

            $xmlDiscounts[] = <<<EOT
                <gift-certificate-result>
                    <valid>true</valid>
                    <code>$cert_code</code>
                    <calculated-amount currency="$currency">$cert_value</calculated-amount>
                    <message>Gift certificate #$cert_code has been applied.</message>
                </gift-certificate-result>
EOT;

            unset($discounts[$id]);
            $cert_applied = true;
        }
    }	// process GiftCertificates


    // fill unused discounts
    if (is_array($discounts) && count($discounts) > 0) {
        foreach ($discounts as $id=>$discount) {
            $coupon_code = trim($discount['CODE']);

            $message = "";
            if ($discount['redundancy']) {
                $discount_type = (($discount['type'] == "coupon") ? "discount coupon" : "gift certificate");
                $message = "Only one $discount_type can be applied during Google Checkout. Although the discount coupon is valid, it cannot be applied.";
            } else {
                $message = "The discount coupon/gift certificate has already been used by someone else or cannot be applied due to the condition it requires is not met. It has been removed from your cart.";
            }

            // invalid coupon
            $xmlDiscounts[] = <<<EOT
                <coupon-result>
                    <valid>false</valid>
                    <code>$coupon_code</code>
                    <calculated-amount currency="$currency">0</calculated-amount>
                    <message>$message</message>
                </coupon-result>
EOT;
        }
    }


    // add wrap tags
    if (is_array($xmlDiscounts) && count($xmlDiscounts) > 0) {
        $xml_discounts = "\t\t\t<merchant-code-results>\n".implode("\n", $xmlDiscounts)."\n\t\t\t</merchant-code-results>";
    }

    $xmlResults = "";

    // Prepare shipping rates
    foreach ($address as $addr_id=>$addr) {
        $allow_shipping = false;
        $valid_methods = array();

        // Create fake customer profile
        $profile = new XLite_Model_Profile();
        $profile->set('shipping_city', $addr['CITY']);
        $profile->set('shipping_zipcode', $addr["POSTAL-CODE"]);
        $profile->set('shipping_country', $addr["COUNTRY-CODE"]);

        $_this->config->setComplex('General.default_zicode', $addr["POSTAL-CODE"]);
        $_this->config->setComplex('General.default_country', $addr["COUNTRY-CODE"]);

        // state
        $state_id = 0;
        $state = new XLite_Model_State();
        if ($state->find("code='".addslashes($addr['REGION'])."'")) {
            $state_id = $state->get('state_id');
        }

        $profile->set('shipping_state', $state_id);
        $_this->GoogleCheckout_profile = $profile;

        $allow_shipping = true;

        $shipping_rates = array();
        if ($allow_shipping) {
            $shipping_rates = $_this->calcShippingRates();
        }

        // Assign shipping method and get calculation
        $classes = GoogleCheckout_getShippingClassesSQL_STRING();
        foreach ($shipping as $shipping_params) {
            $shipping_method = $shipping_params['NAME'];

            $shipable = "false";
            $shipping_cost = 0;
            $tax_cost = 0;

            // Get Shipping method by name from GoogleCheckout
            if ($allow_shipping && is_array($shipping_rates) && count($shipping_rates) > 0) {
                $shippingMethod = new XLite_Model_Shipping();

                if ($shippingMethod->find("name='".addslashes($shipping_method)."' AND enabled='1' AND class IN($classes)") && array_key_exists($shippingMethod->get('shipping_id'), $shipping_rates)) {
                    // If order shipable - calculate shipping and tax cost
                    $_this->setShippingMethod($shippingMethod);
                    $_this->calcTotal();

                    $shipping_cost = (($_this->is('shipped')) ? $_this->get('shippingCost') : 0);
                    $shipping_cost = sprintf("%.02f", $shipping_cost);

                    $tax_cost = sprintf("%.02f", $_this->get('tax'));
                    $shipable = "true";
                }
            }

            // restore value
            $_this->xlite->set('adminZone', $_old_admin_zone);

            $shipping_method = htmlentities($shipping_method);
            $xmlResults .= <<<EOT
        <result shipping-name="$shipping_method" address-id="$addr_id">
            <shipping-rate currency="$currency">$shipping_cost</shipping-rate>
            <shippable>$shipable</shippable>
            <total-tax currency="$currency">$tax_cost</total-tax>
$xml_discounts
        </result>

EOT;
        }

        $_this->GoogleCheckout_profile = null;
    }	// Prepare shipping rates

    // Unset applied coupons
    if ($_this->xlite->get('PromotionEnabled')) {
        $_this->set('discountCoupon', null);
        $_this->DC = null;
    }


    return <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<merchant-calculation-results xmlns="http://checkout.google.com/schema/2">
    <results>
$xmlResults
    </results>
</merchant-calculation-results>
EOT;
}

function GoogleCheckout_getCouponApplyDescription($coupon)
{
    $msg = "";

    $coupon_code = $coupon->get('coupon');
    if ($coupon->get('type') == "freeship") {
        $msg = "Free shipping coupon #$coupon_code has been applied.";
    } else {
        switch ($coupon->get('applyTo')) {
            case "product":
                $msg = "Coupon #$coupon_code has been applied to product '".$coupon->getComplex('product.name')."'.";
            break;

            case "category":
                $msg = "Coupon #$coupon_code has been applied to category '".$coupon->getComplex('category.name')."'.";
            break;

            default:
                $msg = "Coupon #$coupon_code has been applied.";
        }
    }

    return $msg;
}

function GoogleCheckout_getShippingClassesSQL_STRING()
{
    $so = new XLite_Model_Shipping();
    $modules = $so->get('modules');

    $keys = array();
    if (is_array($modules) && count($modules) > 0) {
        $keys = array_keys($modules);

        foreach ($keys as $k=>$v) {
            $keys[$k] = addslashes($v);
        }
    }

    return "'".implode("', '", $keys)."'";
}

//*********************************************************************************
//************************** GoogleCheckout Notification Section ******************
//*********************************************************************************

function GoogleCheckout_new_order_notification($_this, $xmlData)
{
    if ($_this->getXMLDataByPath($xmlData, "NEW-ORDER-NOTIFICATION/FINANCIAL-ORDER-STATE" != "REVIEWING")) {
        return false;
    }

    // Get order
    $order = $_this->getOrderFromCallback($xmlData, "NEW-ORDER-NOTIFICATION", false);
    if (is_null($order)) {
        $_this->xlite->logger->log("NEW-ORDER-NOTIFICATION: Order not found. ");
        return false;
        // create new order if not exists
        /*... Should we implement it in future? ...*/
    }

    // trim XML
    $xmlData = $_this->getXMLDataByPath($xmlData, "NEW-ORDER-NOTIFICATION");

    // Apply Discount coupon
    $coupon = $_this->getXMLDataByPath($xmlData, "ORDER-ADJUSTMENT/MERCHANT-CODES/COUPON-ADJUSTMENT");
    if ($_this->xlite->get('PromotionEnabled') && $coupon && is_null($order->getDC())) {
        $dc = new XLite_Module_Promotion_Model_DiscountCoupon();
        $dc->find("coupon='".addslashes($coupon['CODE'])."'");

        if ($order->google_checkout_setDC($dc)) {
            $order->set('discount', $coupon["APPLIED-AMOUNT"]);
        } else {
            $_this->xlite->logger->log("NEW-ORDER-NOTIFICATION: Discount coupon #".$coupon['CODE']." not applied.");
        }
    }

    // Apply Gift certificate
    $gift_cert = $_this->getXMLDataByPath($xmlData, "ORDER-ADJUSTMENT/MERCHANT-CODES/GIFT-CERTIFICATE-ADJUSTMENT");
    if ($_this->xlite->get('GiftCertificatesEnabled') && $gift_cert) {
        $cert = new XLite_Module_GiftCertificates_Model_GiftCertificate();
        $cert->find("gcid='".addslashes($gift_cert['CODE'])."'");
        $result = $order->set('GC', $cert);

        if ($result == XLite_Module_GiftCertificates_Model_GiftCertificate::GC_OK) {
            $order->set('payedByGC', $gift_cert["APPLIED-AMOUNT"]);
        } else {
            $_this->xlite->logger->log("NEW-ORDER-NOTIFICATION: Gift certificate #".$gift_cert['CODE']." not applied.");
        }
    }

    $defult_shipping_cost = false;

    // Set Shipping method
    $shipping_info = $_this->getXMLDataByPath($xmlData, "ORDER-ADJUSTMENT/SHIPPING/MERCHANT-CALCULATED-SHIPPING-ADJUSTMENT");
    $sm = new XLite_Model_Shipping();
    $classes = GoogleCheckout_getShippingClassesSQL_STRING();

$_this->xlite->logger->log("name='".addslashes($shipping_info["SHIPPING-NAME"])."' AND enabled=1 AND class IN($classes)");

    if ($sm->find("name='".addslashes($shipping_info["SHIPPING-NAME"])."' AND enabled=1 AND class IN($classes)")) {
        $order->setShippingMethod($sm);
        $order->set('shipping_cost', $shipping_info["SHIPPING-COST"]);
    } else {
        $_this->xlite->logger->log("NEW-ORDER-NOTIFICATION: Shipping method '".$shipping_info["SHIPPING-NAME"]."' not found.");
    }

    // Set Payment method
    $pm = XLite_Model_PaymentMethod::factory('google_checkout');
    $order->setPaymentMethod($pm);


    // set check GoogleCheckout calculation result
    $order->set('detailLabels.gcCalculation', "Google calculation");
    if (strcasecmp($_this->getXMLDataByPath($xmlData, "ORDER-ADJUSTMENT/MERCHANT-CALCULATION-SUCCESSFUL"), "true") == 0) {
        $order->setComplex('details.gcCalculation', "VALID");
        $order->setComplex('google_details.calc', 1);
    } else {
        $order->set('details.gcCalculation', "NOT VALID. Google merchant calculation is false.");
        $_this->xlite->logger->log("NEW-ORDER-NOTIFICATION: Google calculation not valid.");
        $order->setComplex('google_details.calc', 0);
    }

    $order->set('detailLabels.google_id', "Google Id");
    $order->setComplex('details.google_id', $_this->getXMLDataByPath($xmlData, "GOOGLE-ORDER-NUMBER"));

    // Set order totals
    $order->set('total', $_this->getXMLDataByPath($xmlData, "ORDER-TOTAL"));
    $order->set('tax', $_this->getXMLDataByPath($xmlData, "ORDER-ADJUSTMENT/TOTAL-TAX"));
    $order->set('alltaxes', array("Tax" => $_this->getXMLDataByPath($xmlData, "ORDER-ADJUSTMENT/TOTAL-TAX")));

    $order->setComplex('google_details.fulfillment_state', $xmlData["FULFILLMENT-ORDER-STATE"]);
    $order->setComplex('google_details.financial_state', $xmlData["FINANCIAL-ORDER-STATE"]);
    $order->setComplex('google_details.buyer_id', $xmlData["BUYER-ID"]);

    $order->set('google_id', $xmlData["GOOGLE-ORDER-NUMBER"]);
    $order->set('google_total', $xmlData["ORDER-TOTAL"]);
    $order->set('status', "I");

    $order->update();

    // Get profile details
    $billing_addr = $_this->getXMLDataByPath($xmlData, "BUYER-BILLING-ADDRESS");
    $shipping_addr = $_this->getXMLDataByPath($xmlData, "BUYER-SHIPPING-ADDRESS");

    if (is_null($shipping_addr) || is_null($billing_addr)) {
        $_this->xlite->logger->log("NEW-ORDER-NOTIFICATION: Billing or Shipping addresses missed. ");
        return false;
    }

    // get profile
    $is_new_profile = false;
    $profile = $order->get('profile');
    if (is_null($profile)) {
        $profile = new XLite_Model_Profile();
        $is_new_profile = true;
    } else {
        $order->setProfileCopy($profile);
        $order->update();
        $profile = $order->get('profile');
    }

if ($is_new_profile) {
    $_this->xlite->logger->log('CREATE NEW PROFILE');
} else {
    $_this->xlite->logger->log('UPDATE PROFILE');
}

    // set billing information
    $profile->set('billing_title', "");
    $profile->set('billing_firstname', $billing_addr["CONTACT-NAME"]);
    $profile->set('billing_lastname', "");
    $profile->set('billing_company', $billing_addr["COMPANY-NAME"]);
    $profile->set('billing_phone', $billing_addr['PHONE']);
    $profile->set('billing_fax', $billing_addr['FAX']);
    $profile->set('billing_address', $billing_addr['ADDRESS1']." ".$billing_addr['ADDRESS2']);
    $profile->set('billing_city', $billing_addr['CITY']);
    $profile->set('billing_country', $billing_addr["COUNTRY-CODE"]);
    $profile->set('billing_zipcode', $billing_addr["POSTAL-CODE"]);

    $state = new XLite_Model_State();
    if ($state->find("code='".trim(addslashes($billing_addr['REGION']))."'")) {
        $profile->set('billing_state', $state->get('state_id'));
    } else {
        $profile->set('billing_custom_state', trim($billing_addr['REGION']));
    }

    // set shipping information
    $profile->set('shipping_title', "");
    $profile->set('shipping_firstname', $shipping_addr["CONTACT-NAME"]);
    $profile->set('shipping_lastname', "");
    $profile->set('shipping_company', (($shipping_addr["COMPANY-NAME"]) ? $shipping_addr["COMPANY-NAME"] : ""));
    $profile->set('shipping_phone', (($shipping_addr['PHONE']) ? $shipping_addr['PHONE'] : ""));
    $profile->set('shipping_fax', (($shipping_addr['FAX']) ? $shipping_addr['FAX'] : ""));
    $profile->set('shipping_address', $shipping_addr['ADDRESS1']." ".$shipping_addr['ADDRESS2']);
    $profile->set('shipping_city', $shipping_addr['CITY']);
    $profile->set('shipping_country', $shipping_addr["COUNTRY-CODE"]);
    $profile->set('shipping_zipcode', $shipping_addr["POSTAL-CODE"]);

    $state = new XLite_Model_State();
    if ($state->find("code='".trim(addslashes($shipping_addr['REGION']))."'")) {
        $profile->set('shipping_state', $state->get('state_id'));
    } else {
        $profile->set('shipping_custom_state', trim($shipping_addr['REGION']));
    }

    $profile->set('login', $billing_addr['EMAIL']);
    $profile->set('access_level', 0);

    if ($pm->getComplex('params.disable_customer_notif')) {
        $old_dc_mailer = $_this->xlite->get('GoogleCheckoutDCNMailer');
        $_this->xlite->set('GoogleCheckoutDCNMailer', true);
    }

    // Register if new profile
    if ($is_new_profile) {
        $profile->create();
        $profile->set('order_id', $order->get('order_id'));

        $order->set('profile', $profile);
        $order->update();
    }

    $profile->update();
    $order->succeed();

    if ($pm->getComplex('params.disable_customer_notif')) {
        $_this->xlite->set('GoogleCheckoutDCNMailer', $old_dc_mailer);
    }

    $session_id = strrev(base64_decode($order->getComplex('google_details.gid')));
    if ($session_id) {
        $sql_table = $_this->db->getTableByAlias('sessions');
        $sql = "SELECT data FROM $sql_table WHERE id='$session_id';";
        $data = (array)unserialize($_this->db->getOne($sql));

        if (isset($data['order_id'])) {
            unset($data['order_id']);
        }

        $sql = "UPDATE $sql_table SET data='".serialize((array)$data)."' WHERE id='$session_id'";
        $_this->db->query($sql);
    }

    return true;
}


function GoogleCheckout_risk_information_notification($_this, $xmlData)
{
    $xmlData = $_this->getXMLDataByPath($xmlData, "RISK-INFORMATION-NOTIFICATION");
    $googleId = $xmlData["GOOGLE-ORDER-NUMBER"];

    // Get Google order
    if (($order = GoogleCheckout_getOrderByGoogleId($googleId)) == null) {
        $_this->xlite->logger->log("RISK-INFORMATION-NOTIFICATION: Order not found by GOOGLE-ORDER-NUMBER #$googleId or order in 'T' status.");
    
        return false;
    }

    $risk_info = $_this->getXMLDataByPath($xmlData, "RISK-INFORMATION");
    $avs_check = $risk_info["AVS-RESPONSE"];
    $cvn_check = $risk_info["CVN-RESPONSE"];
    $eligible_check = (strcasecmp($risk_info["ELIGIBLE-FOR-PROTECTION"], "true") == 0) ? true : false;

    // Store RISK info in order details
    $order->set('detailLabels.avsMessage', "AVS message");
    $order->set('detailLabels.cvnMessage', "CVN message");
    $order->setComplex('details.avsMessage', ((isset($_this->avs_info["$avs_check"])) ? $_this->avs_info["$avs_check"] : $avs_check));
    $order->setComplex('details.cvnMessage', ((isset($_this->cvn_info["$cvn_check"])) ? $_this->cvn_info["$cvn_check"] : $cvn_check));

    $order->setComplex('google_details.risks_set', true);
    $order->setComplex('google_details.avs', $avs_check);
    $order->setComplex('google_details.cvn', $cvn_check);
    $order->setComplex('google_details.eligible', $eligible_check);

    $order->setComplex('google_details.ip_address', $risk_info["IP-ADDRESS"]);
    $order->setComplex('google_details.partial_cc_number', $risk_info["PARTIAL-CC-NUMBER"]);
    $order->setComplex('google_details.buyer_account_age', $risk_info["BUYER-ACCOUNT-AGE"]);

    $order->update();

    GoogleCheckout_process_chargeable_order($_this, $order);

    return true;
}


function GoogleCheckout_order_state_change_notification($_this, $xmlData)
{
    $xmlData = $_this->getXMLDataByPath($xmlData, "ORDER-STATE-CHANGE-NOTIFICATION");
    $googleId = $xmlData["GOOGLE-ORDER-NUMBER"];

    // Get Google order
    if (($order = GoogleCheckout_getOrderByGoogleId($googleId)) == null) {
        $_this->xlite->logger->log("ORDER-STATE-CHANGE-NOTIFICATION: Order not found by GOOGLE-ORDER-NUMBER #$googleId or order in 'T' status.");
        return false;
    }

    $order->setComplex('google_details.fulfillment_state', $xmlData["NEW-FULFILLMENT-ORDER-STATE"]);
    $order->setComplex('google_details.financial_state', $xmlData["NEW-FINANCIAL-ORDER-STATE"]);
    $order->update();

    $state_new = $xmlData["NEW-FINANCIAL-ORDER-STATE"];
    $state_prev = $xmlData["PREVIOUS-FINANCIAL-ORDER-STATE"];

    // Set order CHARGEABLE
    if ($state_new == "CHARGEABLE" && $state_prev == "REVIEWING") {
        $status = $_this->get('chargeableStatus');
        $order->set('status', (($status) ?  $status : "Q"));
        $order->setComplex('google_details.chargeable_set', true);
        $order->update();

        $_this->xlite->logger->log("ORDER-STATE-CHANGE-NOTIFICATION: Order #".$order->get('order_id')." CHARGEABLE.");
        GoogleCheckout_process_chargeable_order($_this, $order);

        return true;
    }

    // Set order CHARGED
    if ($state_new == "CHARGED" && $state_prev == "CHARGING") {
        $status = $_this->get('chargedStatus');
        $order->set('status', (($status) ?  $status : "P"));
        $order->update();

        $_this->xlite->logger->log("ORDER-STATE-CHANGE-NOTIFICATION: Order #".$order->get('order_id')." CHARGED.");
        return true;
    }

    // Set order CANCELLED
    if (in_array($state_new, array('PAYMENT_DECLINED', "CANCELLED", "CANCELLED_BY_GOOGLE"))) {
        $status = $_this->get('failedStatus');
        $order->set('status', (($status) ?  $status : "F"));
        $order->set('details.error', $xmlData['REASON']." ".$state_new);
        $order->setComplex('detailLabels.error', "Error");
        $order->set('google_status', "C");
        $order->update();

        $_this->xlite->logger->log("ORDER-STATE-CHANGE-NOTIFICATION: Cancel order #".$order->get('order_id')." declined by reason: '".$xmlData['REASON']."'");
        return true;
    }

    return true;
}

function GoogleCheckout_order_charge_amount_notification($_this, $xmlData)
{
    $xmlData = $_this->getXMLDataByPath($xmlData, "CHARGE-AMOUNT-NOTIFICATION");
    $googleId = $xmlData["GOOGLE-ORDER-NUMBER"];

    // Get Google order
    if (($order = GoogleCheckout_getOrderByGoogleId($googleId)) == null) {
        $_this->xlite->logger->log("ORDER-STATE-CHANGE-NOTIFICATION: Order not found by GOOGLE-ORDER-NUMBER #$googleId or order in 'T' status.");
        return false;
    }

    $order->setComplex('google_details.total_charge_amount', $xmlData["TOTAL-CHARGE-AMOUNT"]);
    $order->update();

    return true;
}

function GoogleCheckout_order_refund_amount_notification($_this, $xmlData)
{
    $xmlData = $_this->getXMLDataByPath($xmlData, "REFUND-AMOUNT-NOTIFICATION");
    $googleId = $xmlData["GOOGLE-ORDER-NUMBER"];

    if (($order = GoogleCheckout_getOrderByGoogleId($googleId)) == null) {
        $_this->xlite->logger->log("REFUND-AMOUNT-NOTIFICATION: Order not found by GOOGLE-ORDER-NUMBER #$googleId or order in 'T' status.");
        return false;
    }

    if ($order->get('google_status') != "C") {
        if (abs($order->getComplex('google_details.total_charge_amount') - $xmlData["TOTAL-REFUND-AMOUNT"]) == 0) {
            $order->set('google_status', "R");
        } else {
            $order->set('google_status', "P");
        }
    }

    $amount = sprintf("%.02f", $xmlData["TOTAL-REFUND-AMOUNT"]);
    $order->setComplex('google_details.refund_amount', $amount);
    $order->update();
    return true;
}


//*********************************************************************************
//************************ CoogleCheckout Order Management Section ****************
//*********************************************************************************

function GoogleCheckout_OrderCancel($_this, $googleId, $_reason, $_comment)
{
    $params = $_this->get('params');

    $reason = GoogleCheckout_encode_utf8_string($_reason);
    $comment = GoogleCheckout_encode_utf8_string($_comment);

    $_this->xlite->logger->log("Cancel Google order #$googleId");

    $xmlRequest = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<cancel-order xmlns="http://checkout.google.com/schema/2" google-order-number="$googleId">
    <reason>$reason</reason>
    <comment>$comment</comment>
</cancel-order>
EOT;

    $response = GoogleCheckout_sendRequest($_this, $params, $xmlRequest);
    return ($response['ERROR']["ERROR-MESSAGE"]) ? false : true;
}


function GoogleCheckout_OrderCharge($_this, $googleId, $_amount)
{
    $params = $_this->get('params');
    $currency = $params['currency'];

    $amount = sprintf("%.02f", $_amount);
    $_this->xlite->logger->log("Charge Google order #$googleId");

    $xmlRequest = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<charge-order xmlns="http://checkout.google.com/schema/2" google-order-number="$googleId">
    <amount currency="$currency">$amount</amount>
</charge-order>
EOT;

    $response = GoogleCheckout_sendRequest($_this, $params, $xmlRequest);
    return ($response['ERROR']["ERROR-MESSAGE"]) ? false : true;
}

function GoogleCheckout_OrderSendMessage($_this, $googleId, $_message, $_send_email)
{
    $params = $_this->get('params');

    $send_email = (($_send_email == true) ? "true" : "false");
    $message = GoogleCheckout_encode_utf8_string($_message);

    $_this->xlite->logger->log("Send Message Google order #$googleId");

    $xmlRequest = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<send-buyer-message xmlns="http://checkout.google.com/schema/2" google-order-number="$googleId">
    <message>$message</message>
    <send-email>$send_email</send-email>
</send-buyer-message>
EOT;

    $response = GoogleCheckout_sendRequest($_this, $params, $xmlRequest);
    return (($response['ERROR']["ERROR-MESSAGE"]) ? $response['ERROR']["ERROR-MESSAGE"] : true);
}

function GoogleCheckout_OrderArchive($_this, $googleId)
{
    $params = $_this->get('params');

    $xmlRequest = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<archive-order xmlns="http://checkout.google.com/schema/2" google-order-number="$googleId" />
EOT;

    $response = GoogleCheckout_sendRequest($_this, $params, $xmlRequest);
    return (($response['ERROR']["ERROR-MESSAGE"]) ? $response['ERROR']["ERROR-MESSAGE"] : true);
}

function GoogleCheckout_OrderUnArchive($_this, $googleId)
{
    $params = $_this->get('params');

    $xmlRequest = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<unarchive-order xmlns="http://checkout.google.com/schema/2" google-order-number="$googleId" />
EOT;

    $response = GoogleCheckout_sendRequest($_this, $params, $xmlRequest);
    return (($response['ERROR']["ERROR-MESSAGE"]) ? $response['ERROR']["ERROR-MESSAGE"] : true);
}

function GoogleCheckout_OrderProcess($_this, $googleId)
{
    $params = $_this->get('params');

    $xmlRequest = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<process-order xmlns="http://checkout.google.com/schema/2" google-order-number="$googleId"/>
EOT;

    $response = GoogleCheckout_sendRequest($_this, $params, $xmlRequest);
    return (($response['ERROR']["ERROR-MESSAGE"]) ? $response['ERROR']["ERROR-MESSAGE"] : true);
}

function GoogleCheckout_OrderMerchantOrderNumber($_this, $googleId, $_number)
{
    $params = $_this->get('params');

    $number = GoogleCheckout_encode_utf8_string($_number);

    $xmlRequest = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<add-merchant-order-number xmlns="http://checkout.google.com/schema/2" google-order-number="$googleId">
    <merchant-order-number>$number</merchant-order-number>
</add-merchant-order-number>
EOT;

    $response = GoogleCheckout_sendRequest($_this, $params, $xmlRequest);
    return (($response['ERROR']["ERROR-MESSAGE"]) ? $response['ERROR']["ERROR-MESSAGE"] : true);
}

function GoogleCheckout_OrderAddTrackingData($_this, $googleId, $_tracking, $_carrier)
{
    $params = $_this->get('params');

    $order = GoogleCheckout_getOrderByGoogleId($googleId);
    $carrier = (($_carrier) ? $_carrier : GoogleCheckout_encode_utf8_string($order->get('googleShippingCarrirer')));
    $tracking = GoogleCheckout_encode_utf8_string($_tracking);

    $xmlRequest = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<add-tracking-data xmlns="http://checkout.google.com/schema/2" google-order-number="$googleId">
    <tracking-data>
        <carrier>$carrier</carrier>
        <tracking-number>$tracking</tracking-number>
    </tracking-data>
</add-tracking-data>
EOT;

    $response = GoogleCheckout_sendRequest($_this, $params, $xmlRequest);
    return (($response['ERROR']["ERROR-MESSAGE"]) ? $response['ERROR']["ERROR-MESSAGE"] : true);
}

function GoogleCheckout_OrderDeliver($_this, $googleId, $_send_email)
{
    $params = $_this->get('params');

    $order = GoogleCheckout_getOrderByGoogleId($googleId);
    $carrier = GoogleCheckout_encode_utf8_string($order->get('googleShippingCarrirer'));
    $tracking = GoogleCheckout_encode_utf8_string($order->get('tracking'));
    $send_email = (($_send_email == true) ? "true" : "false");

    $xmlRequest = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<deliver-order xmlns="http://checkout.google.com/schema/2" google-order-number="$googleId">
    <tracking-data>
        <carrier>$carrier</carrier>
        <tracking-number>$tracking</tracking-number>
    </tracking-data>
    <send-email>$send_email</send-email>
</deliver-order>
EOT;

    $response = GoogleCheckout_sendRequest($_this, $params, $xmlRequest);
    return (($response['ERROR']["ERROR-MESSAGE"]) ? $response['ERROR']["ERROR-MESSAGE"] : true);
}

function GoogleCheckout_OrderRefund($_this, $googleId, $_amount, $_reason, $_comment)
{
    $params = $_this->get('params');
    $currency = $params['currency'];

    $amount = sprintf("%.02f", $_amount);
    $reason = GoogleCheckout_encode_utf8_string($_reason);
    $comment = GoogleCheckout_encode_utf8_string($_comment);

    $xmlRequest = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<refund-order xmlns="http://checkout.google.com/schema/2" google-order-number="$googleId">
    <amount currency="$currency">$amount</amount>
    <comment>$comment</comment>
    <reason>$reason</reason>
</refund-order>
EOT;


    $response = GoogleCheckout_sendRequest($_this, $params, $xmlRequest);
    return (($response['ERROR']["ERROR-MESSAGE"]) ? $response['ERROR']["ERROR-MESSAGE"] : true);
}


///////////////////////////////////////////////////////////////////////////////////////////

function GoogleCheckout_getOrderByGoogleId($googleId)
{
    $order = new XLite_Model_Order();
    if ($order->find("google_id='".addslashes($googleId)."'")) {
        return $order;
    }

    return null;
}

function GoogleCheckout_process_chargeable_order($_this, $order)
{
    if ($order->getComplex('google_details.risks_set') && $order->getComplex('google_details.chargeable_set')) {
        // Deferred notifications order-state-change-notification and
        // risk-information-notification both received.

        $googleId = $order->get('google_id');

        // Check merchant calculation flag
        if (!$order->getComplex('google_details.calc') && $_this->getComplex('params.merchant_calc')) {
            // Merchant calculation invalid - cancel the order.
            $_this->xlite->logger->log("CANCEL order - Google merchant calculation not valid.");

            GoogleCheckout_OrderCancel($_this, $googleId, "Merchant calculations are not valid", "Your order could not be placed because Google Checkout did not receive valid merchant calculations due to response timeout. Try placing the order once again or contact the store administrator about the problem.");
            return false;
        }

        // Check risks
        $details = $order->get('google_details');
        if (!in_array($details['avs'], (array)$_this->getComplex('params.check_avs')) || !in_array($details['cvn'], (array)$_this->getComplex('params.check_cvn')) || ($_this->getComplex('params.check_prot') && !$details['eligible'])) {
            $order->set('detailLabels.riskCheck', "Risk check");
            $order->setComplex('details.riskCheck', "FAILED");
            $order->update();

            $_this->xlite->logger->log("ORDER #".$order->get('order_id')." - Failed risk check.");
            return false;
        }

        // Risk check passed
        $order->set('detailLabels.riskCheck', "Risk check");
        $order->setComplex('details.riskCheck', "PASSED");
        $order->update();

        // Charge order
        $_this->xlite->logger->log("CHARGE order ".$order->get('order_id')." - Failed risk check.");
        GoogleCheckout_OrderCharge($_this, $googleId, $order->get('google_total'));

        return true;
    }
}

?>
