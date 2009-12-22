<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* 
*
* @package
* @access public
* @version $Id$
*/
class Dialog_express_checkout extends Dialog
{
	function getSecure()
	{
		return $this->config->get("Security.customer_security");
	}
	
	function action_profile()
	{
		$pm = func_new("PaymentMethod","paypalpro_express");
		$response = $pm->sendExpressCheckoutRequest($this->cart); 
		if ($response["ACK"] == "Success" && !empty($response["TOKEN"])) {
			$pmpro = func_new("PaymentMethod","paypalpro");
			$redirect = $pmpro->get("params.pro.mode") ? "https://www.paypal.com" : "https://www.sandbox.paypal.com";
			header("Location: ". $redirect."/webscr?cmd=_express-checkout&token=".$response["TOKEN"]);
			die();
		} else {
			$this->set("returnUrl","cart.php?target=checkout");
		}
	}

	function action_retrieve_profile()
	{
		if (!empty($_GET["token"])) {
			$profile = func_new("Profile");
			$pm = func_new("PaymentMethod","paypalpro_express");
			$response = $pm->sendExpressCheckoutDetailsRequest($_GET["token"]);
  			$details = $response["GETEXPRESSCHECKOUTDETAILSRESPONSEDETAILS"]["PAYERINFO"];
        if ($response["ACK"] == "Success") {
			$state = func_new("State");
			$countryCode = $details["ADDRESS"]["COUNTRY"];
			$stateCode = addslashes($details["ADDRESS"]["STATEORPROVINCE"]);
			$stateCondition = ($countryCode == "US") ? "code='$stateCode'" : "(code='$stateCode' OR state='$stateCode')";
			$state->find("country_code='$countryCode' AND $stateCondition");
			if ($this->cart->get("profile")) {
				$profile = $this->cart->get("profile");
                $profile->set("shipping_firstname",$details["PAYERNAME"]["FIRSTNAME"]);
                $profile->set("shipping_lastname",$details["PAYERNAME"]["LASTNAME"]);
                $profile->set("shipping_company","");
                $profile->set("shipping_fax","");
                $profile->set("shipping_phone",$response["CONTACTPHONE"]);
                $profile->set("shipping_address",$details["ADDRESS"]["STREET1"]." ".$details["ADDRESS"]["STREET2"]);
                $profile->set("shipping_city",$details["ADDRESS"]["CITYNAME"]);
				$profile->set("shipping_state",$state->get("state_id"));
                $profile->set("shipping_country",$details["ADDRESS"]["COUNTRY"]);
                $profile->set("shipping_zipcode",$details["ADDRESS"]["POSTALCODE"]);
				$profile->update();
			} else if ($profile->find("login = '".$details["PAYER"]."' AND order_id = 0")) {
				$this->set("valid",false);
				$this->redirect("cart.php?target=profile&mode=login");
			} else {
	            $profile = func_new("Profile");
    	        $profile->set("login",$details["PAYER"]);
        	    $profile->set("billing_firstname",$details["PAYERNAME"]["FIRSTNAME"]);
            	$profile->set("billing_lastname",$details["PAYERNAME"]["LASTNAME"]);
	            $profile->set("billing_company","");
    	        $profile->set("billing_fax","");
        	    $profile->set("billing_phone",$response["CONTACTPHONE"]);
	        	$profile->set("billing_address",$details["ADDRESS"]["STREET1"]." ".$details["ADDRESS"]["STREET2"]);
		        $profile->set("billing_city",$details["ADDRESS"]["CITYNAME"]);
				$profile->set("billing_state",$state->get("state_id"));
	    	    $profile->set("billing_country",$details["ADDRESS"]["COUNTRY"]);
	        	$profile->set("billing_zipcode",$details["ADDRESS"]["POSTALCODE"]);
                $this->auth->register($profile);
                $this->auth->loginProfile($profile);
                $this->auth->set("profile.order_id", $this->cart->get("order_id"));
	     	}
														
			$this->auth->call("profile.update");
			$this->cart->set("paymentMethod",$pm);
            $this->cart->set("details.token",$response["GETEXPRESSCHECKOUTDETAILSRESPONSEDETAILS"]["TOKEN"]);
            $this->cart->set("details.payer_id",$details["PAYERID"]);
			$this->updateCart();

			$this->set("returnUrl","cart.php?target=checkout");
				
			} 
		} 
		$this->set("returnUrl","cart.php?target=checkout");
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
