<?php

/*
* Hiden methods
*
* @version $Id$
//*/

if (!defined('SAGEPAY_DIRECT_DEBUG_LOG')) {
    define('SAGEPAY_DIRECT_DEBUG_LOG', 0);
}
if (!defined('SAGEPAY_FORM_DEBUG_LOG')) {
    define('SAGEPAY_FORM_DEBUG_LOG', 0);
}

function func_SagePay_getTxCode($pm, $order)
{
    $stamp = substr(md5(uniqid(time())), 0, 4);
    $vendorTxCode = substr($pm->getComplex('params.order_prefix'), 0, 20)."_".$order->get('order_id')."_".$stamp;
    $vendorTxCode = substr($vendorTxCode, 0, 40);
    $vendorTxCode = preg_replace("/[^\d\w_]/", "_", $vendorTxCode);
    return $vendorTxCode;
}

/////////////////////////////////// SagePay VSP Direct ////////////////////////////////////
function func_SagePayDirect_process($_this, $order)
{
    $vendor = $_this->getComplex('params.vendor_name');
    $vendorTxCode = func_SagePay_getTxCode($_this, $order);
    $currency = (($_this->getComplex('params.currency')) ? $_this->getComplex('params.currency') : "USD");

    $profile = $order->get('profile');

    $TxType = $_this->getComplex('params.trans_type');
    if (!in_array($TxType, array('AUTHENTICATE', "PAYMENT", "PAYMENT"))) {
        $TxType = "AUTHENTICATE";
    }

    $trxData = array(
        "VPSProtocol"      => "2.23",
        "TxType"           => $TxType,
        "Vendor"           => $vendor,
        "VendorTxCode"     => $vendorTxCode,
        "Amount"           => sprintf("%.02f", $order->get('total')),
        "Currency"         => $currency,
        "Description"      => "Shopping cart #".$order->get('order_id')
    );
    $trxData['BillingSurname'] = $profile->get('billing_lastname');
    $trxData['BillingFirstnames'] = $profile->get('billing_firstname');
    $trxData['BillingAddress1'] = $profile->get('billing_address');
    $trxData['BillingCity'] = $profile->get('billing_city');
    $trxData['BillingPostCode'] = $profile->get('billing_zipcode');
    $trxData['BillingCountry'] = $profile->get('billing_country');
    $trxData['BillingState'] = func_SagePay_getState($profile, "billing_state", "billing_custom_state");
    $trxData['DeliverySurname'] = $profile->get('shipping_lastname');
    $trxData['DeliveryFirstnames'] = $profile->get('shipping_firstname');
    $trxData['DeliveryAddress1'] = $profile->get('shipping_address');
    $trxData['DeliveryCity'] = $profile->get('shipping_city');
    $trxData['DeliveryPostCode'] = $profile->get('shipping_zipcode');
    $trxData['DeliveryCountry'] = $profile->get('shipping_country');
    $trxData['DeliveryState'] = func_SagePay_getState($profile, "shipping_state", "shipping_custom_state");
    $trxData['CustomerName'] = $profile->get('billing_firstname')." ".$profile->get('billing_lastname');
    $trxData['ContactNumber'] = $profile->get('billing_phone');
    $trxData['ContactFax'] = $profile->get('billing_fax');
    $trxData['CustomerEMail'] = $profile->get('login');
    $trxData['Basket'] = func_SagePay_getBasket($order);
    $trxData['GiftAidPayment'] = 0;
    $trxData['ClientIPAddress'] = $_this->get('clientIP');
    $trxData['Apply3DSecure'] = $_this->getComplex('params.Apply3DSecure');
    $trxData['ApplyAVSCV2'] = $_this->getComplex('params.ApplyAVSCV2');

    $trxData = array_merge($trxData, $_this->get('ccDetails'));

    $request = func_SagePay_clean_inputs($trxData);
    $request = func_SagePayDirect_prepareTrxData($trxData);
    $response = func_SagePayDirect_sendRequestDirect($_this, $request);

    if ($response['Status'] == "3DAUTH") {
        // goto Visa/MasterCard
        $_this->session->set('SagePayDirectQueued', $order->get('order_id'));

        $order->set('details.3DSecureStatus', "NOT CHECKED");
        $order->set('detailLabels.3DSecureStatus', "3D Secure Status");
        $order->update();

        $response['termUrl'] = $_this->get('returnUrl');
        echo func_SagePayDirect_getRedirectForm($response);

        $_this->session->writeClose();

        exit;
    } else { //if ($response['Status'] == "OK") {
        func_SagePay_response_handling($response, $order, $_this);
    }
}

