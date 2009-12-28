<?php
    function func_TrustCommerce_process(&$lite_cart, &$paymentMethod, $debug = false)
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
        $userinfo ["b_zipcode"] = $lite_cart->get("profile.billing_zipcode");
        $userinfo ["b_country"] = $lite_cart->get("profile.billing_country");
        $userinfo ["s_city"] = $lite_cart->get("profile.shipping_city");
        $userinfo ["s_address"] = $lite_cart->get("profile.shipping_address");
        $userinfo ["s_zipcode"] = $lite_cart->get("profile.shipping_zipcode");
        $userinfo ["s_country"] = $lite_cart->get("profile.shipping_country");
		$userinfo ["phone"] = $lite_cart->get("profile.billing_phone");
        $userinfo ["email"] = $lite_cart->get("profile.login");
        $userinfo ["card_name"] = $paymentMethod->cc_info["cc_name"];
        $userinfo ["card_number"] = $paymentMethod->cc_info["cc_number"];
        $userinfo ["card_expire"] = $paymentMethod->cc_info["cc_date"];
        $userinfo ["card_cvv2"] = $paymentMethod->cc_info["cc_cvv2"];
		$st = new XLite_Model_State();
		$st->find("state_id=".$lite_cart->get("profile.billing_state"));
		$userinfo ["b_state"] = $st->get("code");
		$st->find("state_id=".$lite_cart->get("profile.shipping_state"));
		$userinfo ["s_state"] = $st->get("code"); 
		 
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
        // *************** X-CART TrustCommerce payment processor code ***************
        //
// {{{
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

$decline = array(
	"decline" => "This is a 'true' decline, it almost always is a result of insufficient funds on the card.",
	"avs" => "AVS failed; the address entered does not match the billing address on file at the bank.",
	"cvv" => "CVV failed; the number provided is not the correct verification number for the card. (See section X for details on CVV.)",
	"call" => "The card must be authorized manually over the phone. You may choose to call the customer service number listed on the card and ask for an offline authcode, which can be passed in the offlineauthcode field.",
	"carderror" => "Card number is invalid, usually the result of a typo in the card number.", 
	"authexpired" => "Attempt to postauth an expired (more than 7 days old) preauth.", 
	"dailylimit" => "Daily limit in transaction count or amount as been reached.", 
	"weeklylimit" => "Weekly limit in transaction count or amount as been reached.",
	"monthlylimit" => "Monthly limit in transaction count or amount as been reached."
);

$baddata = array (
	"missingfields" => "Some parameters required for this transaction type were not sent.", 
	"extrafields" => "Parameters not allowed for this transaction type were sent.", 
	"badformat" => "A field was improperly formatted, such as non-digit characters in a number field.", 
	"badlength" => "A field was longer or shorter than the server allows.",  
	"merchantcantaccept" => "The merchant can't accept data passed in this field. If the offender is 'cc', for example, it usually means that you tried to run a card type (such as American Express or Discover) that is not supported by your account. If it was 'currency', you tried to run a currency type not supported by your account.",
	"mismatch" => "Data in one of the offending fields did not cross-check with the other offending field."
);

$error = array (
	"cantconnect" => "Couldn't connect to the TrustCommerce gateway. Check your Internet connection to make sure it is up.", 
	"dnsfailure" => "The TCLink software was unable to resolve DNS hostnames. Make sure you have name resolving ability on the machine.", 
	"linkfailure" => "The connection was established, but was severed before the transaction could complete.",
	"failtoprocess" => "The bank servers are offline and unable to authorize transactions. Try again in a few minutes, or try a card from a different issuing bank."
);

$avserr = array(
	"A" => "Address (Street) matches, ZIP does not",
	"E" => "AVS error",
	"N" => "No Match on Address (Street) or ZIP",
	"P" => "AVS not applicable for this transaction",
	"R" => "Retry. System unavailable or timed out",
	"S" => "Service not supported by issuer",
	"U" => "Address information is unavailable",
	"W" => "9 digit ZIP matches, Address (Street) does not",
	"X" => "Exact AVS Match",
	"Y" => "Address (Street) and 5 digit ZIP match",
	"Z" => "5 digit ZIP matches, Address (Street) does not"
);

