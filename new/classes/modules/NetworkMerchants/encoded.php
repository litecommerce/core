<?php

/**
* NetworkMerchants payment gateway hidden code.
*
* @package Module_NetworkMerchants
* @access private
* @version $Id$
*/

function func_NetworkMerchants_process(&$lite_cart, &$paymentMethod, $debug = false)
{
    // license check
    check_module_license("NetworkMerchants");

    //
    // *********************** PREPARE ************************
    //

    // Store values for X-Cart $config variable here
    $config = array ();
    $config ["Company"]["orders_department"] = $lite_cart->config->get("Company.orders_department");

    // Store values for X-Cart $cart variable here
    $cart = array ();
    $cart["total_cost"] = $lite_cart->get("total");

    // Fill parameters fields here
    $module_params = $paymentMethod->get("params");

    if ($debug) {
        echo "module_params:<pre>"; print_r($module_params); echo "</pre><br>";
    }

    $profile = $lite_cart->get("profile");

    // Store values for X-Cart $userinfo variable here
    $userinfo = array ();
    $userinfo ["b_firstname"] = $profile->get("billing_firstname");
    $userinfo ["b_lastname"] = $profile->get("billing_lastname");
    $userinfo ["b_city"] = $profile->get("billing_city");
    $userinfo ["b_address"] = $profile->get("billing_address");
    $userinfo ["b_zipcode"] = $profile->get("billing_zipcode");
	$userinfo ["b_state"] = $profile->get("billingState.code");
	$userinfo ["b_country"] = $profile->get("billing_country");

    $userinfo ["s_firstname"] = $profile->get("shipping_firstname");
    $userinfo ["s_lastname"] = $profile->get("shipping_lastname");
    $userinfo ["s_city"] = $profile->get("shipping_city");
    $userinfo ["s_address"] = $profile->get("shipping_address");
    $userinfo ["s_zipcode"] = $profile->get("shipping_zipcode");
	$userinfo ["s_state"] = $profile->get("shippingState.code");
	$userinfo ["s_country"] = $profile->get("shipping_country");

    $userinfo ["phone"] = $profile->get("billing_phone");
    $userinfo ["email"] = $profile->get("login");
    $userinfo ["card_number"] = $paymentMethod->cc_info["cc_number"];
    $userinfo ["card_expire"] = $paymentMethod->cc_info["cc_date"];
    $userinfo ["card_cvv2"] = $paymentMethod->cc_info["cc_cvv2"];
	$userinfo ["card_name"] = $paymentMethod->cc_info["cc_name"];

    if ($debug) {
        echo "userinfo:<pre>"; print_r($userinfo); echo "</pre><br>";
    }

	$cart_details = $lite_cart->get('details');
	$cart_labels = $lite_cart->get("detailLabels");
	$cart_labels["connectionAttempts"] = "Connection attempts";
	$cart_labels["cvvMessage"] = "CVV message";
	$cart_labels["avsMessage"] = "AVS message";
	$cart_labels["error"] = "Response";

    // Count payment attempts
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
    $secure_oid = array ($lite_cart->get("order_id"), $conn_attempts);

    if ($debug) {
        echo "secure_oid:<pre>"; print_r($secure_oid); echo "<pre><br>";
    }

    // for the post_func
    $GLOBALS["debug"] = $debug;

    //
    // *************** X-CART NetworkMerchants payment processor code ***************
    //
// 	X-CART NetworkMerchants payment module processor code {{{		
?><?php
@set_time_limit(180);

$pp_merch = $module_params["param01"];
$pp_passwd =  $module_params["param03"];

$post = array();
$post[] = "username=".$pp_merch;
$post[] = "password=".$pp_passwd;
$post[] = "ccnumber=".$userinfo["card_number"];
$post[] = "ccexp=".$userinfo["card_expire"];
$post[] = "amount=".number_format($cart["total_cost"],2,".","");
$post[] = "cvv=".$userinfo["card_cvv2"];
$post[] = "ipaddress=".$REMOTE_ADDR;
$post[] = "orderid=".$module_params["param04"].join("-",$secure_oid);

$post[] = "firstname=".$userinfo["b_firstname"];
$post[] = "lastname=".$userinfo["b_lastname"];
$post[] = "address1=".$userinfo["b_address"];
$post[] = "city=".$userinfo["b_city"];
$post[] = "state=".$userinfo["b_state"];
$post[] = "zip=".$userinfo["b_zipcode"];
$post[] = "country=".$userinfo["b_country"];
$post[] = "phone=".$userinfo["phone"];
$post[] = "email=".$userinfo["email"];

$post[] = "shipping_firstname=".$userinfo["s_firstname"];
$post[] = "shipping_lastname=".$userinfo["s_lastname"];
$post[] = "shipping_address1=".$userinfo["s_address"];
$post[] = "shipping_city=".$userinfo["s_city"];
$post[] = "shipping_state=".$userinfo["s_state"];
$post[] = "shipping_zip=".$userinfo["s_zipcode"];
$post[] = "shipping_country=".$userinfo["s_country"];

$post[] = "type=".$module_params["testmode"];

$paymentMethod->initRequest($lite_cart, $post);

list($a,$return)=func_https_request("POST","https://secure.networkmerchants.com:443/gw/api/transact.php",$post);
parse_str($return,$ret);
#response=3&responsetext=Invalid Expiration&authcode=&transactionid=0&avsresponse=&cvvresponse=&orderid=
#response=2&responsetext=    DECLINE     &authcode=      &transactionid=33500569&avsresponse=N&cvvresponse=N&orderid=tst13-1

$bill_output["billmes"] = trim($ret["responsetext"]);

if($ret["response"]=="1")
{
    $bill_output["code"] = 1;
    if($ret["authcode"])
        $bill_output["billmes"].= " (AuthCode: ".$ret["authcode"].")";
}
else
    $bill_output["code"] = 2;

if($ret["transactionid"])
	$bill_output["billmes"].= " (TxnID: ".$ret["transactionid"].")";


$avserr = array(
    "X" => "Exact. Nine-digit zip code and address match.",
    "Y" => "Yes. Five-digit zip code and address match.",
    "A" => "Address matches, but zip code does not.",
    "W" => "Nine-digit zip code matches, but address does not.",
    "Z" => "Five-digit zip code matches, but address does not.",
    "N" => "No part of the address matches.",
    "U" => "Address information is unavailable.",
    "R" => "Retry. System unable to process.",
    "S" => "AVS not supported.",
    "E" => "AVS not supported for this industry.",
    "B" => "AVS not performed.",
    "Q" => "Unknown response from issuer/banknet switch."
);

$cvverr = array(
    "M" => "The CVD value provided matches the CVD value associated with the card.",
    "N" => "The CVD value provided does not match the CVD value associated with the card.",
    "P" => "The CVD value was not processed.",
    "S" => "Merchant indicated that CVV2 was not present on card.",
    "U" => "Issuer is not certified and/or has not provided Visa encryption keys."
);

if($ret["avsresponse"])
{
    $bill_output["avsmes"] = (empty($avserr[$ret["avsresponse"]]) ? "Code: ".$ret["avsresponse"] : $avserr[$ret["avsresponse"]]);
}
if($ret["cvvresponse"])
    $bill_output["cvvmes"].= (empty($cvverr[$ret["cvvresponse"]]) ? "Code: ".$ret["cvvresponse"] : $cvverr[$ret["cvvresponse"]]);

#print_r($bill_output);
#print_r($ret);
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
    $lite_cart->set("status", $status);
    $lite_cart->update();

	$error = $bill_output ["billmes"];
    if ($bill_output["code"] != 1) {
        $status = "F";
    } else {
        // success
        $status = "P";
    }
	
    if ($bill_output ["cvvmes"])
		$cart_details["cvvMessage"] = $bill_output ["cvvmes"];
    else
		$cart_details["cvvMessage"] = null;

    if ($bill_output ["avsmes"])
		$cart_details["avsMessage"] = $bill_output ["avsmes"];
    else
		$cart_details["avsMessage"] = null;

	$cart_details["error"] = $error;
	
	$lite_cart->set('details', $cart_details);
	$lite_cart->set('detailLabels', $cart_labels);
    $lite_cart->set("status", $status);
    $lite_cart->update();
}

/*
function networkmerchants_func_https_request($method, $url, $vars) {
    $request =& func_new('HTTPS');
    $request->url = $url;
	$request->data = $vars;
	
    $request->urlencoded=false;
	
    if ($GLOBALS["debug"]) {
        echo "request->data:<pre>"; print_r($request->data); echo "</pre><br>";
    }
    $request->request();

    if ($GLOBALS["debug"]) {
        echo "request->response:<pre>"; print_r($request->response); echo "</pre><br>";
    }
    return array ("", $request->response);
}
*/

?>