function func_SagePayDirect_action_return($_this, $order, $payment)
{
    $trxData = array(
        "MD"	=> $_this->get('MD'),
        "PaRes"	=> $_this->get('PaRes')
    );

    $url = $payment->getServiceUrl('callback');
    $request = func_SagePayDirect_prepareTrxData($trxData);
    $response = func_SagePayDirect_sendRequestDirect($_this, $request, $url);

    func_SagePay_response_handling($response, $order, $payment);
}


function func_SagePay_response_handling($response, $order, &$payment)
{
    $detailLabels = array();

    // Process response
    switch ($response['Status']) {
        case "OK":
            // success
            $order->setComplex('details.status', $response['Status']);
            $order->setComplex('details.statusDetail', $response['StatusDetail']);
            $order->setComplex('details.VPSTxId', $response['VPSTxId']);
            $order->setComplex('details.avscv2', $response['AVSCV2']);
            $order->setComplex('details.addressResult', $response['AddressResult']);
            $order->setComplex('details.posCodeResult', $response['PostCodeResult']);
            $order->setComplex('details.cv2Result', $response['CV2Result']);

            $detailLabels = array(
                "status"		=> "Status",
                "statusDetail"	=> "Status Detail",
                "VPSTxId"		=> "VPSTxId",
                "avscv2"		=> "AVSCV2",
                "addressResult"	=> "Address Result",
                "posCodeResult"	=> "PostCode Result",
                "cv2Result"		=> "CV2 Result",
            );

            if (isset($response['SecurityKey'])) {
                $order->setComplex('details.securityKey', $response['SecurityKey']);
                $detailLabels['securityKey'] = "Security Key";
            }

            if (isset($response['TxAuthNo'])) {
                $order->setComplex('details.TxAuthNo', $response['TxAuthNo']);
                $detailLabels['TxAuthNo'] = "TxAuthNo";
            }

            if (isset($response['CAVV'])) {
                $order->setComplex('details.cavv', $response['CAVV']);
                $detailLabels['cavv'] = "CAVV";
            }

            if (isset($response['Amount'])) {
                $order->setComplex('details.amount', $response['Amount']);
                $detailLabels['amount'] = "Amount";
            }

            $status = $order->get('status');
            if (isset($response['3DSecureStatus'])) {
                $order->setComplex('details.3DSecureStatus', $response['3DSecureStatus']);
                $detailLabels['3DSecureStatus'] = "3DSecureStatus";

                if ($response['3DSecureStatus'] == "OK") {
                    $status = $payment->get('orderSuccess3dOkStatus');
                } elseif ($response['3DSecureStatus'] == "NOTCHECKED") {
                    $status = $payment->get('orderSuccessNo3dStatus');
                } else {
                    $status = $payment->get('orderSuccess3dFailStatus');
                }
            } else {
                $status = $payment->get('orderSuccessNo3dStatus');
            }
            if ($order->xlite->AOMEnabled) {
                $order->set('orderStatus', $status);
            } else {
                $order->set('status', $status);
            }
        break;

        case "AUTHENTICATED":
        case "REGISTERED":
            $order->setComplex('details.status', $response['Status']);
            $order->setComplex('details.statusDetail', $response['StatusDetail']);
            $order->setComplex('details.VPSTxId', $response['VPSTxId']);

            $detailLabels = array(
                "status"		=> "Status",
                "statusDetail"	=> "Status Detail",
                "VPSTxId"		=> "VPSTxId",
            );

            if (isset($response['3DSecureStatus'])) {
                $order->setComplex('details.3DSecureStatus', $response['3DSecureStatus']);
                $detailLabels['3DSecureStatus'] = "3DSecureStatus";
            }

            if (isset($response['SecurityKey'])) {
                $order->setComplex('details.securityKey', $response['SecurityKey']);
                $detailLabels['securityKey'] = "Security Key";
            }

            if (isset($response['TxAuthNo'])) {
                $order->setComplex('details.TxAuthNo', $response['TxAuthNo']);
                $detailLabels['TxAuthNo'] = "TxAuthNo";
            }

            if (isset($response['Amount'])) {
                $order->setComplex('details.amount', $response['Amount']);
                $detailLabels['amount'] = "Amount";
            }

            if ($order->xlite->AOMEnabled) {
                $order->set('orderStatus', $payment->get('orderAuthStatus'));
            } else {
                $order->set('status', $payment->get('orderAuthStatus'));
            }
        break;

        default:
        case "NOTAUTHED":
        case "REJECTED":
            $order->setComplex('details.status', $response['Status']);
            $order->setComplex('details.statusDetail', $response['StatusDetail']);
            $order->setComplex('details.VPSTxId', $response['VPSTxId']);
            $order->setComplex('details.securityKey', $response['SecurityKey']);

            $detailLabels = array(
                "status"		=> "Status",
                "statusDetail"	=> "Status Detail",
                "VPSTxId"		=> "VPSTxId",
                "securityKey"	=> "Security Key"
            );
            $order->setComplex('details.error', "(".$response['Status'].") ".$response['StatusDetail']);
            if ($order->xlite->AOMEnabled) {
                $order->set('orderStatus', $payment->get('orderRejectStatus'));
            } else {
                $order->set('status', $payment->get('orderRejectStatus'));
            }
        break;

    }
    
    $order->set('detailLabels', $detailLabels);
    $order->update();
}


