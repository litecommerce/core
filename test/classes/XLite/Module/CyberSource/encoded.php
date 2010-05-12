<?php
    function func_CyberSource_process(&$lite_cart, &$paymentMethod, $debug = false)
    {
        //
        // *********************** PREPARE ************************
        //

        // Save original Lite values into the following variables:

        // Store values for X-Cart $config variable here

        // Store values for X-Cart $cart variable here
        $cart = array ();
        $cart["total_cost"] = $lite_cart->get ("total");
        $cart["discount"] = $lite_cart->get ("discount");
        $cart["subtotal"] = $lite_cart->get ("subtotal");

        if (isset($cart["discount"])) {
            $cart["total_cost"] += $cart["discount"];
        }

        $cart["sh_n_tax"] = doubleval(sprintf("%.2f", ($cart[total_cost] - $cart[subtotal])));
        $cart["sh_n_tax"] = ($cart["sh_n_tax"] < 0) ? 0 : $cart["sh_n_tax"];
        
        if (isset($cart["discount"])) {
            $cart["subtotal"] -= $cart["discount"];
        }
        // Fill parameters fields here
        $module_params = $paymentMethod->get('params');
        if ($debug) {
            echo "module_params:<pre>"; print_r($module_params); echo "</pre><br>";
        }

        // Store values for X-Cart $userinfo variable here
        $userinfo = array ();
        $userinfo ["firstname"] = $lite_cart->getComplex('profile.billing_firstname');
        $userinfo ["lastname"] = $lite_cart->getComplex('profile.billing_lastname');
        $userinfo ["b_city"] = $lite_cart->getComplex('profile.billing_city');
        $userinfo ["b_address"] = $lite_cart->getComplex('profile.billing_address');
        $userinfo ["b_state"] = $lite_cart->getComplex('profile.billingState.code');
        $userinfo ["b_zipcode"] = $lite_cart->getComplex('profile.billing_zipcode');
        $userinfo ["b_country"] = $lite_cart->getComplex('profile.billing_country');
        $userinfo ["phone"] = $lite_cart->getComplex('profile.billing_phone');
        $userinfo ["email"] = $lite_cart->getComplex('profile.login');
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
        
        // executable path
        $certdir = "./var/tmp";
        if (!is_dir($certdir)) {
            mkdir($certdir);
        }
        
        //
        // *************** X-CART CyberSource payment processor code ***************
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

$vs_mid = $module_params["param01"];
$vs_path = $module_params["param02"];
$vs_host = $module_params["param03"];
$vs_port = $module_params["param04"];
$vs_curr = $module_params["param05"];
$vs_prx = $module_params["param06"];

$post = "";
$post[] = "ics_path=".$vs_path;
$post[] = "server_host=".$vs_host;
$post[] = "server_port=".$vs_port;
$post[] = "ics_applications=ics_auth,ics_bill";
$post[] = "merchant_id=".$vs_mid;
$post[] = "customer_firstname=".$userinfo["firstname"];
$post[] = "customer_lastname=".$userinfo["lastname"];
$post[] = "customer_email=".$userinfo["email"];
$post[] = "customer_phone=".$userinfo["phone"];
$post[] = "bill_address1=".$userinfo["b_address"];
$post[] = "bill_city=".$userinfo["b_city"];
$post[] = "bill_state=".$userinfo["b_state"];
$post[] = "bill_zip=".$userinfo["b_zipcode"];
$post[] = "bill_country=".$userinfo["b_country"];
$post[] = "customer_cc_number=".$userinfo["card_number"];
$post[] = "customer_cc_expmo=".substr($userinfo["card_expire"],0,2);
$post[] = "customer_cc_expyr=".(2000+substr($userinfo["card_expire"],2,2));
$post[] = "merchant_ref_number=".join("-",$secure_oid);
$post[] = "currency=".$vs_curr;
if (isset($userinfo["card_cvv2"]) && strlen($userinfo["card_cvv2"]) > 0) {
    $post[] = "customer_cc_cv_number=".$userinfo["card_cvv2"];
} else {
    $post[] = "customer_cc_cv_indicator=0";
}

#$i=0;$post[] = "offer".$i."=offerid".($i++)."^product_name:".strtr($product[product],"^:","  ")."^merchant_product_sku:".strtr($product[productcode],"^:","  ")."^product_code:^amount:".$product[price]."^quantity:".$product[amount];
//$post[] = "offer0=offerid0^product_name:Products^merchant_product_sku:^product_code:^amount:". $cart['subtotal'] ."^quantity:1";
//$post[] = "offer1=offerid1^product_name:Shipping_etc^merchant_product_sku:^product_code:^amount:".$cart["sh_n_tax"]."^quantity:1";

$post[] = "offer0=offerid0^product_name:Shopping_cart^merchant_product_sku:^product_code:^amount:". $lite_cart->get("total") ."^quantity:1";

# Execute ICS
#$tmpfile = func_temp_store('');

$tmpfile = @tempnam($certdir,"lctmp");
$execline = func_find_executable("perl")." ./classes/modules/CyberSource/csrc.pl process 1> ".$tmpfile." 2>&1";

$fp = popen($execline, "w");
fputs($fp,join("\n",$post)); pclose($fp);
$return = file($tmpfile);

if($return)
foreach($return as $v)
{ list($a,$b) = split("=",$v,2); $ret[$a] = trim($b); }

if($ret[ics_rcode] == "1")
{
        $bill_output[code] = 1;

        $bill_output[billmes] = $ret[ics_rmsg];
        if($ret[auth_auth_code])
                $bill_output[billmes].= " (AuthCode: ".$ret[auth_auth_code].")";
        if($ret[bill_trans_ref_no])
                $bill_output[billmes].= " (RefNo: ".$ret[bill_trans_ref_no].")";
}
else
{
        $bill_output[code] = 2;
        $bill_output[billmes] = $ret[ics_rmsg];
}

if($ret[auth_avs_raw])$bill_output[avsmes] = "Auth AVS raw: ".$ret[auth_avs_raw];

?><?php

        //
        // *********************** POST PROCESS ***********************
        //

        if ($debug) {
            echo "bill_output:<pre>"; print_r($bill_output); echo "</pre><br>";
exit;
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

        $cart_details["error"] = $error;
        
        $lite_cart->set('details', $cart_details);
        $lite_cart->set('detailLabels', $cart_labels);
        $lite_cart->set("status", $status);
        $lite_cart->update();
    }
?>
