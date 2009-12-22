<?php

/*
* Hidden methods
*
* @version $Id$
*/

    function func_PayFlowPro_process(&$lite_cart, &$paymentMethod, $debug = false)
    {
        //
        // *********************** PREPARE ************************
        //

        // Save original Lite values into the following variables:

        // Store values for X-Cart $config variable here

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
        $userinfo ["b_state"] = $lite_cart->get("profile.billingState.state");
        $userinfo ["b_zipcode"] = $lite_cart->get("profile.billing_zipcode");
        $userinfo ["b_country"] = $lite_cart->get("profile.billingCountry.country");
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
		$cart_details = $lite_cart->get('details');
		$cart_labels = $lite_cart->get('detailLabels');
		$adv_labels = array(
			"connectionAttempts" => "Connection attempts",
			"cvvMessage" => "CVV message",
			"avsMessage" => "AVS message",
//			"authCode"	=> "Auth Code",
//			"pnref"		=> "PNREF"
		);
		$cart_labels = array_merge($cart_labels, $adv_labels);
        $conn_attempts = (int) $cart_details["connectionAttempts"];
        if (is_null($conn_attempts)) {
            $conn_attempts = 1;
        } else {
            $conn_attempts++;
        }
		$cart_details["connectionAttempts"] = $conn_attempts;

        if ($debug) echo "Connection attempt: $conn_attempts<br>";

        $REMOTE_ADDR = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";

        if ($debug) echo "Remote addr: $REMOTE_ADDR<br>";

        // Misc. values
        $secure_oid = array ($lite_cart->get ("order_id"), $conn_attempts);

        if ($debug) {
            echo "secure_oid:<pre>"; print_r($secure_oid); echo "<pre><br>";
        }

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

$vs_user = $module_params["param01"];
$vs_vendor = $module_params["param02"];
$vs_partner = $module_params["param03"];
$vs_pwd = $module_params["param04"];
$currency = $module_params["currency"];
// transaction type
$vs_trx_type = (isset($module_params["param07"])) ? $module_params["param07"] : "S";

$vs_host = ($module_params["testmode"] != "N") ? "pilot-payflowpro.paypal.com" : "payflowpro.paypal.com";
$testlive_prefix = ($module_params["testmode"] != "N") ? "test" : "live";

//if(!test_payflow())
//	func_header_location($xcart_web_dir.DIR_CUSTOMER."/error_message.php?error_ccprocessor_notfound");

$post = "";
$post[] = "USER=".$vs_user;
$post[] = "VENDOR=".$vs_vendor;
$post[] = "PARTNER=".$vs_partner;
$post[] = "PWD=".$vs_pwd;
$post[] = "TRXTYPE=".$vs_trx_type;
$post[] = "TENDER=C";
$post[] = "ACCT=".$userinfo["card_number"];
$post[] = "EXPDATE=".$userinfo["card_expire"];
$post[] = "NAME=".$userinfo ["card_name"];
$post[] = "AMT=".$cart["total_cost"];
$post[] = "CVV2=".$userinfo["card_cvv2"];
$post[] = "CURRENCY=".$currency;
$post[] = "STREET=".$userinfo["b_address"];
$post[] = "ZIP=".$userinfo["b_zipcode"];
$post[] = "FIRSTNAME=".$userinfo["firstname"];
$post[] = "LASTNAME=".$userinfo["lastname"];
$post[] = "CITY=".$userinfo["b_city"];
$post[] = "STATE=".$userinfo["b_state"];
$post[] = "EMAIL=".$userinfo["email"];
$post[] = "PONUM=".$testlive_prefix.join("-",$secure_oid);

if ($debug || $module_params["testmode"] == "Y") {
    $post[] = "VERBOSITY=MEDIUM";
}

$post = $paymentMethod->initRequest($post);

$post = implode("&", $post);


// Get HTTPS object
$https =& PayFlowPro_getHTTPS_Object();

$https->addHeader("Content-Type", "text/namevalue");
$https->addHeader("X-VPS-REQUEST-ID", md5(uniqid(time())));
$https->addHeader("X-VPS-CLIENT-TIMEOUT", "30");
$https->addHeader("X-VPS-VIT-CLIENT-CERTIFICATION-ID", "7894b92104f04ffb4f38a8236ca48db3");
$https->addHeader("X-VPS-VIT-Integration-Product", "LiteCommerce");
$https->data = $post;
$https->url = "https://" . $vs_host . "/transaction";
$https->urlencoded = true;

if ($debug) {
        echo "host:"; echo($https->url); echo "<br>";
        echo "headers:<pre>"; print_r($https->getHeaders()); echo "</pre><br>";
        echo "post:<pre>"; print_r($https->data); echo "</pre><br>";
}

$https->request();

if ($https->error) {
	$return = $https->error;
} else {
	$return = $https->response;
}

if ($debug) {
            echo "return:<pre>"; print_r($return); echo "</pre><br>";
}

# Check AVS result
$a = array();
$erravs = array(
"Y" => "match",
"N" => "not match"
);

if(preg_match("/IAVS=([YNX])/",$return,$out))
	$a[] = "iAVS ".(($out[1]=="X") ? ("cannot be determined") : ( ($out[1]=="Y") ? ("international") : ("USA") ));

if(preg_match("/AVSADDR=([YNX])/",$return,$out))
	$a[] = ($out[1] == "X") ? "Bank does not support AVS" : "Street address ".$erravs[$out[1]];

if(preg_match("/AVSZIP=([YNX])/",$return,$out))
	$a[] = ($out[1] == "X") ? "Bank does not support AVS" : "ZIP code ".$erravs[$out[1]];

$bill_output[avsmes] = join(" :: ",$a);

# Check result
if(preg_match("/PNREF=(.*)&/U",$return,$out))
	$pnref = $out[1];

$err = array(
"-1" => "Failed to connect to host",
"-2" => "Failed to resolve hostname",
"-5" => "Failed to initialize SSL context",
"-6" => "Parameter list format error: & in name",
"-7" => "Parameter list format error: invalid [ ] name length clause",
"-8" => "SSL failed to connect to host",
"-9" => "SSL read failed",
"-10" => "SSL write failed",
"-11" => "Proxy authorization failed",
"-12" => "Timeout waiting for response",
"-13" => "Select failure",
"-14" => "Too many connections",
"-15" => "Failed to set socket options",
"-20" => "Proxy read failed",
"-21" => "Proxy write failed",
"-22" => "Failed to initialize SSL certificate",
"-23" => "Host address not specified",
"-24" => "Invalid transaction type",
"-25" => "Failed to create a socket",
"-26" => "Failed to initialize socket layer",
"-27" => "Parameter list format error: invalid [ ] name length clause",
"-28" => "Parameter list format error: name",
"-29" => "Failed to initialize SSL connection",
"-30" => "Invalid timeout value",
"-31" => "The certificate chain did not validate, no local certificate found",
"-32" => "The certificate chain did not validate, common name did not match URL",
"-99" => "Out of memory",
"1" => "User authentication failed",
"2" => "Invalid tender type. Your merchant bank account does not support the following credit card type that was submitted.",
"3" => "Invalid transaction type. Transaction type is not appropriate for this transaction. For example, you cannot credit an authorization-only transaction.",
"4" => "Invalid amount format",
"5" => "Invalid merchant information. Processor does not recognize your merchant account information. Contact your bank account acquirer to resolve this problem.",
"7" => "Field format error. Invalid information entered. See RESPMSG.",
"8" => "Not a transaction server",
"9" => "Too many parameters or invalid stream",
"10" => "Too many line items",
"11" => "Client time-out waiting for response",
"12" => "Declined. Check the credit card number and transaction information to make sure they were entered correctly. If this does not resolve the problem, have the customer call the credit card issuer to resolve.",
"13" => "Referral. Transaction was declined but could be approved with a verbal authorization from the bank that issued the card. Submit a manual Voice Authorization transaction and enter the verbal auth code.",
"19" => "Original transaction ID not found. The transaction ID you entered for this transaction is not valid. See RESPMSG.",
"20" => "Cannot find the customer reference number",
"22" => "Invalid ABA number",
"23" => "Invalid account number. Check credit card number and re-submit.",
"24" => "Invalid expiration date. Check and re-submit.",
"25" => "Invalid Host Mapping. Transaction type not mapped to this host",
"26" => "Invalid vendor account",
"27" => "Insufficient partner permissions",
"28" => "Insufficient user permissions",
"29" => "Invalid XML document. This could be caused by an unrecognized XML tag or a bad XML format that cannot be parsed by the system.",
"30" => "Duplicate transaction",
"31" => "Error in adding the recurring profile",
"32" => "Error in modifying the recurring profile",
"33" => "Error in canceling the recurring profile",
"34" => "Error in forcing the recurring profile",
"35" => "Error in reactivating the recurring profile",
"36" => "OLTP Transaction failed",
"50" => "Insufficient funds available in account",
"99" => "General error. See RESPMSG.",
"100" => "Transaction type not supported by host",
"101" => "Time-out value too small",
"102" => "Processor not available",
"103" => "Error reading response from host",
"104" => "Timeout waiting for processor response. Try your transaction again.",
"105" => "Credit error. Make sure you have not already credited this transaction, or that this transaction ID is for a creditable transaction. (For example, you cannot credit an authorization.)",
"106" => "Host not available",
"107" => "Duplicate suppression time-out",
"108" => "Void error. See RESPMSG. Make sure the transaction ID entered has not already been voided. If not, then look at the Transaction Detail screen for this transaction to see if it has settled. (The Batch field is set to a number greater than zero if the transaction has been settled). If the transaction has already settled, your only recourse is a reversal (credit a payment or submit a payment for a credit).",
"109" => "Time-out waiting for host response",
"111" => "Capture error. Only authorization transactions can be captured.",
"112" => "Failed AVS check. Address and ZIP code do not match. An authorization may still exist on the cardholder's account.",
"113" => "Cannot exceed sales cap. For ACH transactions only.",
"113" => "Merchant sale total will exceed the cap with current transaction",
"114" => "Card Security Code (CSC) Mismatch. An authorization may still exist on the cardholder's account.",
"115" => "System busy, try again later",
"116" => "VPS Internal error - Failed to lock terminal number",
"117" => "Failed merchant rule check. An attempt was made to submit a transaction that failed to meet the security settings specified on the PayFlowPro Manager Security Settings page. See PayFlowPro Manager User's Guide.",
"118" => "Invalid keywords found in string fields",
"1000" => "Generic host error. See RESPMSG. This is a generic message returned by your credit card processor. The message itself will contain more information describing the error.",
);

if(preg_match("/RESULT=(.*)&/U",$return,$out))
	$result = $out[1];

if(preg_match("/RESPMSG=(.*)&/U",$return,$out))
	$respmsg = $out[1];


if(preg_match("/CVV2MATCH=([YNX])/",$return,$out))
	$bill_output[cvvmes].= (($out[1]=="X") ? ("Not Supported") : ( ($out[1]=="Y") ? ("Match") : ("Not Match") ));

$auth_code = "";
if($result == "0")
{
	$bill_output[code] = 1;

	if(preg_match("/AUTHCODE=(.*)&/U",$return,$out))
		$auth_code = $out[1];
		$bill_output[billmes] = "(AuthCode: ".$auth_code.")";
}
else
{
	$bill_output[code] = 2;
	$bill_output[billmes] = (empty($err[$result]) ? "Result: ".$return . "; RESPMSG: " . $respmsg : "RESPMSG: " . $respmsg . "; Error: " . $err[$result]);
}

if(!empty($bill_output[billmes]))
	$bill_output[billmes].= " (PNREF = ".$pnref.")";


#print "<pre>";
#print_r($bill_output);
#print $return;
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

			if ($bill_output ["cvvmes"])
				$cart_details["cvvMessage"] = $bill_output ["cvvmes"];
			else
				$cart_details["cvvMessage"] = null;

			if ($bill_output ["avsmes"])
				$cart_details["avsMessage"] = $bill_output ["avsmes"];
			else
				$cart_details["avsMessage"] = null;

			$cart_details["authCode"] = $auth_code;
			$cart_labels["authCode"]  = "Auth Code";
        }

		$cart_details["pnref"] = $pnref;
		$cart_labels["pnref"] = "PNREF";

        if ($bill_output ["cvvmes"])
			$cart_details["cvvMessage"] = $bill_output ["cvvmes"];
        else
			$cart_details["cvvMessage"] = null;

        if ($bill_output ["avsmes"])
			$cart_details["avsMessage"] = $bill_output ["avsmes"];
        else
			$cart_details["avsMessage"] = null;

		$cart_details["error"] = $error;
		
        $full_response = array();
    	foreach ($bill_output as $k=>$v) {
    		$full_response[] = "\"$k\": $v";
    	}
        $full_response = implode(", ", $full_response);
        $cart_details["full_response"] = $full_response;
        $cart_labels["full_response"] = "Response";

		$lite_cart->set("details", $cart_details);
		$lite_cart->set("detailLabels", $cart_labels);
        $lite_cart->set("status", $status);
        $lite_cart->update();
    }

function PayFlowPro_getHTTPS_Object()
{
	$obj =& func_new("HTTPS");
	if (method_exists($obj, "getHeaders")) {
		return $obj;
	}

	$obj = null;

	return func_new("HTTPS_PayFlowPro");
}

?>
