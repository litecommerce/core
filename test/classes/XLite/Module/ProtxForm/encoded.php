<?php

/*
* Hiden methods
*
* @version $Id$
//*/

if (!defined('PROTX_FORM_DEBUG_LOG')) {
	define('PROTX_FORM_DEBUG_LOG', 0);
}

function func_ProtxForm_compileInfoCrypt($_this, $order)
{
	$vendorTxCode = substr($_this->getComplex('params.order_prefix'), 0, 30)."_".$order->get("order_id")."";
	$vendorTxCode = preg_replace("/[^\d\w_]/", "_", $vendorTxCode);
	$currency = (($_this->getComplex('params.currency')) ? $_this->getComplex('params.currency') : "USD");

	$profile = $order->get("profile");

	$trxData = array(
		"VendorTxCode"		=> $vendorTxCode,
		"Amount"			=> sprintf("%.02f", $order->get("total")),
		"Currency"			=> $currency,
		"Description"		=> "Shopping cart #".$order->get("order_id"),
		"SuccessURL"		=> $_this->getSuccessUrl($order->get("order_id")),
		"FailureURL"		=> $_this->getFailureUrl($order->get("order_id")),
		"CustomerName"		=> $profile->get("shipping_firstname")." ".$profile->get("shipping_lastname"),
		"CustomerEMail"		=> $profile->get("login"),
		"eMailMessage"		=> $_this->getComplex('params.eMailMessage'),
		"BillingAddress"	=> $profile->get("billing_address")." ".$profile->get("billing_city")." ".func_ProtxForm_getState($profile, "billing_state", "billing_custom_state")." ".$profile->get("billing_country"),
		"BillingPostCode"	=> $profile->get("billing_zipcode"),
		"DeliveryAddress"	=> $profile->get("shipping_address")." ".$profile->get("shipping_city")." ".func_ProtxForm_getState($profile, "shipping_state", "shipping_custom_state")." ".$profile->get("shipping_country"),
		"DeliveryPostCode"	=> $profile->get("shipping_zipcode"),
		"ContactNumber"		=> $profile->get("billing_phone"),
		"ContactFax"		=> $profile->get("billing_fax"),
		"Basket"			=> func_ProtxForm_getBasket($order),
		"AllowGiftAid"		=> "0",
		"ApplyAVSCV2"		=> $_this->getComplex('params.ApplyAVSCV2'),
		"Apply3DSecure"		=> $_this->getComplex('params.Apply3DSecure')
	);

if (PROTX_FORM_DEBUG_LOG) {
$_this->xlite->logger->log("Protx VSP Form crypt:".var_export($trxData, true));
}

	$trxData = func_ProtxForm_prepareTrxData($trxData);
	$crypt = base64_encode(func_ProtxForm_simpleXor($trxData, $_this->getComplex('params.xor_password')));

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

	$response = func_ProtxForm_simpleXor(base64_decode($crypt), $paymentMethod->getComplex('params.xor_password'));

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
	preg_match("/_([\d]+)$/", $responseArray["VendorTxCode"], $out);
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

	if ($responseArray["Status"] == "OK") {
		// success
		$order->set("details.Status", $responseArray["Status"]);
		$order->set("details.StatusDetail", $responseArray["StatusDetail"]);
		$order->set("details.VPSTxId", $responseArray["VPSTxId"]);
		$order->set("details.TxAuthNo", $responseArray["TxAuthNo"]);
		$order->set("details.AVSCV2", $responseArray["AVSCV2"]);
		$order->set("details.AddressResult", $responseArray["AddressResult"]);
		$order->set("details.PostCodeResult", $responseArray["PostCodeResult"]);
		$order->set("details.CV2Result", $responseArray["CV2Result"]);
		$order->set("details.3DSecureStatus", $responseArray["3DSecureStatus"]);
		$order->set("details.CAVV", $responseArray["CAVV"]);

		$order->set("detailLabels", array(
			"Status"		=> "Status",
			"StatusDetail"	=> "Status Detail",
			"VPSTxId"		=> "VPSTxId",
			"TxAuthNo"		=> "TxAuthNo",
			"AVSCV2"		=> "AVSCV2",
			"AddressResult"	=> "Address Result",
			"PostCodeResult"	=> "PostCode Result",
			"CV2Result"		=> "CV2 Result",
			"3DSecureStatus"	=> "3DSecure Status",
			"CAVV"			=> "CAVV"
		));

		$order->set("status", $paymentMethod->get("sucessedStatus"));
	} else {
		// failed
		$order->set("details.error", $responseArray["StatusDetail"]);
		$order->set("details.Status", $responseArray["Status"]);

		$order->set("detailLabels", array(
			"Status"	=> "Status",
			"error"		=> "Error"
		));

		$order->set("status", $paymentMethod->get("failedStatus"));
	}

	$order->update();

	return true;
}


/////////////////////////////////////////// Helper //////////////////////////////////
function func_ProtxForm_getState($profile, $field, $customField)
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

function func_ProtxForm_getBasket($order)
{
	$basket = array(count($order->get("items")));

	foreach ($order->get("items") as $item) {
		$basket[] = func_ProtxForm_encodeTrxValue($item->get("name")).":".sprintf("%.02f", $item->get("amount")).":".sprintf("%.02f", $item->get("price")).":::".sprintf("%.02f", $item->get("taxableTotal"));
	}

	return implode(":", $basket);
}

function func_ProtxForm_encodeTrxValue($value)
{
	return urlencode($value);
}

function func_ProtxForm_prepareTrxData($trxData)
{
	$ignore = array("Basket", "SuccessURL", "FailureURL", "VendorTxCode");

	$data = array();
	foreach ($trxData as $key=>$value) {
		if (!in_array($key, $ignore)) {
			$value = func_ProtxForm_encodeTrxValue($value);
		}

		$data[] = "$key=$value";
	}

	return implode("&", $data);
}

function func_ProtxForm_simpleXor($InString, $Key) {
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

?>
