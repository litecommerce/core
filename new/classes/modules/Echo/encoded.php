<?php
    function func_Echo_processor_process(&$lite_cart, &$paymentMethod, $debug = false)
    {
        // license check
        check_module_license("Echo");

        //
        // *********************** PREPARE ************************
        //

        // Save original Lite values into the following variables:

        // Store values for X-Cart $config variable here

        // Store values for X-Cart $cart variable here
        $cart = array ();
        $cart["total_cost"] = $paymentMethod->price($lite_cart->get ("total"));

        // Fill parameters fields here
        $module_params = $paymentMethod->get('params');

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
        // *************** X-CART Echo payment processor code ***************
        //

?><?
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

@set_time_limit(100);

$errstatus = array(
	"D" => "Declined", 
	"C" => "Cancelled", 
	"T" => "Timeout waiting for host response", 
	"R" => "Received" 
);

$errmes = array(
	"01" => "Refer to card issuer. The card must be referred to the issuer before the transaction can be approved ",
	"03" => "Invalid merchant number. The merchant submitting the request is not supported by the acquirer. ",
	"04" => "Capture card. The card number has been listed on the Warning Bulletin File for reasons of counterfeit, fraud, or other ",
	"05" => "Do not honor. The transaction was declined by the issuer without definition or reason. ",
	"12" => "Invalid transaction. The transaction request presented is not supported or is not valid for the card number presented. ",
	"13" => "Invalid amount. The amount is below the minimum limit or above the maximum limit the issuer allows for this type of transaction. ",
	"14" => "Invalid card number. The issuer has indicated this card number is not valid. ",
	"15" => "Invalid issuer. The issuer number is not valid. ",
	"30" => "Format error. The transaction was not formatted properly. ",
	"41" => "Lost card. This card has been reported lost. ",
	"43" => "Stolen card. This card has been reported stolen. ",
	"51" => "Over credit limit. The transaction will result in an over credit limit or insufficient funds condition.. ",
	"54" => "Expired card. The card is expired. ",
	"55" => "Incorrect PIN. The cardholder-entered PIN is incorrect. ",
	"57" => "Transaction not permitted (card). This card does not support the type of transaction requested ",
	"58" => "Transaction not permitted (merchant). The merchant's account does not support the type of transaction presented. ",
	"61" => "Daily withdrawal limit exceeded. The cardholder has requested a withdrawal amount in excess of the daily defined maximum. ",
	"62" => "Restricted card. The card has been restricted. ",
	"63" => "Security violation. The card has been restricted. ",
	"65" => "Withdrawal limit exceeded. The allowed number of daily transactions has been exceeded ",
	"75" => "Pin retries exceeded. The allowed number of PIN retries has been exceeded. ",
	"76" => "Invalid \"to\" account. The \"to\" (credit) account specified in the transaction does not exist or is not associated with the card number presented. ",
	"77" => "Invalid \"from\" account. The \"from\" (debit) account specified in the transaction does not exist or is not associated with the card number presented. ",
	"78" => "Invalid account. The \"from\" (debit) or \"to\" (credit) account does not exist or is not associated with the card number presented. ",
	"84" => "Invalid cycle. The authorization life cycle is above or below limits established by the issuer. ",
	"91" => "Issuer not available. The bank is not available to authorize this transaction ",
	"92" => "Unable to route. The transaction does not contain enough information to be routed to the authorizing agency. ",
	"94" => "Duplicate transmission. The host has detected a duplicate transmission. ",
	"96" => "Authorization system error. A system error has occurred or the files required for authorization are not available. ",
	"1000" => "Unrecoverable error. An unrecoverable error has occurred in the ECHONLINE processing. ",
	"1001" => "Account closed. The merchant account has been closed. ",
	"1012" => "Invalid trans code. The host computer received an invalid transaction code. ",
	"1013" => "Invalid term id. The ECHO-ID is invalid. ",
	"1015" => "Invalid card number. The credit card number that was sent to the host computer was invalid ",
	"1016" => "Invalid expiry date. The card has expired or the expiration date was invalid. ",
	"1017" => "Invalid amount. The dollar amount was less than 1.00 or greater than the maximum allowed for this card. ",
	"1021" => "Invalid service. The merchant or card holder is not allowed to perform that kind of transaction ",
	"1024" => "Invalid auth code. The authorization number presented with this transaction is incorrect. (deposit transactions only) ",
	"1025" => "Invalid reference number. The reference number presented with this transaction is incorrect or is not numeric. ",
	"1508" => "Invalid or missing order_type. ",
	"1509" => "The merchant is not approved to submit this order_type. ",
	"1510" => "The merchant is not approved to submit this transaction_type. ",
	"1511" => "Duplicate transaction attempt.  ",
	"1599" => "An system error occurred while validating the transaction input. ",
	"1801" => "Return Code \"A\". Address matches; ZIP does not match. ",
	"1802" => "Return Code \"W\". 9-digit ZIP matches; Address does not match. ",
	"1803" => "Return Code \"Z\". 5-digit ZIP matches; Address does not match. ",
	"1804" => "Return Codes \"U\". Issuer unavailable; cannot verify. ",
	"1805" => "Return Code \"R\". Retry; system is currently unable to process. ",
	"1806" => "Return Code \"S\". or \"G\" Issuer does not support AVS. ",
	"1807" => "Return Code \"N\". Nothing matches. ",
	"1808" => "Return Code \"E\". Invalid AVS only response. ",
	"1809" => "Return Code \"B\". Street address match. Postal code not verified because of incompatible formats. ",
	"1810" => "Return Code \"C\". Street address and Postal code not verified because of incompatible formats. ",
	"1811" => "Return Code \"D\". Street address match and Postal code match. ",
	"1812" => "Return Code \"I\". Address information not verified for international transaction. ",
	"1813" => "Return Code \"M\". Street address match and Postal code match. ",
	"1814" => "Return Code \"P\". Postal code match. Street address not verified because of incompatible formats. ",
	"1897" => "Invalid response. The host returned an invalid response. ",
	"1898" => "Disconnect. The host unexpectedly disconnected. ",
	"1899" => "Timeout. Timeout waiting for host response. ",
	"2071" => "Call VISA. An authorization number from the VISA Voice Center is required to approve this transaction. ",
	"2072" => "Call Master Card. An authorization number from the Master Card Voice Center is required to approve this transaction. ",
	"2073" => "Call Carte Blanche. An authorization number from the Carte Blanche Voice Center is required to approve this transaction. ",
	"2074" => "Call Diners Club. An authorization number from the Diners' Club Voice Center is required to approve this transaction. ",
	"2075" => "Call AMEX. An authorization number from the American Express Voice Center is required to approve this transaction. ",
	"2076" => "Call Discover. An authorization number from the Discover Voice Center is required to approve this transaction. ",
	"2078" => "Call ECHO. The merchant must call ECHO Customer Support for approval.or because there is a problem with the merchant's account. ",
	"2079" => "Call XpresscheX. The merchant must call ECHO Customer Support for approval.or because there is a problem with the merchant's account. ",
	"3001" => "No ACK on Resp. The host did not receive an ACK from the terminal after sending the transaction response. ",
	"3002" => "POS NAK'd 3 Times. The host disconnected after the terminal replied 3 times to the host response with a NAK. ",
	"3003" => "Drop on Wait. The line dropped before the host could send a response to the terminal. ",
	"3005" => "Drop on Resp. The line dropped while the host was sending the response to the terminal. ",
	"3007" => "Drop Before EOT. The host received an ACK from the terminal but the line dropped before the host could send the EOT. ",
	"3011" => "No Resp to ENQ. The line was up and carrier detected, but the terminal did not respond to the ENQ. ",
	"3012" => "Drop on Input. The line disconnected while the host was receiving data from the terminal. ",
	"3013" => "FEP NAK'd 3. Times The host disconnected after receiving 3 transmissions with incorrect LRC from the terminal. ",
	"3014" => "No Resp to ENQ. The line disconnected during input data wait in Multi-Trans Mode. ",
	"3015" => "Drop on Input. The host encountered a full queue and discarded the input data. ",
	"9000" => "Host Error. The host encountered an internal error and was not able to process the transaction."
);

$cvverr = array(
	"M" => "Good match ",
	"N" => "No match ",
	"P" => "Not processed ",
	"S" => "Card issued with Security Code; merchant indicates Security Code is not present ",
	"U" => "Issuer does not support Security Code "
);

$avserr = array(
	"X" => "All digits of address and ZIP match (9-digit ZIP)",
	"Y" => "All digits of address and ZIP match (5-digit ZIP)",
	"D" => "Street address and postal code match",
	"M" => "Street address and postal code match",
	"A" => "Address matches, ZIP does not",
	"B" => "Street address match. Postal code not verified because of incompatible formats",
	"P" => "Postal code match. Street address not verified because of incompatible formats",
	"W" => "9-digit ZIP matches; address does not",
	"Z" => "5-digit ZIP matches, address does not",
	"C" => "Street address and postal code could not be verified due to incompatible formats",
	"G" => "Issuer unavailable or AVS not supported (non-US Issuer)",
	"I" => "Address information not verified for international transaction",
	"R" => "Retry; system is currently unable to process",
	"S" => "Card issuer does not support AVS",
	"U" => "Issuer unavailable or AVS not supported (US Issuer)",
	"E" => "ECHO received an invalid response from the issuer.",
	"N" => "Nothing matches"
);

# Auth
$post = "";
$post[] = "transaction_type=AV";
$post[] = "order_type=S";
$post[] = "merchant_echo_id=".$module_params["param01"];
$post[] = "merchant_pin=".$module_params["param02"];
$post[] = "billing_ip_address=".$REMOTE_ADDR;

$post[] = "merchant_email=".$lite_cart->config->get("Company.orders_department");
$post[] = "grand_total=".$cart["total_cost"];
$post[] = "billing_first_name=".$userinfo["firstname"];
$post[] = "billing_last_name=".$userinfo["lastname"];
$post[] = "billing_address1=".$userinfo["b_address"];
$post[] = "billing_city=".$userinfo["b_city"];
$post[] = "billing_state=".$userinfo["b_state"];
$post[] = "billing_zip=".$userinfo["b_zipcode"];
$post[] = "billing_country=".$userinfo["b_country"];
$post[] = "billing_phone=".$userinfo["phone"];
$post[] = "billing_email=".$userinfo["email"];
$post[] = "cc_number=".$userinfo["card_number"];
$post[] = "ccexp_month=".substr($userinfo["card_expire"],0,2);
$post[] = "ccexp_year=".(2000+substr($userinfo["card_expire"],2,2));
$post[] = "cnp_security=".$userinfo["card_cvv2"];
$post[] = "merchant_trace_nbr=".$module_params["param03"].join("-",$secure_oid);
$post[] = "counter=" . $conn_attempts;
#$post[] = "debug=t";

#print_r($post);

list($a,$return)=func_https_request("POST","https://wwws1.echo-inc.com:443/scripts/INR200.EXE",$post);

#print "[".$return."]";

preg_match("/<ECHOTYPE3>.*<status>(.*)<\/status>.*<\/ECHOTYPE3>/U",$return,$out);
$respcode = $out[1];


if($respcode == "G")
{
	preg_match("/<ECHOTYPE3>.*<order_number>(.*)<\/order_number>.*<\/ECHOTYPE3>/U",$return,$out);
	if(!empty($out[1]))$bill_output[billmes].= " (OrderNumber=".$out[1].")";
	$onum = $out[1];

	preg_match("/<ECHOTYPE3>.*<auth_code>(.*)<\/auth_code>.*<\/ECHOTYPE3>/U",$return,$out);
	if(!empty($out[1]))$bill_output[billmes].= " (AuthCode=".$out[1].")";
	$auth = $out[1];

	preg_match("/<ECHOTYPE3>.*<avs_result>(.*)<\/avs_result>.*<\/ECHOTYPE3>/U",$return,$out);
	if(!empty($out[1]))$bill_output[avsmes] = (($avserr[$out[1]]) ? $avserr[$out[1]] : "AVS Code: ".$out[1]);

	preg_match("/<ECHOTYPE3>.*<security_result>(.*)<\/security_result>.*<\/ECHOTYPE3>/U",$return,$out);
	if(!empty($out[1]))$bill_output[cvvmes] = (($cvverr[$out[1]]) ? $cvverr[$out[1]] : "CVV Code: ".$out[1]);

#<ECHOTYPE3>
#	<status>D</status>
#	<auth_code>1015</auth_code>
#	<decline_code>1015</decline_code>
#	<order_number>xcart381</order_number>
#	<merchant_name>X-CART INTEGRATION  TEST</merchant_name>
#	<tran_amount>13.99</tran_amount>
#	<tran_date>03/27/2003</tran_date>
#	<merchant_trace_nbr>xcart381</merchant_trace_nbr>
#	<version>3.25.5</version>
#</ECHOTYPE3>

#<ECHOTYPE3>
#	<status>G</status>
#	<order_number>xcart379</order_number>
#	<echo_reference>25637311</echo_reference>
#	<merchant_name>X-CART INTEGRATION  TEST</merchant_name>
#	<tran_amount>13.99</tran_amount>
#	<tran_date>03/27/2003</tran_date>
#	<merchant_trace_nbr>xcart379</merchant_trace_nbr>
#	<version>3.25.5</version>
#</ECHOTYPE3>

// /*
# Debit
$post = "";
$post[] = "transaction_type=DS";
$post[] = "order_type=S";
$post[] = "merchant_echo_id=".$module_params["param01"];
$post[] = "merchant_pin=".$module_params["param02"];
$post[] = "billing_ip_address=".$REMOTE_ADDR;
$post[] = "authorization=".$auth;

$post[] = "merchant_email=".$lite_cart->config->get("Company.orders_department");
$post[] = "grand_total=".$cart["total_cost"];
$post[] = "original_amount=".$cart["total_cost"];
$post[] = "cc_number=".$userinfo["card_number"];
$post[] = "ccexp_month=".substr($userinfo["card_expire"],0,2);
$post[] = "ccexp_year=".(2000+substr($userinfo["card_expire"],2,2));
$post[] = "cnp_security=".$userinfo["card_cvv2"];
$post[] = "merchant_trace_nbr=".$module_params["param03"].join("-",$secure_oid);
$post[] = "order_number=".$onum;
$post[] = "original_trandate_mm=".date("m");
$post[] = "original_trandate_dd=".date("d");
$post[] = "original_trandate_yyyy=".date("Y");
$post[] = "counter=".$conn_attempts;

list($a,$return)=func_https_request("POST","https://wwws1.echo-inc.com:443/scripts/INR200.EXE",$post);
#print $return."<hr>";

preg_match("/<ECHOTYPE3>.*<status>(.*)<\/status>.*<\/ECHOTYPE3>/U",$return,$out);
$respcode=$out[1];

if($respcode == "G")
{
	preg_match("/<ECHOTYPE3>.*<echo_reference>(.*)<\/echo_reference>.*<\/ECHOTYPE3>/U",$return,$out);
	if(!empty($out[1]))$bill_output[billmes].= " (ECHO Reference=".$out[1].")";

	$bill_output[code] = 1;
}
// */
}
else
{
	$bill_output[code] = 2;
	$bill_output[billmes].= $errstatus[$out[1]];

	preg_match("/<ECHOTYPE3>.*<decline_code>(.*)<\/decline_code>.*<\/ECHOTYPE3>/U",$return,$out);
	if(!empty($out[1]))
	{	if($out[1]>9000) $out[1]=9000; $out[1] +=0;
		$bill_output[billmes].= ": ".(($errmes[$out[1]]) ? $errmes[$out[1]] : "DeclineCode: ".$out[1]);
	}

}

if(empty($return))
	$bill_output[code]=0;

#print "<pre>";
#print_r($bill_output);
#print "</pre>";
#print $return."<hr>";
#print htmlspecialchars($return);
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
