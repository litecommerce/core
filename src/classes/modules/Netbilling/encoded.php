<?php

#
# $Id: encoded.php,v 1.5 2008/03/07 13:01:13 osipov Exp $
#
# Netbilling.com CC processing module using Direct Mode 3.1
#

function func_Netbilling_processor_process(&$cart, &$_this, $debug = false)
{
	// license check
	check_module_license("Netbilling");

	$module_params = $_this->get('params');

	$REMOTE_ADDR = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
	$USER_AGENT = $_SERVER["HTTP_USER_AGENT"];

	$netbilling_account_id = $module_params["account"];
	$netbilling_tran_type = (($module_params["tran_type"] == "S") ? "S" : "A");

	$profile = $cart->get("profile");

	$post_data = array(
		"account_id"	=> $netbilling_account_id,
		"tran_type"	=> $netbilling_tran_type,
//		"trans_id"	=> substr(uniqid(time()), 0, 12),

		// payment information
		"pay_type"	=> "C",
		"amount"	=> sprintf("%.02f", $cart->get("total")),
		"card_number"	=> $_this->cc_info["cc_number"],
		"card_expire"	=> $_this->cc_info["cc_date"],
		"card_cvv2"	=> $_this->cc_info["cc_cvv2"],

		// customer information
		"bill_name1"	=> $profile->get("billing_firstname"),
		"bill_name2"	=> $profile->get("billing_lastname"),
		"bill_street"	=> $profile->get("billing_address"),
		"bill_city"	=> $profile->get("billing_city"),
		"bill_state"	=> func_Netbilling_getState($profile, "billing_state", "billing_custom_state"),
		"bill_zip"	=> $profile->get("billing_zipcode"),
		"bill_country"	=> $profile->get("billing_country"),
		"cust_email"	=> $profile->get("login"),
		"cust_phone"	=> $profile->get("billing_phone"),
		"cust_ip"	=> $REMOTE_ADDR,
		"cust_browser"	=> $USER_AGENT,

		// purchase information
		"site_tag"	=> $module_params["site_tag"],
		"description"	=> "Shopping cart #".$cart->get("order_id"),
		"user_data"	=> "Order-Number: ".$cart->get("order_id"),

		// shipping information
		"ship_name1"	=> $profile->get("shipping_firstname"),
		"ship_name2"	=> $profile->get("shipping_lastname"),
		"ship_street"	=> $profile->get("shipping_address"),
		"ship_city"	=> $profile->get("shipping_city"),
		"ship_state"	=> func_Netbilling_getState($profile, "shipping_state", "shipping_custom_state"),
		"ship_zip"	=> $profile->get("shipping_zipcode"),
		"ship_country"	=> $profile->get("shipping_country"),

		// level 2 information
		"tax_amount"	=> sprintf("%.02f", $cart->get("tax")),
		"ship_amount"	=> sprintf("%.02f", $cart->get("shipping_cost")),
		"purch_order"	=> $cart->get("order_id")
	);

	$_this->initRequest($cart, $post_data);

if ($debug) {
// write request log
$_this->xlite->logger->log("Request:\n".var_export($post_data, true));
}

	$post = array();
	foreach ($post_data as $key=>$value) {
		$post[] = "$key=".($value);
	}

	list($a,$return)=func_https_request("POST","https://secure.netbilling.com:1402/gw/sas/direct3.1",$post,'&');

	$response = array();
	$strings = explode("&", $return);
	foreach ($strings as $str_node) {
		list($key, $value) = explode("=", $str_node);
		$response[$key] = $value;
	}

if ($debug) {
// write response log
$_this->xlite->logger->log("Response:\n".var_export($response, true)."\n");
}

	$status_code = $response["status_code"];

	$detailLabels = $cart->get("detailLabels");
	$detailLabels["status_code"] = "Status code";
	$detailLabels["auth_msg"] = "Auth message";
	$detailLabels["auth_date"] = "Auth date";
	$detailLabels["result"] = "Result";

	$cart->set("details.status_code", $status_code);

	if (in_array($status_code, array("1", "T", "I"))) {
		// success
		$result = "";
		switch ($status_code) {
			case "1":
				$result = "Successful monetary transaction";
				$cart->set("status", $_this->get("sucessedStatus"));
			break;
			case "T":
				$result = "Successful auth only transaction";
				$cart->set("status", $_this->get("queuedStatus"));
			break;
			case "I":
				$result = "Pending transaction, such as an unfunded check payment";
				$cart->set("status", $_this->get("queuedStatus"));
			break;
		}

		$cart->set("details.auth_code", $response["auth_code"]);
		$cart->set("details.result", $result);

		$detailLabels["auth_code"] = "Auth code";
		$detailLabels["result"] = "Result";

		if ($response["settle_amount"]) {
			$cart->set("details.settle_amount", $response["settle_amount"]);
			$detailLabels["settle_amount"] = "Settle amount";
		}

		if ($response["settle_currency"]) {
			$cart->set("details.settle_currency", $response["settle_currency"]);
			$detailLabels["settle_currency"] = "Settle currency";
		}

		unset($detailLabels["error"]);
		$cart->set("setails.error", null);
	} else {
		// failed
		$cart->set("status", $_this->get("failedStatus"));

		$error = "";
		switch ($status_code) {
			case "0": $error = "Failed transaction. Consult ".$response["auth_msg"]." and ".$response["reason_code2"]; break;
			case "F": $error = "Settlement failure or returned NBCheck transaction. Not applicable at authorization time"; break;
			case "D": $error = "Duplicate transaction. The ".$response["trans_id"]." of the original transaction will be returned"; break;
			default: $error = "Unknown failed response."; break;
		}

		$cart->set("details.error", $error);
		$detailLabels["error"] = "Error";
	}

	if ($status_code != "D") {
		$cart->set("details.trans_id", $response["trans_id"]);
		$detailLabels["trans_id"] = "Transaction ID";
	}

	$cart->set("details.avs_code", $_this->getAVSString($response["avs_code"]));
	$cart->set("details.cvv2_code", $_this->getCVV2String($response["cvv2_code"]));
	$cart->set("details.auth_msg", $response["auth_msg"]);
	$cart->set("details.auth_date", $response["auth_date"]);

	$detailLabels["avs_code"] = "AVS code";
	$detailLabels["cvv2_code"] = "CVV2 code";

	$cart->set("detailLabels", $detailLabels);
	$cart->update();

}

function func_Netbilling_getState(&$profile, $field, $customField)
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

?>
