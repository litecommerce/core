<?php
    function func_NetRegistry_process(&$lite_cart, &$paymentMethod, $debug = false)
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
        $userinfo ["b_state"] = $lite_cart->get("profile.billingState.code");
        $userinfo ["b_zipcode"] = $lite_cart->get("profile.billing_zipcode");
        $userinfo ["b_country"] = $lite_cart->get("profile.billing_country");
        $userinfo ["phone"] = $lite_cart->get("profile.billing_phone");
        $userinfo ["email"] = $lite_cart->get("profile.login");
        $userinfo ["card_name"] = $paymentMethod->cc_info["cc_name"];
        $userinfo ["card_number"] = $paymentMethod->cc_info["cc_number"];
        $userinfo ["card_expire"] = $paymentMethod->cc_info["cc_date"];
        $userinfo ["card_cvv2"] = $paymentMethod->cc_info["cc_cvv2"];

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
        $GLOBALS["debug"] = $debug;

        //
        // *************** X-CART Echo payment processor code ***************
        //
// 	X-CART NetRegistry payment module processor code {{{		
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

$pp_merch = $module_params["param01"];
$pp_pass = $module_params["param02"];

$post = "";
$post[] = "LOGIN=".$pp_merch."/".$pp_pass;
$post[] = "COMMAND=purchase";
$post[] = "AMOUNT=".$cart["total_cost"];
$post[] = "COMMENT=OID:".join("-",$secure_oid).";CardHolder:".$userinfo["card_name"];
$post[] = "CCNUM=".$userinfo["card_number"];
$post[] = "CCEXP=".substr($userinfo["card_expire"],0,2)."/".substr($userinfo["card_expire"],2,2);

$gateway_url = $module_params["param09"];
list($a,$return)=func_https_request("POST", $gateway_url, $post);
$return = "&".strtr($return,"\n","&")."&";

# declined
# .
# result=0           # 0 and -1 = failed; 1 - approved
# card_type=05
# settlement_date=20030228
# status=declined
# card_desc=VISA                
# response_text=CARD NOT VALID      
# txn_ref=0302282313434545
# bank_ref=002248
# response_code=31

if(preg_match("/&status=approved&/i",$return) && preg_match("/&result=1&/i",$return))
{
	$bill_output[code] = 1;
	if(preg_match("/authentication=(.*)&/U",$return,$out))
		$bill_output[billmes] ="(authentication=[".$out[1]."])";
}
else
{
	$bill_output[code] = 2;
	if(preg_match("/response_text=(.*)&/U",$return,$out))
		$bill_output[billmes] =$out[1];
}

preg_match("/bank_ref=(.*)&/U",$return,$out); $bill_output[billmes].="(Bank ref=".$out[1].")";
preg_match("/txn_ref=(.*)&/U",$return,$out);  $bill_output[billmes].="(Txn=".$out[1].")";
$bill_output[cvvmes].= "Not support";
$bill_output[avsmes] = "Not support";

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
            $cart_details ["cvvMessage"] = $bill_output ["cvvmes"];
        else
            $cart_details ["cvvMessage"] = null;

        if ($bill_output ["avsmes"])
            $cart_details ["avsMessage"] = $bill_output ["avsmes"];
        else
			$cart_details ["avsMessage"] = null;

        $cart_details ["error"] = $error;
        $lite_cart->set("status", $status);
		$lite_cart->set("details", $cart_details);
		$lite_cart->set('detailLabels', $cart_labels);
        $lite_cart->update();
    }	
?>