/////////////////////////////////// SagePay VSP Form ////////////////////////////////////
function func_SagePayForm_compileInfoCrypt($_this, $order)
{
    $vendorTxCode = func_SagePay_getTxCode($_this, $order);
    $currency = (($_this->getComplex('params.currency')) ? $_this->getComplex('params.currency') : "USD");

    $profile = $order->get('profile');

    $trxData = array(
        "VendorTxCode"	=> $vendorTxCode,
        "Amount"		=> sprintf("%.02f", $order->get('total')),
        "Currency"		=> $currency,
        "Description"		=> "Shopping cart #".$order->get('order_id'),
        "SuccessURL"		=> $_this->getSuccessUrl($order->get('order_id')),
        "BillingSurname" 	=> $profile->get('billing_lastname'),
        "BillingFirstnames" 	=> $profile->get('billing_firstname'),
        "BillingAddress1" 	=> $profile->get('billing_address'),
        "BillingCity" 		=> $profile->get('billing_city'),
        "BillingPostCode" 	=> $profile->get('billing_zipcode'),
        "BillingCountry" 	=> $profile->get('billing_country'),
        "BillingState" 		=> func_SagePay_getState($profile, "billing_state", "billing_custom_state"),
        "DeliverySurname" 	=> $profile->get('shipping_lastname'),
        "DeliveryFirstnames" 	=> $profile->get('shipping_firstname'),
        "DeliveryAddress1" 	=> $profile->get('shipping_address'),
        "DeliveryCity" 		=> $profile->get('shipping_city'),
        "DeliveryPostCode" 	=> $profile->get('shipping_zipcode'),
        "DeliveryCountry" 	=> $profile->get('shipping_country'),
        "DeliveryState" 	=> func_SagePay_getState($profile, "shipping_state", "shipping_custom_state"),
        "CustomerName" 		=> $profile->get('billing_firstname')." ".$profile->get('billing_lastname'),
        "ContactNumber" 	=> $profile->get('billing_phone'),
        "ContactFax" 		=> $profile->get('billing_fax'),
        "CustomerEMail" 	=> $profile->get('login'),
        "Basket" 		=> func_SagePay_getBasket($order, true),
        "eMailMessage" 		=> $_this->getComplex('params.eMailMessage'),
        "FailureURL"		=> $_this->getFailureUrl($order->get('order_id')),
        	"GiftAidPayment"    	=> 0,
        	"ClientIPAddress"   	=> $_this->get('clientIP'),
        "Apply3DSecure"     	=> $_this->getComplex('params.Apply3DSecure'),
        "ApplyAVSCV2"           => $_this->getComplex('params.ApplyAVSCV2')
    );

if (SAGEPAY_FORM_DEBUG_LOG) {
$_this->xlite->logger->log("SagePay VSP Form crypt:".var_export($trxData, true));
}

    $trxData = func_SagePay_clean_inputs($trxData);
    $trxData = func_SagePayForm_prepareTrxData($trxData);
    $crypt = base64_encode(func_SagePayForm_simpleXor($trxData, $_this->getComplex('params.xor_password')));

    return $crypt;
}

