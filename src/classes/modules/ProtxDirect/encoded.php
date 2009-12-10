<?php

/*
* Hiden methods
*
* @version $Id: encoded.php,v 1.10 2007/07/12 12:07:10 osipov Exp $
//*/

if (!defined('PROTX_DIRECT_DEBUG_LOG')) {
	define('PROTX_DIRECT_DEBUG_LOG', 0);
}

function func_ProtxDirect_process(&$_this, &$order)
{
	$vendor = $_this->get("params.vendor_name");
//	$vendorTxCode = $_this->get("params.order_prefix").$order->get("order_id")."_".uniqid(time())."";
//	$vendorTxCode = substr($vendorTxCode, 0, 40);
//	$vendorTxCode = preg_replace("/[^\d\w_]/", "_", $vendorTxCode);
	$vendorTxCode = substr($_this->get("params.order_prefix"), 0, 30)."_".$order->get("order_id")."";
	$vendorTxCode = preg_replace("/[^\d\w_]/", "_", $vendorTxCode);
	$currency = (($_this->get("params.currency")) ? $_this->get("params.currency") : "USD");

	$profile = $order->get("profile");

	$trxData = array(
		"VPSProtocol"      => "2.22",
		"TxType"           => (($_this->get("params.trans_type") == "PAYMENT") ? "PAYMENT" : "DEFERRED"),
		"Vendor"           => $vendor,
		"VendorTxCode"     => $vendorTxCode,
		"Amount"           => sprintf("%.02f", $order->get("total")),
		"Currency"         => $currency,
		"Description"      => "Shopping cart #".$order->get("order_id"),
		"BillingAddress"   => (
                $profile->get("billing_address")." ".
                $profile->get("billing_city")." ".
                func_ProtxDirect_getState($profile, "billing_state", "billing_custom_state")." ".
                $profile->get("billing_country")
            ),
		"BillingPostCode"  => $profile->get("billing_zipcode"),
		"DeliveryAddress"  => (
                $profile->get("shipping_address")." ".
                $profile->get("shipping_city")." ".
                func_ProtxDirect_getState($profile, "shipping_state", "shipping_custom_state")." ".
                $profile->get("shipping_country")
            ),
		"DeliveryPostCode" => $profile->get("shipping_zipcode"),
		"CustomerName"     => $profile->get("shipping_firstname")." ".$profile->get("shipping_lastname"),
		"ContactNumber"    => $profile->get("billing_phone"),
		"ContactFax"       => $profile->get("billing_fax"),
		"CustomerEMail"    => $profile->get("login"),
        "Basket"           => func_ProtxDirect_getBasket($order),
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

//		$order->set("status", $_this->get("queuedStatus"));
		$order->set("details.3DSecureStatus", "NOT CHECKED");
		$order->set("detailLabels.3DSecureStatus", "3D Secure Status");
		$order->update();

		$response["termUrl"] = $_this->get("returnUrl");
		echo func_ProtxDirect_getRedirectForm($response);

		$_this->session->writeClose();

		exit;
	} elseif ($response["Status"] == "OK") {
		$order->set("details.status", 			$response["Status"]);
		$order->set("details.statusDetail",		$response["StatusDetail"]);
		$order->set("details.VPSTxId",			$response["VPSTxId"]);
		$order->set("details.securityKey",		$response["SecurityKey"]);
		$order->set("details.TxAuthNo",			$response["TxAuthNo"]);
		$order->set("details.avscv2",			$response["AVSCV2"]);
		$order->set("details.addressResult",	$response["AddressResult"]);
		$order->set("details.posCodeResult",	$response["PostCodeResult"]);
		$order->set("details.cv2Result",		$response["CV2Result"]);

		$detailLabels = array(
			"status"		=> "Status",
			"statusDetail"	=> "Status Detail",
			"VPSTxId"		=> "VPSTxId",
			"securityKey"	=> "Security Key",
			"TxAuthNo"		=> "TxAuthNo",
			"avscv2"		=> "AVSCV2",
			"addressResult"	=> "Address Result",
			"posCodeResult"	=> "PostCode Result",
			"cv2Result"		=> "CV2 Result",
		);

		$order->set("status", $_this->get("sucessedStatus"));
	} elseif (in_array($response["Status"], array("NOTAUTHED", "REJECTED"))) {
		$order->set("details.status", 			$response["Status"]);
		$order->set("details.statusDetail",		$response["StatusDetail"]);
		$order->set("details.VPSTxId",			$response["VPSTxId"]);
		$order->set("details.securityKey",		$response["SecurityKey"]);
		$order->set("details.avscv2",			$response["AVSCV2"]);
		$order->set("details.addressResult",	$response["AddressResult"]);
		$order->set("details.posCodeResult",	$response["PostCodeResult"]);
		$order->set("details.cv2Result",		$response["CV2Result"]);

		$detailLabels = array(
			"status"		=> "Status",
			"statusDetail"	=> "Status Detail",
			"VPSTxId"		=> "VPSTxId",
			"securityKey"	=> "Security Key",
			"avscv2"		=> "AVSCV2",
			"addressResult"	=> "Address Result",
			"posCodeResult"	=> "PostCode Result",
			"cv2Result"		=> "CV2 Result",
		);

		$order->set("details.error", "(".$response["Status"].") ".$response["StatusDetail"]);
		$order->set("status", $_this->get("failedStatus"));
	} else {
		$order->set("details.status",		$response["Status"]);
		$order->set("details.statusDetail",	$response["StatusDetail"]);

		$detailLabels = array(
			"status"		=> "Status",
			"statusDetail"	=> "Status Detail"
		);

		$order->set("details.error", $response["StatusDetail"]);
		$order->set("status", $_this->get("failedStatus"));
	}

	$order->set("detailLabels", $detailLabels);
	$order->update();
}

