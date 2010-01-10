<?php

/*
* Hiden methods
*
* @version $Id$
//*/

if (!defined('PROTX_DIRECT_DEBUG_LOG')) {
	define('PROTX_DIRECT_DEBUG_LOG', 0);
}
if (!defined('PROTX_FORM_DEBUG_LOG')) {
	define('PROTX_FORM_DEBUG_LOG', 0);
}

function func_Protx_getTxCode($pm, $order)
{
	$stamp = substr(md5(uniqid(time())), 0, 4);
	$vendorTxCode = substr($pm->get("params.order_prefix"), 0, 20)."_".$order->get("order_id")."_".$stamp;
	$vendorTxCode = substr($vendorTxCode, 0, 40);
	$vendorTxCode = preg_replace("/[^\d\w_]/", "_", $vendorTxCode);
	return $vendorTxCode;
}

/////////////////////////////////// Protx VSP Direct ////////////////////////////////////
function func_ProtxDirect_process($_this, $order)
{
	$vendor = $_this->get("params.vendor_name");
	$vendorTxCode = func_Protx_getTxCode($_this, $order);
	$currency = (($_this->get("params.currency")) ? $_this->get("params.currency") : "USD");

	$profile = $order->get("profile");

	$TxType = $_this->get("params.trans_type");
	if (!in_array($TxType, array("AUTHENTICATE", "PAYMENT", "PAYMENT"))) {
		$TxType = "AUTHENTICATE";
	}

	$trxData = array(
		"VPSProtocol"      => "2.22",
		"TxType"           => $TxType,
		"Vendor"           => $vendor,
		"VendorTxCode"     => $vendorTxCode,
		"Amount"           => sprintf("%.02f", $order->get("total")),
		"Currency"         => $currency,
		"Description"      => "Shopping cart #".$order->get("order_id"),
		"BillingAddress"   => (
                $profile->get("billing_address")." ".
                $profile->get("billing_city")." ".
                func_Protx_getState($profile, "billing_state", "billing_custom_state")." ".
                $profile->get("billing_country")
            ),
		"BillingPostCode"  => $profile->get("billing_zipcode"),
		"DeliveryAddress"  => (
                $profile->get("shipping_address")." ".
                $profile->get("shipping_city")." ".
                func_Protx_getState($profile, "shipping_state", "shipping_custom_state")." ".
                $profile->get("shipping_country")
            ),
		"DeliveryPostCode" => $profile->get("shipping_zipcode"),
		"CustomerName"     => $profile->get("shipping_firstname")." ".$profile->get("shipping_lastname"),
		"ContactNumber"    => $profile->get("billing_phone"),
		"ContactFax"       => $profile->get("billing_fax"),
		"CustomerEMail"    => $profile->get("login"),
        "Basket"           => func_Protx_getBasket($order),
		"GiftAidPayment"   => 0,
		"ApplyAVSCV2"		=> $_this->get("params.ApplyAVSCV2"),
		"ClientIPAddress"	=> $_this->get("clientIP"),
		"Apply3DSecure"		=> $_this->get("params.Apply3DSecure")
	);

	$trxData = array_merge($trxData, $_this->get("ccDetails"));

	$request = func_ProtxDirect_prepareTrxData($trxData);
	$response = func_ProtxDirect_sendRequestDirect($_this, $request);

	if ($response["Status"] == "3DAUTH") {
		// goto Visa/MasterCard
		$_this->session->set("ProtxDirectQueued", $order->get("order_id"));

		$order->set("details.3DSecureStatus", "NOT CHECKED");
		$order->set("detailLabels.3DSecureStatus", "3D Secure Status");
		$order->update();

		$response["termUrl"] = $_this->get("returnUrl");
		echo func_ProtxDirect_getRedirectForm($response);

		$_this->session->writeClose();

		exit;
	} else { //if ($response["Status"] == "OK") {
		func_Protx_response_handling($response, $order, $_this);
	}
}

function func_ProtxDirect_action_return($_this, $order, $payment)
{
	$trxData = array(
		"MD"	=> $_this->get("MD"),
		"PaRes"	=> $_this->get("PaRes")
	);

	$url = $payment->getServiceUrl("callback");
	$request = func_ProtxDirect_prepareTrxData($trxData);
	$response = func_ProtxDirect_sendRequestDirect($_this, $request, $url);

	func_Protx_response_handling($response, $order, $payment);
}