$cvverr = array(
	"M" => "Match",
	"N" => "No Match",
	"P" => "Not Processed",
	"S" => "Should have been present",
	"U" => "Issuer unable to process request"
);

$tc_custid = $module_params["param01"];
$tc_password = $module_params["param02"];
$tc_prefix = $module_params["param04"];
$tc_curr = $module_params["param05"];
$tc_avs = $module_params["param06"];
$tc_operator = $module_params["param07"];

$post["custid"] = $tc_custid;
$post["password"] = $tc_password;
$post["action"] = "sale";
if ($module_params["testmode"] != "N") $post["demo"]="y";
$post["address1"] = $userinfo["b_address"];
$post["city"] = $userinfo["b_city"];
$post["state"] = (!empty($userinfo["b_state"]))? $userinfo["b_state"] : "Non US";
$post["zip"] = $userinfo["b_zipcode"];
$post["country"] = $userinfo["b_country"];
$post["phone"] = $userinfo["phone"];
$post["email"] = $userinfo["email"];
$post["shipto_name"] = $Ship_To_name !="" ? $Ship_To_name: $userinfo["firstname"]." ".$userinfo["lastname"];
$post["shipto_address1"] = $userinfo["s_address"];
$post["shipto_city"] = $userinfo["s_city"];
$post["shipto_state"] = (!empty($userinfo["s_state"]))? $userinfo["s_state"] : "Non US";
$post["shipto_zip"] = $userinfo["s_zipcode"];
$post["shipto_country"] = $userinfo["s_country"];

$post["amount"] = $cart["total_cost"]*100;
$post["currency"] = $tc_curr;
$post["name"] = $userinfo["card_name"];
$post["cc"] = $userinfo["card_number"];
$post["exp"] = $userinfo["card_expire"];
$post["cvv"] = $userinfo["card_cvv2"];
$post["ticket"] = $tc_prefix.join("-",$secure_oid);
$post["operator"] = $tc_operator;

#
# Order details
#
$products = $lite_cart->get("items");
$post["numitems"] = count($products);
$post["shippinghandling"] = $lite_cart->get("shipping_cost")*100; 
if (is_array($products))
foreach($products as $k => $v ) {
	$n = $k + 1;
	!is_null($v->get("sku")) ? $post["productcode".$n] = "#" . $v->get("sku") : $post["productcode".$n] = "#" . $v->get("product_id");
	$post["quantity".$n] = $v->get("amount");
	$post["price".$n] = $v->get("price")  * 100;
}
if ($tc_avs == "Y") $post["avs"] = "y";

#
# Use HTTPS connection
#
$http_post = "";
foreach($post as $key=>$value)
	$http_post[] = $key."=".$value;

/*
print_r($http_post);
die();
*/
list($a,$content) = func_https_request("POST","https://vault.trustcommerce.com:443/trans/", $http_post);

$result="";
foreach (split("\n",$content) as $line) {
	list($key,$value) = split('=',$line);
	$result[$key] = $value;
}

$bill_output["billmes"] = "";
	
switch($result["status"]) {
	case "notconfugured":
		$bill_output["code"] = 2;
		$bill_output["billmes"] = "The TClink library is not configured (installed)";
		break;
	case "accepted":
	case "approved":
		$bill_output["code"] = 1;
		$bill_output["billmes"] = " (Trans ID: ".$result["transid"].") ";
		break;
	case "decline":
	case "rejected":
		$bill_output["code"] = 2;
		$bill_output["billmes"] = " Declined (Trans ID: ".$result["transid"]."): ".$decline[$result["declinetype"]]." ";
		if (!empty($result["avs"])) $bill_output["avsmes"] = "AVS Code: ".$avserr[$result["avs"]];
		if (!empty($result["cvv"])) $bill_output["cvvmes"] = "CVV Code: ".$cvverr[$result["cvv"]];
		break;
	case "error":
		$bill_output["code"] = 2;
		$bill_output["billmes"] = "Error : ". $error[$result["error"]];
		break;
	case "baddata":
		$bill_output["code"] = 2;
		$bill_output["billmes"] = "Error (bad data): ".$result["offenders"]." : ".$baddata[$result["error"]];
		break;
}

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