function func_SagePayForm_action_return($_this, $paymentMethod)
{
    $crypt = array();
    $vars = (array)$_REQUEST;
    foreach ($vars as $key=>$value) {
        if (strtolower($key) == "crypt") {
            $crypt = $value;
            break;
        }
    }

    if (!$crypt)
        return false;

    $crypt = preg_replace('/ /', "+", $crypt);
    $response = func_SagePayForm_simpleXor(base64_decode($crypt), $paymentMethod->getComplex('params.xor_password'));

    $responseArray = array();
    $nodes = explode('&', $response);
    foreach ((array)$nodes as $val) {
        $pos = strpos($val, "=");
        if ($pos !== false) {
            $key = substr($val, 0, $pos);
            $value = substr($val, $pos+1, strlen($val)-$pos-1);
        }
        $responseArray[$key] = trim($value);
    }

if (SAGEPAY_FORM_DEBUG_LOG) {
$_this->xlite->logger->log("SagePay VSP Form response:".var_export($responseArray, true)."\n");
}

    // extract order_id
    preg_match("/_([\d]+)_[a-f0-9]{4}$/", $responseArray['VendorTxCode'], $out);
    $order_id = $out[1];

    // check order exists
    $_order = new \XLite\Model\Order();
    if (!$_order->find("order_id='$order_id'")) {
if (SAGEPAY_FORM_DEBUG_LOG) {
$_this->xlite->logger->log("SagePay VSP Form response Error: Order #$order_id not found.");
}
        return false;
    }

    // set checkout dialog order
    $_this->order = null;
    $_REQUEST['order_id'] = $order_id;

    if ($responseArray['Status'] == "ABORT") {
        return false;
    }

    $order = $_this->get('order');

    func_SagePay_response_handling($responseArray, $order, $paymentMethod);

    return true;
}