function func_Protx_response_handling($response, $order, &$payment)
{
	$detailLabels = array();

	// Process response
	switch ($response["Status"]) {
		case "OK":
			// success
			$order->set("details.status", 			$response["Status"]);
			$order->set("details.statusDetail",		$response["StatusDetail"]);
			$order->set("details.VPSTxId",			$response["VPSTxId"]);
			$order->set("details.avscv2",			$response["AVSCV2"]);
			$order->set("details.addressResult",	$response["AddressResult"]);
			$order->set("details.posCodeResult",	$response["PostCodeResult"]);
			$order->set("details.cv2Result",		$response["CV2Result"]);

			$detailLabels = array(
				"status"		=> "Status",
				"statusDetail"	=> "Status Detail",
				"VPSTxId"		=> "VPSTxId",
				"avscv2"		=> "AVSCV2",
				"addressResult"	=> "Address Result",
				"posCodeResult"	=> "PostCode Result",
				"cv2Result"		=> "CV2 Result",
			);

			if (isset($response["SecurityKey"])) {
				$order->set("details.securityKey", $response["SecurityKey"]);
				$detailLabels["securityKey"] = "Security Key";
			}

			if (isset($response["TxAuthNo"])) {
				$order->set("details.TxAuthNo", $response["TxAuthNo"]);
				$detailLabels["TxAuthNo"] = "TxAuthNo";
			}

			if (isset($response["CAVV"])) {
				$order->set("details.cavv", $response["CAVV"]);
				$detailLabels["cavv"] = "CAVV";
			}

			if (isset($response["Amount"])) {
				$order->set("details.amount", $response["Amount"]);
				$detailLabels["amount"] = "Amount";
			}

			$status = $order->get('status');
			if (isset($response["3DSecureStatus"])) {
				$order->set("details.3DSecureStatus",   $response["3DSecureStatus"]);
				$detailLabels["3DSecureStatus"] = "3DSecureStatus";

				if ($response["3DSecureStatus"] == "OK") {
					$status = $payment->get("orderSuccess3dOkStatus");
				} elseif ($response["3DSecureStatus"] == "NOTCHECKED") {
					$status = $payment->get("orderSuccessNo3dStatus");
				} else {
					$status = $payment->get("orderSuccess3dFailStatus");
				}
			} else {
				$status = $payment->get("orderSuccessNo3dStatus");
			}
			if ($order->xlite->AOMEnabled) {
				$order->set("orderStatus", $status);
			} else {
				$order->set("status", $status);
			}
		break;

		case "AUTHENTICATED":
		case "REGISTERED":
			$order->set("details.status",			$response["Status"]);
			$order->set("details.statusDetail",		$response["StatusDetail"]);
			$order->set("details.VPSTxId",			$response["VPSTxId"]);

			$detailLabels = array(
				"status"		=> "Status",
				"statusDetail"	=> "Status Detail",
				"VPSTxId"		=> "VPSTxId",
			);

			if (isset($response["3DSecureStatus"])) {
				$order->set("details.3DSecureStatus",   $response["3DSecureStatus"]);
				$detailLabels["3DSecureStatus"] = "3DSecureStatus";
			}

			if (isset($response["SecurityKey"])) {
				$order->set("details.securityKey", $response["SecurityKey"]);
				$detailLabels["securityKey"] = "Security Key";
			}

			if (isset($response["TxAuthNo"])) {
				$order->set("details.TxAuthNo", $response["TxAuthNo"]);
				$detailLabels["TxAuthNo"] = "TxAuthNo";
			}

			if (isset($response["Amount"])) {
				$order->set("details.amount", $response["Amount"]);
				$detailLabels["amount"] = "Amount";
			}

			if ($order->xlite->AOMEnabled) {
				$order->set("orderStatus", $payment->get("orderAuthStatus"));
			} else {
				$order->set("status", $payment->get("orderAuthStatus"));
			}
		break;

		default:
		case "NOTAUTHED":
		case "REJECTED":
			$order->set("details.status",		$response["Status"]);
			$order->set("details.statusDetail",	$response["StatusDetail"]);
			$order->set("details.VPSTxId",		$response["VPSTxId"]);
			$order->set("details.securityKey",	$response["SecurityKey"]);

			$detailLabels = array(
				"status"		=> "Status",
				"statusDetail"	=> "Status Detail",
				"VPSTxId"		=> "VPSTxId",
				"securityKey"	=> "Security Key"
			);
			$order->set("details.error", "(".$response["Status"].") ".$response["StatusDetail"]);
			if ($order->xlite->AOMEnabled) {
				$order->set("orderStatus", $payment->get("orderRejectStatus"));
			} else {
				$order->set("status", $payment->get("orderRejectStatus"));
			}
		break;

	}
	
	$order->set("detailLabels", $detailLabels);
	$order->update();
}