function func_ProtxDirect_action_return(&$_this, &$order, $payment)
{
	$trxData = array(
		"MD"	=> $_this->get("MD"),
		"PaRes"	=> $_this->get("PaRes")
	);

	$url = $payment->getServiceUrl("callback");
	$request = func_ProtxDirect_prepareTrxData($trxData);
	$response = func_ProtxDirect_sendRequestDirect($_this, $request, $url);

	$detailLabels = array();

	// Process response
	if (in_array($response["Status"], array("OK", "NOTAUTHED", "REJECTED"))) {
		// success
		$order->set("details.status", 			$response["Status"]);
		$order->set("details.statusDetail",		$response["StatusDetail"]);
		$order->set("details.VPSTxId",			$response["VPSTxId"]);
		$order->set("details.securityKey",		$response["SecurityKey"]);
		$order->set("details.avscv2",			$response["AVSCV2"]);
		$order->set("details.addressResult",	$response["AddressResult"]);
		$order->set("details.posCodeResult",	$response["PostCodeResult"]);
		$order->set("details.cv2Result",		$response["CV2Result"]);
		$order->set("details.3DSecureStatus",	$response["3DSecureStatus"]);

		$detailLabels = array(
			"status"		=> "Status",
			"statusDetail"	=> "Status Detail",
			"VPSTxId"		=> "VPSTxId",
			"securityKey"	=> "Security Key",
			"avscv2"		=> "AVSCV2",
			"addressResult"	=> "Address Result",
			"posCodeResult"	=> "PostCode Result",
			"cv2Result"		=> "CV2 Result",
			"3DSecureStatus"	=> "3DSecureStatus",
		);

		if ($response["Status"] == "OK") {
			$order->set("details.TxAuthNo", $response["TxAuthNo"]);
			$order->set("details.cavv", $response["CAVV"]);

			$detailLabels["cavv"] = "CAVV";
			$detailLabels["TxAuthNo"] = "TxAuthNo";

			if ($response["3DSecureStatus"] == "OK") {
				$order->set("status", $payment->get("sucessedStatus"));
			} else {
				$order->set("status", $payment->get("queuedStatus"));
			}
		} else {
			$order->set("status", $payment->get("failedStatus"));
			$order->set("details.error", "(".$response["Status"].") ".$response["StatusDetail"]);
		}

	} else {
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
		$order->set("status", $payment->get("failedStatus"));
	}

	$order->set("detailLabels", $detailLabels);
	$order->update();
}


///////////////////////////////////////// Transport //////////////////////////////
function func_ProtxDirect_sendRequestDirect(&$payment, $post, $url=null)
{
	if (is_null($url)) {
		$url = $payment->get("serviceUrl");
	}

	$https =& func_new('HTTPS');
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
		$response = "VPSProtocol=2.22\nStatus=INVALID\nStatusDetail=".$https->error."";
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
function func_ProtxDirect_getState(&$profile, $field, $customField)
{
    $stateName = "";
    $state =& func_new("State");
    if ($state->find("state_id='".$profile->get($field)."'")) {
        $stateName = $state->get('state');
    } else { // state not found
        $stateName = &$profile->get($customField);
    }

    return $stateName;
}

function func_ProtxDirect_getBasket($order)
{
	$basket = array(count($order->get("items")));

	foreach ($order->get("items") as $item) {
		$basket[] = func_ProtxDirect_encodeTrxValue($item->get("name")).":".sprintf("%.02f", $item->get("amount")).":".sprintf("%.02f", $item->get("price")).":::".sprintf("%.02f", $item->get("taxableTotal"));
	}

	return implode(":", $basket);
}

function func_ProtxDirect_encodeTrxValue($value)
{
	return urlencode($value);
}

function func_ProtxDirect_prepareTrxData($trxData)
{
	$ignore = array("Basket", "MD");

	foreach ($trxData as $key=>$val) {
		if (in_array($key, $ignore)) {
			continue;
		}
		$trxData[$key] = func_ProtxDirect_encodeTrxValue($val);
	}

	return $trxData;
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