///////////////////////////////////////// Transport //////////////////////////////
function func_SagePayDirect_sendRequestDirect(&$payment, $post, $url=null)
{
    if (is_null($url)) {
        $url = $payment->get('serviceUrl');
    }

    $https = new \XLite\Model\HTTPS();
    $https->url        = $url;
    $https->data       = $post;
    $https->method     = 'POST';
    $https->urlencoded = true;

if (SAGEPAY_DIRECT_DEBUG_LOG) {
$payment->xlite->logger->log("Request:");
$payment->xlite->logger->log("URL: ".$https->url);
$payment->xlite->logger->log("REQUEST: ".var_export($https->data, true));
}

    $https->request();
    $response = $https->response;

if (SAGEPAY_DIRECT_DEBUG_LOG) {
$payment->xlite->logger->log("RESPONSE: ".$https->response."");
}

    $response = preg_replace("/read:errno=[\d]+$/", "", $response);

    if (!$response || $https->error) {
        $response = "VPSProtocol=2.23\nStatus=INVALID\nStatusDetail=".(($https->error) ? $https->error : "No response")."";
    }

    $responseArray = array();
    $nodes = explode("\n", $response);
    foreach ((array)$nodes as $val) {
        $pos = strpos($val, "=");
        if ($pos !== false) {
            $key = substr($val, 0, $pos);
            $value = substr($val, $pos+1, strlen($val)-$pos-1);
        }
        $responseArray[$key] = trim($value);
    }

if (SAGEPAY_DIRECT_DEBUG_LOG) {
$payment->xlite->logger->log("RESPONSE ARRAY: ".var_export($responseArray, true)."\n");
}

    return $responseArray;
}

/////////////////////////////////////////// Helper //////////////////////////////////
function func_SagePay_getState($profile, $field, $customField)
{
    if (
		(preg_match('/billing/', $field) && $profile->get('billing_country') != 'US')
		|| (preg_match('/shipping/', $field) && $profile->get('shipping_country') != 'US')
	) {
    	return '';
    }

    $state = \XLite\Core\Database::getRepo('\XLite\Model\State')->find($profile->get($field));
    return $state ? $state->code : $profile->get($customField);
}

function func_SagePay_getBasket($order, $is_form=false)
{
    $basket = array(count($order->get('items')));

    foreach ($order->get('items') as $item) {
        $basket[] = func_SagePay_encodeTrxValue($item->get('name'), $is_form).":".sprintf("%.02f", $item->get('amount')).":".sprintf("%.02f", $item->get('price')).":-:-:".sprintf("%.02f", $item->get('taxableTotal'));
    }

    return implode(":", $basket);
}

function func_SagePay_encodeTrxValue($value, $is_form=false)
{
    $value = preg_replace("/:/", " ", $value);	// TODO: prevent SagePay bug

    if ($is_form) {
        $value = preg_replace("/([&=])/ie", "urlencode('$1')", $value);
    } else {
        $value = urlencode($value);
    }

    return $value;
}

function func_SagePayDirect_prepareTrxData($trxData)
{
    $ignore = array('Basket', "MD");

    foreach ($trxData as $key=>$val) {
        if (in_array($key, $ignore)) {
            continue;
        }
        $trxData[$key] = func_SagePay_encodeTrxValue($val);
    }

    return $trxData;
}

function func_SagePayForm_prepareTrxData($trxData)
{
    $ignore = array('Basket', "SuccessURL", "FailureURL", "VendorTxCode");

    $data = array();
    foreach ($trxData as $key=>$value) {
        if (!in_array($key, $ignore)) {
            $value = func_SagePay_encodeTrxValue($value, true);
        }

        $data[] = "$key=$value";
    }

    return implode('&', $data);
}

function func_SagePayForm_simpleXor($InString, $Key)
{
    $KeyList = array();
    $output = "";

    for ($i = 0; $i < strlen($Key); $i++){
        $KeyList[$i] = ord(substr($Key, $i, 1));
    }

    for ($i = 0; $i < strlen($InString); $i++) {
        $output.= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
    }

    return $output;
}

function func_SagePayDirect_getRedirectForm($params)
{
    $form = <<<EOT
<html>
<head>
    <title>Processing your 3-D Secure Transaction</title>
    <script language="Javascript">
    <!--
        function OnLoadEvent()
        {
            document.protx_vbv_form.submit();
        }
    //-->
    </script>
</head>
<body onLoad="OnLoadEvent();">
    <form name="protx_vbv_form" action="${params['ACSURL']}" method="POST" />
        <input type="hidden" name="PaReq" value="${params['PAReq']}" />
        <input type="hidden" name="TermUrl" value="${params['termUrl']}" />
        <input type="hidden" name="MD" value="${params['MD']}" />
        <noscript>
            <center><p>Please click button below to Authenticate your card</p><input type="submit" value="Go" /></p></center>
            <input type="submit" value="Go" />
        </noscript>
    </form>
</body>
</html>
EOT;

    return $form;
}