/////////////////////////////////// Protx VSP Form ////////////////////////////////////
function func_ProtxForm_compileInfoCrypt($_this, $order)
{
	$vendorTxCode = func_Protx_getTxCode($_this, $order);
	$currency = (($_this->get("params.currency")) ? $_this->get("params.currency") : "USD");

	$profile = $order->get("profile");

	$trxData = array(
		"VendorTxCode"	=> $vendorTxCode,
		"Amount"		=> sprintf("%.02f", $order->get("total")),
		"Currency"		=> $currency,
		"Description"	=> "Shopping cart #".$order->get("order_id"),
		"SuccessURL"	=> $_this->getSuccessUrl($order->get("order_id")),
		"FailureURL"	=> $_this->getFailureUrl($order->get("order_id")),
		"CustomerName"	=> $profile->get("shipping_firstname")." ".$profile->get("shipping_lastname"),
		"CustomerEMail"	=> $profile->get("login"),
		"eMailMessage"	=> $_this->get("params.eMailMessage"),
		"BillingAddress"	=> $profile->get("billing_address")." ".$profile->get("billing_city")." ".func_Protx_getState($profile, "billing_state", "billing_custom_state")." ".$profile->get("billing_country"),
		"BillingPostCode"	=> $profile->get("billing_zipcode"),
		"DeliveryAddress"	=> $profile->get("shipping_address")." ".$profile->get("shipping_city")." ".func_Protx_getState($profile, "shipping_state", "shipping_custom_state")." ".$profile->get("shipping_country"),
		"DeliveryPostCode"	=> $profile->get("shipping_zipcode"),
		"ContactNumber"	=> $profile->get("billing_phone"),
		"ContactFax"	=> $profile->get("billing_fax"),
		"Basket"		=> func_Protx_getBasket($order, true),
		"AllowGiftAid"	=> "0",
		"ApplyAVSCV2"	=> $_this->get("params.ApplyAVSCV2"),
		"Apply3DSecure"	=> $_this->get("params.Apply3DSecure")
	);

if (PROTX_FORM_DEBUG_LOG) {
$_this->xlite->logger->log("Protx VSP Form crypt:".var_export($trxData, true));
}

	$trxData = func_ProtxForm_prepareTrxData($trxData);
	$crypt = base64_encode(func_ProtxForm_simpleXor($trxData, $_this->get('params.xor_password')));

	return $crypt;
}

function func_ProtxForm_action_return($_this, $paymentMethod)
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

	$crypt = preg_replace("/ /", "+", $crypt);
	$response = func_ProtxForm_simpleXor(base64_decode($crypt), $paymentMethod->get('params.xor_password'));

	$responseArray = array();
	$nodes = explode("&", $response);
	foreach ((array)$nodes as $val) {
		$pos = strpos($val, "=");
		if ($pos !== false) {
			$key = substr($val, 0, $pos);
			$value = substr($val, $pos+1, strlen($val)-$pos-1);
		}
		$responseArray[$key] = trim($value);
	}

if (PROTX_FORM_DEBUG_LOG) {
$_this->xlite->logger->log("Protx VSP Form response:".var_export($responseArray, true)."\n");
}

	// extract order_id
	preg_match("/_([\d]+)_[a-f0-9]{4}$/", $responseArray["VendorTxCode"], $out);
	$order_id = $out[1];

	// check order exists
	$_order = new XLite_Model_Order();
	if (!$_order->find("order_id='$order_id'")) {
if (PROTX_FORM_DEBUG_LOG) {
$_this->xlite->logger->log("Protx VSP Form response Error: Order #$order_id not found.");
}
		return false;
	}

	// set checkout dialog order
	$_this->order = null;
	$_REQUEST["order_id"] = $order_id;

	if ($responseArray["Status"] == "ABORT") {
		return false;
	}

	$order = $_this->get("order");

	func_Protx_response_handling($responseArray, $order, $paymentMethod);

	return true;
}

