<?php
	function func_BankOfAmerica_process(&$lite_cart, &$paymentMethod, $debug = false)
	{
		// license check
		check_module_license("BankOfAmerica");
		
		//global $options;
		/*
		$http_host = $lite_cartoptions["host_details"]["http_host"];
		$web_dir = $options["host_details"]["web_dir"];
		*/
		$http_host = $lite_cart->xlite->get("options.host_details.http_host");
		$web_dir = $lite_cart->xlite->get("options.host_details.web_dir");
		//
		// *********************** PREPARE ************************
		//

		// Save original Lite values into the following variables:
		$lite_config = $GLOBALS ["config"];

		// Store values for X-Cart $config variable here
		$config = array ();
		$config ["Company"]["orders_department"] = $lite_config->Company->orders_department;

		// Store values for X-Cart $cart variable here
		$cart = array ();
		$cart["total_cost"] = $lite_cart->get ("total");

		// Fill parameters fields here
		$module_params = $paymentMethod->get("params");
		if ($debug) {
			echo "module_params:<pre>"; print_r($module_params); echo "</pre><br>";
		}

		// Store values for X-Cart $userinfo variable here
		$userinfo = array ();
		$userinfo ["firstname"] = $lite_cart->get("profile.billing_firstname");
		$userinfo ["lastname"] = $lite_cart->get("profile.billing_lastname");
		$userinfo ["b_city"] = $lite_cart->get("profile.billing_city");
		$userinfo ["b_address"] = $lite_cart->get("profile.billing_address");
		$userinfo ["b_state"] = $lite_cart->get("profile.billingState.code");
		$userinfo ["b_zipcode"] = $lite_cart->get("profile.billing_zipcode");
		$userinfo ["b_country"] = $lite_cart->get("profile.billing_country");
		$userinfo ["phone"] = $lite_cart->get("profile.billing_phone");
		$userinfo ["email"] = $lite_cart->get("profile.login");
		$userinfo ["card_number"] = $paymentMethod->cc_info["cc_number"];
		$userinfo ["card_expire"] = $paymentMethod->cc_info["cc_date"];
		$userinfo ["card_cvv2"] = $paymentMethod->cc_info["cc_cvv2"];
		$userinfo ["card_name"] = $paymentMethod->cc_info["cc_name"];

		if ($debug) {
			echo "userinfo:<pre>"; print_r($userinfo); echo "</pre><br>";
		}

		// Count payment attempts
		$conn_attempts = $lite_cart->get("details.connectionAttempts");
		if (is_null($conn_attempts)) {
			$conn_attempts = 1;
		} else {
			$conn_attempts++;
		}
		$lite_cart->set("details.connectionAttempts", $conn_attempts);
		$lite_cart->set("detailLabels.connectionAttempts", "Connection attempts");

		if ($debug) echo "Connection attempt: $conn_attempts<br>";

		$REMOTE_ADDR = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";

		if ($debug) echo "Remote addr: $REMOTE_ADDR<br>";

		// Misc. values
		$secure_oid = array ($lite_cart->get ("order_id"), $conn_attempts);

		if ($debug) {
			echo "secure_oid:<pre>"; print_r($secure_oid); echo "<pre><br>";
		}

		// for the post_func
		$GLOBALS["debug"] = $debug;

		//
		// *************** X-CART Verisign payment processor code ***************
		//

?><?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2003 Ruslan R. Fazliev <rrf@rrf.ru>                      |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
| FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
|                                                                             |
| The Initial Developer of the Original Code is Ruslan R. Fazliev             |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2003           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id$
#

$post = "";
$post[] = "ecom_billto_online_email=".$userinfo[email]; 
$post[] = "ecom_billto_postal_city=".$userinfo[b_city]; 
$post[] = "ecom_billto_postal_countrycode=".$userinfo[b_country]; 
$post[] = "ecom_billto_postal_name_first=".$userinfo[firstname]; 
$post[] = "ecom_billto_postal_name_last=".$userinfo[lastname]; 
$post[] = "ecom_billto_postal_postalcode=".$userinfo[b_zipcode]; 
$post[] = "ecom_billto_postal_stateprov=".$userinfo[b_state]; 
$post[] = "ecom_billto_postal_street_line1=".$userinfo[b_address]; 
$post[] = "ecom_billto_telecom_phone_number=".$userinfo[phone]; 
$post[] = "ecom_payment_card_expdate_month=".(0+substr($userinfo["card_expire"],0,2)); 
$post[] = "ecom_payment_card_expdate_year=".(2000+substr($userinfo["card_expire"],2,2)); 
$post[] = "ecom_payment_card_name=".$userinfo["card_name"]; 
$post[] = "ecom_payment_card_number=".$userinfo[card_number]; 
$post[] = "ioc_merchant_id=".$module_params ["param01"]; 
$post[] = "ioc_merchant_order_id=".$module_params ["param03"].join("-",$secure_oid);
$post[] = "ioc_order_shopper_id=".$userinfo["login"]; 
$post[] = "ioc_order_total_amount=".$cart["total_cost"]; 
$post[] = "ioc_cvv_indicator=".$module_params ["param02"];; 
$post[] = "ecom_payment_card_verification=".$userinfo["card_cvv2"]; 
$post[] = "ioc_auto_settle_flag=Y";

list($a,$return) = ref_func_https_request("POST","https://cart.bamart.com:443/payment.mart",$post,"http://".$http_host."/".$web_dir."/cart.php"); 
//$return="m=psagroup<BR>IOC_merchant_order_id=xcart576<BR>IOC_merchant_shopper_id=<BR>IOC_shopper_id=521JMN4GW9D18JNCW549XKB4URWX2BG8<BR>IOC_response_code=8<BR>Ecom_transaction_complete=TRUE<BR>IOC_pcard_response=N<BR>Ecom_Payment_Card_Verification_RC=P<BR>IOC_order_total_amount=41.95<BR>IOC_reject_description=Unable to obtain a valid credit card authorization at this time.<BR>IOC_AVS_result=0<BR>ioc_order_shopper_id=sdg<BR>";

$m = split("<BR>",$return);
foreach($m as $k => $v)
{
	list($a,$b) = split("=",trim($v),2);
	if($a)
		$ret[strtolower($a)] = $b;
}

#print $return;
#print "<hr>";
#print_r($ret);


$staerr = array(
	"-1" => "Authorization system not responding. Order accepted in Faith mode.",
	"1" => "Authorization system not responding. Please retry transaction.",
	"2" => "Authorization declined. Please retry with different credit card.",
	"3" => "No response from issuing institution. Order not accepted. Please retry.",
	"4" => "Authorization declined. Invalid credit card. Please retry with different credit card.",
	"5" => "Authorization declined. Invalid amount. Please retry.",
	"6" => "Authorization declined. Expired credit card. Please retry with different credit card.",
	"7" => "Authorization declined. Invalid transaction. Please retry with different credit card.",
	"8" => "Received unexpected reply. Order not accepted. Please retry.",
	"9" => "Authorization declined. Duplicate transaction.",
	"10" => "Other issue. Order not accepted. Please retry."
);

$avserr = array(
	"0" => "No data",
	"1" => "No match",
	"2" => "Address match only",
	"3" => "Zip code match only",
	"4" => "Exact match"
);

$cvverr = array(
	"M" => "CVV Matched.",
	"N" => "CVV No Match.",
	"P" => "Not Processed.",
	"S" => "CVV is on the card, but the shopper has indicated that CVV is not present.",
	"U" => "Issuer is not VISA certified for CVV and has not provided Visa encryption keys or both."
);

if(!empty($ret[ioc_invoice_number]) && !empty($ret[ioc_settlement_amount]) && $ret[ioc_response_code]==0)
{
	$bill_output[code] = 1;
	$bill_output[billmes] = "(Authorization Code: ".$ret[ioc_authorization_code]."; OrderID: ".$ret[ioc_order_id]."; IOC_invoice_number: ".$ret[ioc_invoice_number].")";
}
else
{
	$bill_output[code] = 2;
	if (empty($ret[ioc_reject_description]))
		$bill_output[billmes] = empty($staerr[$ret[ioc_response_code]]) ? "Response code: ".$ret[ioc_response_code] : $staerr[$ret[ioc_response_code]];
	else 
		$bill_output[billmes] = $ret[ioc_reject_description]." (".$ret[ioc_response_code].")";
}

if(isset($ret[ioc_avs_result]))
	$bill_output[avsmes] = empty($avserr[$ret[ioc_avs_result]]) ? "AVS Response code: ".$ret[ioc_avs_result] : $avserr[$ret[ioc_avs_result]];
if(!empty($ret[ecom_payment_card_verification_rc]))
	$bill_output[cvvmes] = empty($cvverr[$ret[ecom_payment_card_verification_rc]]) ? "CVV Response code: ".$ret[ecom_payment_card_verification_rc] : $cvverr[$ret[ecom_payment_card_verification_rc]];

#print_r($bill_output);
#print_r($ret);
#print_r($return);
#exit;

?><?php

		//
		// *********************** POST PROCESS ***********************
		//

		if ($debug) {
			echo "bill_output:<pre>"; print_r($bill_output); echo "</pre><br>";
		}

		$status = "I";

		if ($bill_output["code"] != 1) {
			$error = $bill_output ["billmes"];
			$status = "F";
		} else {
			// success
			$error = "";
			$status = "P";
		}

		if ($bill_output ["cvvmes"]) {
			$lite_cart->set("details.cvvMessage", $bill_output ["cvvmes"]);
			$lite_cart->set("detailLabels.cvvMessage", "CVV message");
		} else {
			$lite_cart->set("details.cvvMessage", null);
		}
		
		if ($bill_output ["avsmes"]) {
			$lite_cart->set("details.avsMessage", $bill_output ["avsmes"]);
			$lite_cart->set("detailLabels.avsMessage", "AVS message");
		} else {
			$lite_cart->set("details.avsMessage", null);
		}

		$lite_cart->set("details.error", $error);
		$lite_cart->set("status", $status);
		$lite_cart->update();
	}
?>