function func_SagePay_clean_inputs($data) 
{
    $fields_specs = func_SagePay_get_allowed_fields();
    
    foreach ($fields_specs as $field => $spec) {
        if (!isset($data[$field]) || isset($spec['skip']))
            continue;
        if (isset($fields_specs[$field]['allowed_values'])) {
            if ( !in_array($data[$field], $spec['allowed_values'])) {
                if (isset($data[$field])) 
                    unset($data[$field]);
            }
            continue;
        }
        $pattern = ($spec['filter'] == "Custom") ? $spec['pattern'] : false;
        $data[$field] = cleanInput($data[$field], $spec['filter'], $spec['max'], $pattern);
    }
    return $data;
}

function func_SagePay_get_allowed_fields() 
{
    $fields_specification = array(

        "VendorTxCode" => array(
            "max" => 40,
            "filter" => "Custom",
            "pattern" => "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_."
        ),
        "Amount" => array(
            "skip" => true,
        ),
        "Currency" => array(
            "skip" => true
        ),
        "Description" => array(
            "max" => 100,
            "filter" => "Text"
        ),
        "SuccessURL" => array(
            "max" => 2000,
            "filter" => "Text"
        ),
        "FailureURL" => array(
            "max" => 2000,
            "filter" => "Text"
        ),
        "CustomerName" => array(
            "max" => 100,
            "filter" => "Text"
        ),
        "CustomerEMail" => array(
            "max" => 255,
            "filter" => "Text"
        ),
        "VendorEMail" => array(
            "max" => 255,
            "filter" => "Text"
        ),
        "SendEMail" => array(
            "allowed_values" => array(0,1,2,3)
        ),
        "eMailMessage" => array(
            "max" => 7500,
            "filter" => "Text"
        ),
        "BillingSurname" => array(
            "max" => 20,
            "filter" => "Text"
        ),
        "BillingFirstnames" => array(
            "max" => 20,
            "filter" => "Text"
        ),
        "BillingAddress1" => array(
            "max" => 100,
            "filter" => "Text"
        ),
        "BillingAddress2" => array(
            "max" => 100,
            "filter" => "Text"
        ),
        "BillingCity" => array(
            "max" => 40,
            "filter" => "Text"
        ),
        "BillingPostCode" => array(
            "max" => 10,
            "filter" => "Text"
        ),
        "BillingCountry" => array(
            "skip" => true
        ),
        "BillingState"=> array(
            "skip" => true
        ),
        "BillingPhone" => array(
            "max" => 20,
            "filter" => "Text"
        ),
        "DeliverySurname" => array(
            "max" => 20,
            "filter" => "Text"
        ),
        "DeliveryFirstnames" => array(
            "max" => 20,
            "filter" => "Text"
        ),
        "DeliveryAddress1" => array(
            "max" => 100,
            "filter" => "Text"
        ),
        "DeliveryAddress2" => array(
            "max" => 100,
            "filter" => "Text"
        ),
        "DeliveryCity" => array(
            "max" => 40,
            "filter" => "Text"
        ),
        "DeliveryPostCode" => array(
            "max" => 10,
            "filter" => "Text"
        ),
        "DeliveryCountry" => array(
            "skip" => true
        ),
        "DeliveryState" => array(
            "skip" => true
        ),
        "DeliveryPhone" => array(
            "max" => 20,
            "filter" => "Text"
        ),
        "Basket" => array(
            "max" => 7500,
            "filter" => "Text"
        ),
        "AllowGiftAid" => array(
            "allowed_values" => array('0',"1")
        ),
        "ApplyAVSCV2" => array(
            "allowed_values" => array('0',"1","2","3")
        ),
        "Apply3DSecure" => array(
            "allowed_values" => array('0',"1","2","3")
        ),
        "TxType" => array(
            "allowed_values" => array('PAYMENT',"DEFERRED","AUTHENTICATE","RELEASE","AUTHORISE","CANCEL","ABORT","MANUAL","REFUND","REPEAT","REPEATDEFERRED","VOID","PREAUTH","COMPLETE")
        ),
        "NotificationURL" => array(
            "max" => 255,
            "filter" => "Text"
        ),
        "Vendor" => array(
            "max" => 15,
            "filter" => "Text"
        ),
        "Profile" => array(
            "allowed_values" => array('LOW',"NORMAL")
        ),
        "CardHolder" => array(
            "max" => 50,
            "filter" => "Text"
        ),
        "CardNumber" => array(
            "max" => 20,
            "filter" => "Digits"
        ),
        "StartDate" => array(
            "max" => 4,
            "filter" => "Digits"
        ),
        "ExpiryDate" => array(
            "max" => 4,
            "filter" => "Digits"
        ),
        "IssueNumber" => array(
            "max" => 2,
            "filter" => "Digits"
        ),
        "CV2" => array(
            "max" => 4,
            "filter" => "Digits"
        ),
        "CardType" => array(
            "allowed_values" => array('VISA',"MC","DELTA","SOLO","MAESTRO","UKE","AMEX","DC","JCB","LASER","PAYPAL")
        ),
        "PayPalCallbackURL" => array(
            "max" => 255,
            "filter" => "Text"
        ),
        "GiftAidPayment" => array(
            "allowed_values" => array('0',"1")
        ),
        "ClientIPAddress" => array(
            "max" => 15,
            "filter" => "Text"
        ),
        "MD" => array(
            "max" => 35,
            "Text"
        ),
        "PARes" => array(
            "max" => 7500,
            "filter" => "Text"
        ),
        "VPSTxID" => array(
            "max" => 38,
            "filter" => "Text"
        ),
        "Accept" => array(
            "allowed_values" => array('Yes',"No")
        ),
        "Crypt" => array(
            "max" => 16384,
            "filter" => "Text"
        ),
        "AccountType" => array(
            "allowed_values" => array('E',"M","C")
        )
    );

    return $fields_specification;
}