///////////////////////////////////////// Transport //////////////////////////////
function func_ProtxDirect_sendRequestDirect(&$payment, $post, $url=null)
{
	if (is_null($url)) {
		$url = $payment->get("serviceUrl");
	}

	$https = new XLite_Model_HTTPS();
	$https->url        = $url;
    $https->data       = $post;
	$https->method     = 'POST';
	$https->urlencoded = true;

if (PROTX_DIRECT_DEBUG_LOG) {
$payment->xlite->logger->log("Request:");
$payment->xlite->logger->log("URL: ".$https->url);
$payment->xlite->logger->log("REQUEST: ".var_export($https->data, true));
}

	$https->request();
	$response = $https->response;

if (PROTX_DIRECT_DEBUG_LOG) {
$payment->xlite->logger->log("RESPONSE: ".$https->response."");
}

	$response = preg_replace("/read:errno=[\d]+$/", "", $response);

	if (!$response || $https->error) {
		$response = "VPSProtocol=2.22\nStatus=INVALID\nStatusDetail=".(($https->error) ? $https->error : "No response")."";
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

if (PROTX_DIRECT_DEBUG_LOG) {
$payment->xlite->logger->log("RESPONSE ARRAY: ".var_export($responseArray, true)."\n");
}

	return $responseArray;
}

/////////////////////////////////////////// Helper //////////////////////////////////
function func_Protx_getState($profile, $field, $customField)
{
    $stateName = "";
    $state = new XLite_Model_State();
    if ($state->find("state_id='".$profile->get($field)."'")) {
        $stateName = $state->get('state');
    } else { // state not found
        $stateName = $profile->get($customField);
    }

    return $stateName;
}

function func_Protx_getBasket($order, $is_form=false)
{
	$basket = array(count($order->get("items")));

	foreach ($order->get("items") as $item) {
		$basket[] = func_Protx_encodeTrxValue($item->get("name"), $is_form).":".sprintf("%.02f", $item->get("amount")).":".sprintf("%.02f", $item->get("price")).":-:-:".sprintf("%.02f", $item->get("taxableTotal"));
	}

	return implode(":", $basket);
}

function func_Protx_encodeTrxValue($value, $is_form=false)
{
	$value = preg_replace("/:/", " ", $value);	// TODO: prevent Protx bug

	if ($is_form) {
		$value = preg_replace("/([&=])/ie", "urlencode('$1')", $value);
	} else {
		$value = urlencode($value);
	}

	return $value;
}

function func_ProtxDirect_prepareTrxData($trxData)
{
	$ignore = array("Basket", "MD");

	foreach ($trxData as $key=>$val) {
		if (in_array($key, $ignore)) {
			continue;
		}
		$trxData[$key] = func_Protx_encodeTrxValue($val);
	}

	return $trxData;
}

function func_ProtxForm_prepareTrxData($trxData)
{
	$ignore = array("Basket", "SuccessURL", "FailureURL", "VendorTxCode");

	$data = array();
	foreach ($trxData as $key=>$value) {
		if (!in_array($key, $ignore)) {
			$value = func_Protx_encodeTrxValue($value, true);
		}

		$data[] = "$key=$value";
	}

	return implode("&", $data);
}

function func_ProtxForm_simpleXor($InString, $Key)
{
	$KeyList = array();
	$output = "";

	for($i = 0; $i < strlen($Key); $i++){
		$KeyList[$i] = ord(substr($Key, $i, 1));
	}

	for($i = 0; $i < strlen($InString); $i++) {
		$output.= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
	}

	return $output;
}

function func_ProtxDirect_getRedirectForm($params)
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
	<form name="protx_vbv_form" action="${params["ACSURL"]}" method="POST" />
		<input type="hidden" name="PaReq" value="${params["PAReq"]}" />
		<input type="hidden" name="TermUrl" value="${params["termUrl"]}" />
		<input type="hidden" name="MD" value="${params["MD"]}" />
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

?>
