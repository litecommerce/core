<?php
    function func_SkipJack_process(&$lite_cart, &$paymentMethod, $debug = false)
    {
        // license check
        check_module_license("SkipJack");

        //
        // *********************** PREPARE ************************
        //

        // Save original Lite values into the following variables:

        // Store values for X-Cart $config variable here

        // Store values for X-Cart $cart variable here
        $cart = array ();
        $cart["total_cost"] = sprintf("%.02f",$lite_cart->get("total"));
        $cart["subtotal"] = $lite_cart->get ("subtotal");

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

        $userinfo ["s_city"] = $lite_cart->get("profile.shipping_city");
        $userinfo ["s_address"] = $lite_cart->get("profile.shipping_address");
        $userinfo ["s_state"] = $lite_cart->get("profile.shippingState.code");
        $userinfo ["s_zipcode"] = $lite_cart->get("profile.shipping_zipcode");
        $userinfo ["s_country"] = $lite_cart->get("profile.shipping_country");


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
		$cart_labels = array(
			"avsMessage" => "AVS message",
			"cvvMessage" => "CVV message",
			"connectionAttempts" => "Connection attempts"
		);
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

        // for the post_func
        $GLOBALS["debug"] = $debug;
        
        //
        // *************** X-CART Verisign payment processor code ***************
        //

?><?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2004 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2004           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: encoded.php,v 1.7 2009/07/02 07:42:20 fundaev Exp $
#

$staerr = array(
        "-35" => "Error invalid credit card number",
        "-37" => "Error failed communication",
        "-39" => "Error length serial number",
        "-51" => "Error length zip code",
        "-52" => "Error length shipto zip code",
        "-53" => "Error length expiration date",
        "-54" => "Error length account number date",
        "-55" => "Error length street address",
        "-56" => "Error length shipto street address",
        "-57" => "Error length transaction amount",
        "-58" => "Error length name",
        "-59" => "Error length location",
        "-60" => "Error length state",
        "-61" => "Error length shipto state",
        "-62" => "Error length order string",
        "-64" => "Error invalid phone number",
        "-65" => "Error empty name",
        "-66" => "Error empty email",
        "-67" => "Error empty street address",
        "-68" => "Error empty city",
        "-69" => "Error empty state",
        "-79" => "Error length customer name",
        "-80" => "Error length shipto customer name",
        "-81" => "Error length customer location",
        "-82" => "Error length customer state",
        "-83" => "Error length shipto phone",
        "-84" => "Pos error duplicate ordernumber",
        "-91" => "Pos_error_CVV2",
        "-92" => "Pos_error_Error_Approval_Code",
        "-93" => "Pos_error_Blind_Credits_Not_Allowed",
        "-94" => "Pos_error_Blind_Credits_Failed",
        "-95" => "Pos_error_Voice_Authorizations_Not_Allowed "
);

$avserr = array(
 "X" => "Exact match, 9 digit zip",
 "Y" => "Exact match, 5 digit zip",
 "A" => "Address match only",
 "W" => "9 digit match only",
 "Z" => "5 digit match only",
 "N" => "No address or zip match",
 "U" => "Address unavailable",
 "R" => "Issuer system unavailable",
 "E" => "Not a mail/phone order",
 "S" => "Service not supported"
);

$test = ($module_params["testmode"]!="N" ? "developer" : "www");
$ordr = join("-",$secure_oid);

$os = "";

$items=$lite_cart->getItems();
if($items)
    foreach($items as $item) {
        $name = preg_replace("/[^a-zA-Z0-9':; ]/", "", $item->get("product.name"));
        $sku = preg_replace("/[^a-zA-Z0-9':; ]/", "", $item->get("product.sku"));
        $os.= $sku."~".$name."~".sprintf("%0.2f",$item->get("product.price"))."~".$item->get("amount")."~N~||";
    }

# Prepare user data
$sj_name = !empty($userinfo['firstname'])||!empty($userinfo['lastname']) ?  $userinfo['firstname']." ".$userinfo['lastname'] : "NA";
$empty_values = array('b_state'=>'XX', 's_state'=>'XX', 'b_zipcode'=>00000, 's_zipcode'=>00000);
foreach ($userinfo as $key => $field) {
    if (empty($field)) {
        $userinfo[$key] = array_key_exists($key, $empty_values) ? $empty_values[$key] : 'None';
    }
}

# Post data
$post = "";
$post[]="sjname=".$sj_name;
$post[]="Email=".$userinfo['email'];
$post[]="Streetaddress=".$userinfo['b_address'];
$post[]="City=".$userinfo['b_city'];
$post[]="State=".$userinfo['b_state'];
$post[]="Zipcode=".$userinfo['b_zipcode'];
$post[]="Ordernumber=".$ordr;
$post[]="Accountnumber=".$userinfo['card_number'];
$post[]="cvv2=".$userinfo['card_cvv2'];
$post[]="Month=".substr($userinfo["card_expire"],0,2);
$post[]="Year=".substr($userinfo["card_expire"],2,2);
$post[]="Serialnumber=".$module_params["param01"];
$post[]="Transactionamount=".$cart["total_cost"];
$post[]="Shiptophone=".$userinfo['phone'];
$post[]="Phone=".$userinfo['phone'];
$post[]="Country=".$userinfo['b_country'];
$post[]="Fax=".$userinfo['fax'];
$post[]="Shiptostreetaddress=".$userinfo['s_address'];
$post[]="Shiptocity=".$userinfo['s_city'];
$post[]="Shiptostate=".$userinfo['s_state'];
$post[]="Shiptozipcode=".$userinfo['s_zipcode'];
$post[]="Shiptocountry=".$userinfo['s_country'];
$post[]="Orderstring=".$os;

#print "<pre>";print_r($post);

list($a,$return) = func_https_request("POST","https://".$test.".skipjackic.com:443/scripts/EvolvCC.dll?AuthorizeAPI",$post);

#"AUTHCODE","szSerialNumber","szTransactionAmount","szAuthorizationDeclinedMessage","szAVSResponseCode","szAVSResponseMessage","szOrderNumber","szAuthorizationResponseCode","szIsApproved","szCVV2ResponseCode","szCVV2ResponseMessage","szReturnCode","szTransactionFileName"
#"VITAL5","000882895356","145","","Y","Card authorized, exact address match with 5 digit zip code.","1","VITAL5","1","","","1","9802851296723.DEV"

list($a,$b) = split("\n",$return);
$a = split("\",\"","\",".$a.",\"");
$b = split("\",\"","\",".$b.",\"");

$res = "";foreach($a as $i => $j)if($j!="")$res[$j] = $b[$i];

#[AUTHCODE] => EMPTY
#[szSerialNumber] => 000154051399
#[szTransactionAmount] => 41.95
#[szAuthorizationDeclinedMessage] => 
#[szAVSResponseCode] => 
#[szAVSResponseMessage] => 
#[szOrderNumber] => xcart521
#[szAuthorizationResponseCode] => 
#[szIsApproved] => 0
#[szCVV2ResponseCode] => 
#[szCVV2ResponseMessage] => 
#[szReturnCode] => -66
#[szTransactionFileName] => 

if($res[szIsApproved]==1)
{
        $bill_output[code] = 1;
        $bill_output[billmes] = "(TransactionFileName: ".$res[szTransactionFileName]."/ AUTHCODE: ".$res[AUTHCODE].")";
}
else
{
        $bill_output[code] = 2;
        $bill_output[billmes] = (empty($res[szAuthorizationDeclinedMessage]) ? $staerr[$res[szReturnCode]] : $res[szAuthorizationDeclinedMessage])." (ReturnCode: ".$res[szReturnCode].")";
}

if(!empty($res[szAVSResponseCode]) || !empty($res[szAVSResponseMessage]))
        $bill_output[avsmes] = (empty($res[szAVSResponseMessage]) ? $avserr[$res[szAVSResponseCode]] : $res[szAVSResponseMessage])." (".$res[szAVSResponseCode].")";

if(!empty($res[szCVV2ResponseCode]) || !empty($res[szCVV2ResponseMessage]))
        $bill_output[cvvmes].= $res[szCVV2ResponseMessage]." (".$res[szCVV2ResponseCode].")";

#print_r($res);
#print_r($bill_output);
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

        if ($bill_output ["avsmes"])
			$cart_details["avsMessage"] = $bill_output ["avsmes"];
        else
			$cart_details["avsMessage"] = null;

		if ($bill_output ["cvvmes"])
			$cart_details["cvvMessage"] = $bill_output ["cvvmes"];
        else
			$cart_details["cvvMessage"] = null;

		$cart_details["error"] = $error;
		
		$lite_cart->set('details', $cart_details);
		$lite_cart->set('detailLabels', $cart_labels);
        $lite_cart->set("status", $status);
        $lite_cart->update();
    }	
?>