function cleanInput($strRawText, $strType, $maxChars=false, $customPattern=false) 
{
    switch ($strType) {
        case "Number":
            $strClean = "0123456789.";
            $bolHighOrder = false;
            break;
        case "Digits":
            $strClean = "0123456789";
            $bolHighOrder = false;
            break;
        case "Text":
  			$strClean =" ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.,'/{}@():?-_&£$=%~<>*+\"";
            $bolHighOrder = true;
            break;
        case "Custom":
            $strClean = $customPattern;
            $bolHighOrder = false;
            break;
        default:
            break;
    }

    $strCleanedText="";
    $iCharPos = 0;

    do
        {
            // Only include valid characters
            $chrThisChar=substr($strRawText,$iCharPos,1);
            
            if (strspn($chrThisChar,$strClean,0,strlen($strClean))>0) {
                $strCleanedText=$strCleanedText . $chrThisChar;
            }
            else if ($bolHighOrder==true) {
                // Fix to allow accented characters and most high order bit chars which are harmless 
                if (bin2hex($chrThisChar)>=191) {
                    $strCleanedText=$strCleanedText . $chrThisChar;
                }
            }
            
        $iCharPos=$iCharPos+1;
        }
    while ($iCharPos<strlen($strRawText));
        
  	$cleanInput = ltrim($strCleanedText);

    if ($maxChars && strlen($cleanInput) > $maxChars)
        $cleanInput = substr($cleanInput, 0, $maxChars);
        
    return $cleanInput;
    
}

?>
