<?php
    function func_eWAYxml_process(&$lite_cart, &$paymentMethod, $debug = false)
    {
        //
        // *********************** PREPARE ************************
        //

        // Save original Lite values into the following variables:

        // Store values for X-Cart $config variable here

        // Store values for X-Cart $cart variable here
        $cart = array ();
        $cart['total_cost'] = $lite_cart->get ("total");

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
        $userinfo ["card_number"] = $paymentMethod->cc_info['cc_number'];
        $userinfo ["card_expire"] = $paymentMethod->cc_info['cc_date'];
        $userinfo ["card_cvv2"] = $paymentMethod->cc_info['cc_cvv2'];
        $userinfo ["card_name"] = $paymentMethod->cc_info['cc_name'];

        if ($debug) {
            echo "userinfo:<pre>"; print_r($userinfo); echo "</pre><br>";
        }

        // Count payment attempts
        $cart_details = $lite_cart->get('details');
        $cart_labels = array(
            "connectionAttempts" => "Connection attempts",
            "cvvMessage" => "CVV message",
            "avsMessage" => "AVS message"
        );
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


        //
        // *************** X-CART eWAYxml payment processor code ***************
        //
// eWAYxml {{{
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

$pp_login = $module_params['param01'];
$pp_test = ($module_params['testmode']=="N")?(""):("TRUE");
// LiteCommece code {{{
//$script = ($module_params['testmode']=="N")?("gateway/xmlpayment.asp"):("gateway/xmltest/TestPage.asp");
$test_server = $module_params['param09'];
$live_server = $module_params['param08'];
$pay_server = ($module_params['testmode']=="N")?($live_server):($test_server);


$post = "";
$post .= "<ewaygateway>";
$post .= "<ewayCustomerID>".$pp_login."</ewayCustomerID>";
$post .= "<ewayTotalAmount>".(100*$cart['total_cost'])."</ewayTotalAmount>";
$post .= "<ewayCustomerFirstName>".$userinfo['firstname']."</ewayCustomerFirstName>";
$post .= "<ewayCustomerLastName>".$userinfo['lastname']."</ewayCustomerLastName>";
$post .= "<ewayCustomerEmail>".$userinfo['email']."</ewayCustomerEmail>";
$post .= "<ewayCustomerAddress>".$userinfo['b_address']."</ewayCustomerAddress>";
$post .= "<ewayCustomerPostcode>".$userinfo['b_zipcode']."</ewayCustomerPostcode>";
$post .= "<ewayCustomerInvoiceDescription>".$descr."</ewayCustomerInvoiceDescription>";
$post .= "<ewayCustomerInvoiceRef>".$module_params['param03'].join("-",$secure_oid)."</ewayCustomerInvoiceRef>";
$post .= "<ewayCardHoldersName>".$userinfo['card_name']."</ewayCardHoldersName>";
$post .= "<ewayCardNumber>".$userinfo['card_number']."</ewayCardNumber>";
$post .= "<ewayCardExpiryMonth>".substr($userinfo['card_expire'],0,2)."</ewayCardExpiryMonth>";
$post .= "<ewayCardExpiryYear>".substr($userinfo['card_expire'],2,2)."</ewayCardExpiryYear>";
$post .= "<ewayTrxnNumber></ewayTrxnNumber>";
$post .= "<ewayOption1></ewayOption1>";
$post .= "<ewayOption2></ewayOption2>";
$post .= "<ewayOption3>".$pp_test."</ewayOption3>";
$post .= "</ewaygateway>";

// LiteCommerce code {{{
//list($a,$return)=func_https_request('POST',"https://www.eway.com.au:443/".$script,$post,"","","text/xml");
list($a,$return)=ewx_func_https_request('POST', $pay_server, $post);


#<ewayResponse>
#	<ewayTrxnError>A9,INVALID CARD NUMBER. Data Sent:4111111111111111</ewayTrxnError>
#	<ewayTrxnStatus>False</ewayTrxnStatus>
#	<ewayTrxnNumber>10016</ewayTrxnNumber>
#	<ewayTrxnOption1></ewayTrxnOption1>
#	<ewayTrxnOption2></ewayTrxnOption2>
#	<ewayTrxnOption3>TRUE</ewayTrxnOption3>
#   <ewayAuthCode></ewayAuthCode>
#   <ewayReturnAmount>11998</ewayReturnAmount>
#	<ewayTrxnReference></ewayTrxnReference>
#</ewayResponse>

$bill_output[cvvmes].= "Not support";
$bill_output[avsmes] = "Not support";

preg_match("/<ewayTrxnStatus>(.*)<\/ewayTrxnStatus>/",$return,$out);

if ($out[1] == "True")
{	preg_match("/<ewayAuthCode>(.*)<\/ewayAuthCode>/",$return,$out);
    $bill_output[code] = 1; $bill_output[billmes] = $out[1]; }
else
{	preg_match("/<ewayTrxnError>(.*)<\/ewayTrxnError>/",$return,$out);
    $bill_output[code] = 2; $bill_output[billmes] = $out[1]; }

preg_match("/<ewayTrxnNumber>(.*)<\/ewayTrxnNumber>/",$return,$out);
$bill_output[billmes].= " (TrnxNum=".$out[1].")";

?><?php


        //
        // *********************** POST PROCESS ***********************
        //

        if ($debug) {
            echo "bill_output:<pre>"; print_r($bill_output); echo "</pre><br>";
        }
        
        $status = "I";

        if ($bill_output['code'] != 1) {
            $error = $bill_output ["billmes"];
            $status = "F";
        } else {
            // success
            $error = "";
            $status = "P";
        }

        if ($bill_output ["cvvmes"])
            $cart_details['cvvMessage'] = $bill_output['cvvmes'];
        else
            $cart_details['cvvMessage'] = null;

        if ($bill_output ["avsmes"])
            $cart_details['avsMessage'] = $bill_output['avsmes'];
        else
            $cart_details['avsMessage'] = null;

        $cart_details['error'] = $error;
        $lite_cart->set('status', $status);
        $lite_cart->set('details', $cart_details);
        $lite_cart->set('detailLabels', $cart_labels);
        $lite_cart->update();
    }
?>
