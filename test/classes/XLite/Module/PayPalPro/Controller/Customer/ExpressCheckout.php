<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_PayPalPro_Controller_Customer_ExpressCheckout extends XLite_Controller_Abstract
{
	function getSecure()
	{
		return $this->config->getComplex('Security.customer_security');
	}
	
	function action_profile()
	{
		$pm = XLite_Model_PaymentMethod::factory('paypalpro_express');
		$response = $pm->sendExpressCheckoutRequest($this->cart); 
		if ($response["ACK"] == "Success" && !empty($response["TOKEN"])) {
			$pmpro = XLite_Model_PaymentMethod::factory('paypalpro');
			$redirect = $pmpro->getComplex('params.pro.mode') ? "https://www.paypal.com" : "https://www.sandbox.paypal.com";
			header("Location: ". $redirect."/webscr?cmd=_express-checkout&token=".$response["TOKEN"]);
			die();
		} else {
			$this->set("returnUrl","cart.php?target=checkout");
		}
	}

	function action_retrieve_profile()
	{
		if (!empty($_GET["token"])) {
			$profile = new XLite_Model_Profile();
			$pm = XLite_Model_PaymentMethod::factory('paypalpro_express');
			$response = $pm->sendExpressCheckoutDetailsRequest($_GET["token"]);
  			$details = $response["GETEXPRESSCHECKOUTDETAILSRESPONSEDETAILS"]["PAYERINFO"];
        if ($response["ACK"] == "Success") {
			$state = new XLite_Model_State();
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
	            $profile = new XLite_Model_Profile();
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
                $this->auth->setComplex("profile.order_id", $this->cart->get("order_id"));
	     	}
														
			XLite_Model_Auth::getInstance()->getProfile()->update();

			$this->cart->set("paymentMethod",$pm);
            $this->cart->setComplex("details.token", $response["GETEXPRESSCHECKOUTDETAILSRESPONSEDETAILS"]["TOKEN"]);
            $this->cart->setComplex("details.payer_id", $details["PAYERID"]);
			$this->updateCart();

			$this->set("returnUrl","cart.php?target=checkout");
				
			} 
		} 
		$this->set("returnUrl","cart.php?target=checkout");
	}
}
