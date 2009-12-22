<?php
    function func_LinkPoint_process(&$lite_cart, &$paymentMethod, $debug = false)
    {
        //
        // *********************** PREPARE ************************
        //

        // Save original Lite values into the following variables:
        // Store values for X-Cart $config variable here

        // Store values for X-Cart $cart variable here
        $cart = array ();
        $cart["total_cost"] = $lite_cart->get("total");

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

		$cart_details = $lite_cart->get('details');
		$cart_labels = array(
			"connectionAttempts" => "Connection attempts",
			"cvvMessage" => "CVV message",
			"avsMessage" => "AVS message"
		);

        // Count payment attempts
		$conn_attempts = (int) $cart_details['connectionAttempts'];

        if (is_null($conn_attempts)) {
            $conn_attempts = 1;
        } else {
            $conn_attempts++;
        }

		$cart_details['connectionAttempts'] = $conn_attempts;

        if ($debug) echo "Connection attempt: $conn_attempts<br>";

        $REMOTE_ADDR = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";

        if ($debug) echo "Remote addr: $REMOTE_ADDR<br>";

        // Misc. values
        $secure_oid = array ($lite_cart->get ("order_id"), $conn_attempts);

        if ($debug) {
            echo "secure_oid:<pre>"; print_r($secure_oid); echo "<pre><br>";
        }

        // for the post_func

		$cert_file = $lite_cart->config->get('linkpoint_cc.file_name');
		if (is_null($cert_file)) {
			die("No certificate installed");
		}
		$cert_text = $lite_cart->config->get('linkpoint_cc.cert_text');
		$certdir = "./var/tmp";
		if (!is_dir($certdir)) {
			mkdir($certdir);
		} 
		$handle = fopen($certdir . "/" . $cert_file, "w");
		fwrite($handle, $cert_text);
		fclose($handle);
		
        //
        // *************** X-CART LinkPoint payment processor code ***************
        //
// 	X-CART LinkPoint payment module processor code {{{		
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

@set_time_limit(100);

$pp_login = $module_params["param01"];
$sert = "./var/tmp/00.pem";
$host =  $module_params["param06"];
$port = $module_params["param07"];
$lp_test = "LIVE";
$cert = $certdir . "/" . $cert_file;
switch($module_params["testmode"]) {
	case "A": $lp_test = "GOOD"; break;
	case "D": $lp_test = "DECLINE"; break;
}

$addrnum = preg_replace("/[^\d]/","",$userinfo["b_address"]);

$post = "";
$post .= "<order><orderoptions>";
$post .= "<ordertype>SALE</ordertype>";
$post .= "<result>".$lp_test."</result></orderoptions>";

$post .= "<creditcard><cardnumber>".$userinfo["card_number"]."</cardnumber>";
$post .= "<cardexpmonth>".substr($userinfo["card_expire"],0,2)."</cardexpmonth>";
$post .= "<cardexpyear>".substr($userinfo["card_expire"],2,2)."</cardexpyear>";
$post .= "<cvmvalue>".$userinfo["card_cvv2"]."</cvmvalue>";
$post .= "<cvmindicator>".$module_params["param04"]."</cvmindicator></creditcard>";

$post .= "<merchantinfo><configfile>".$pp_login."</configfile>";
$post .= "<keyfile>".$cert."</keyfile>";
$post .= "<host>".$host."</host><port>".$port."</port></merchantinfo>";

$post .= "<payment><chargetotal>".$cart["total_cost"]."</chargetotal></payment>";

$post .= "<billing>";
$post .= "<name>".$userinfo["firstname"]." ".$userinfo["lastname"]."</name>";
$post .= "<address1>".$userinfo["b_address"]."</address1>";
$post .= "<city>".$userinfo["b_city"]."</city>";
$post .= "<state>".$userinfo["b_state"]."</state>";
$post .= "<zip>".$userinfo["b_zipcode"]."</zip>";
$post .= "<country>".$userinfo["b_country"]."</country>";
$post .= "<phone>".$userinfo["phone"]."</phone>";
$post .= "<email>".$userinfo["email"]."</email>";
$post .= "<userid>".$userinfo["login"]."</userid></billing>";

$post .= "<transactiondetails>";
$post .= "<oid>".$module_params["param05"].join("-",$secure_oid)."</oid>";
$post .= "</transactiondetails>";

$post .= "</order>";

list($a,$return)=lp_func_https_request("POST","https://$host:$port/LSGSXML", $post, $cert);
@unlink($certdir . "/" . $cert_file);

// test server emulation {{{
//list($a,$return)=lp_func_https_request("POST","https://rrf.ru/~ivf/xlite_1_2_x/tests/classes/modules/LinkPoint/test_srv/server.php", $post, $cert);
// }}}

//print "[".$a."]";
//print "[".$return."]";
#<r_time>Mon May 26 23:39:46 2003</r_time>
#	<r_ref>12345678</r_ref>
#	<r_approved>APPROVED</r_approved>
#	<r_code>0123456789123456:YNAM:01234567890123412345678:</r_code>
#	<r_error></r_error>
#	<r_ordernum>xcart596</r_ordernum>
#	<r_authresponse></r_authresponse>
#	<r_message></r_message>
#	<r_tdate>1054017586</r_tdate>

#<r_time>Mon May 26 23:45:51 2003</r_time>
#	<r_ref></r_ref>
#	<r_approved>FRAUD</r_approved>
#	<r_code></r_code>
#	<r_error>This credit card appears to have expired.</r_error>
#	<r_ordernum>xcart597</r_ordernum>
#	<r_authresponse></r_authresponse>
#	<r_message></r_message>
#	<r_tdate>1054017951</r_tdate>

$avserr = array(
	"YY" => "Address matches, zip code matches",
	"YN" => "Address matches, zip code does not match",
	"YX" => "Address matches, zip code comparison not available",
	"NY" => "Address does not match, zip code matches",
	"XY" => "Address comparison not available, zip code matches",
	"NN" => "Address comparison does not match, zip code does not match",
	"NX" => "Address does not match, zip code comparison not available",
	"XN" => "Address comparison not available, zip code does not match",
	"XX" => "Address comparisons not available, zip code comparison not available",
);

$cvverr = array(
	"M" => "Card Code Match",
	"N" => "Card code does not match",
	"P" => "Not processed",
	"S" => "Merchant has indicated that the card code is not present on the card",
	"U" => "Issuer is not certified and/or has not provided encryption keys"
);


preg_match("/<r_approved>(.*)<\/r_approved>/",$return,$out);

if($out[1] == "APPROVED")
{	
	$bill_output[code] = 1;
	preg_match("/<r_code>(.*)<\/r_code>/",$return,$out);
	$bill_output[billmes] = "Code [".$out[1]."] :: ";
	preg_match("/^(\w{6})(\w{10}):(\w{2})(\w)(\w):(.*):$/",$out[1],$pars);
	$bill_output[billmes] .= "Approval number: ".$pars[1]."; Reference number: ".$pars[2]."; Leaseline transaction identifier: ".$pars[6];
	#print_r($pars);
	if($cvverr[$pars[5]])
		$bill_output[cvvmes] = $cvverr[$pars[5]];
	if($pars[5])
		$bill_output[cvvmes].= " (CVV code: ".$pars[5].")";

	if($avserr[$pars[3]])
		$bill_output[avsmes] = $avserr[$pars[3]];
	if($pars[3])
		$bill_output[avsmes].= " (AVS code: ".$pars[3].$pars[4].")";
}
else
{
	$bill_output[code] = 2;
	$bill_output[billmes] = "[".$out[1]."] ";
	preg_match("/<r_error>(.*)<\/r_error>/",$return,$out);
	$bill_output[billmes] .= $out[1];
}

preg_match("/<r_authresponse>(.*)<\/r_authresponse>/",$return,$out);
if($out[1]) $bill_output[billmes].= " (AuthResponce: ".$out[1].")";

preg_match("/<r_message>(.*)<\/r_message>/",$return,$out);
if($out[1]) $bill_output[billmes].= " (Message: ".$out[1].")";

#print_r($bill_output);
#exit;

?><?php
// }}} 
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
		
        if ($bill_output ["cvvmes"])
			$cart_details["cvvMessage"] = $bill_output ["cvvmes"];
        else
			$cart_details["cvvMessage"] = null;

        if ($bill_output ["avsmes"])
			$cart_details["cvvMessage"] = $bill_output ["avsmes"];
        else
			$cart_details["cvvMessage"] = null;

		$cart_details["error"] = $error;
		
		$lite_cart->set('details', $cart_details);
		$lite_cart->set('detailLabels', $cart_labels);
        $lite_cart->set("status", $status);
        $lite_cart->update();
    }
?>
