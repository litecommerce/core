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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* @package Module_GoogleCheckout
* @access public
* @version $Id$
*/
class XLite_Module_GoogleCheckout_View_GoogleAltCheckout extends XLite_View
{
    var $template = "modules/GoogleCheckout/main_alt_checkout.tpl";
    var $GCMerchantID = null;
    var $CurrentSkin = null;

    function initGoogleData()
    {
    	if (!isset($this->GCMerchantID)) {
    		$pm = new XLite_Model_PaymentMethod("google_checkout");
    		$isAdminZone = $this->xlite->is("adminZone");
    		$this->xlite->set("adminZone", true);
    		$enabled = (bool) $pm->get("enabled");
    		$this->xlite->set("adminZone", $isAdminZone);
    		if ($enabled) {
    			$params = $pm->get("params");
    			$this->GCMerchantID = $params["merchant_id"];
    			$this->CurrentSkin = strval($this->get("dialog.config.Skins.skin"));
    		} else {
    			$this->GCMerchantID = null;
    		}
    	}
    }

    function isVisible()
    {
    	$targetsProfile = array
    	(
    		"profile",
    		"login",
    	);
		$targets = array(
			"checkout",
			"cart"
		);
    	$cart = $this->get("dialog.cart");
    	$dialogTarget = $this->get("dialog.target");
    	if (is_object($cart) && !$cart->is("empty") && !in_array($dialogTarget, $targets)) {
    		if (in_array($dialogTarget, $targetsProfile)) {
    			$this->set("dialog.google_checkout_profile", true);
    		}
    		$this->initGoogleData();
    		if (isset($this->GCMerchantID)) {
    			return true;
        	} else {
        		return false;
        	}
    	} else {
    		return false;
    	}
    }

	function getGoogleCheckoutButtonUrl()
	{
		$variant = "text";
		if (!$this->isGoogleAllowPay()) {
			$variant = "disabled";
		}

		$url = array();
		$url[] = "http";
		$url[] = ($this->get("dialog.secure")) ? "s" : "";
		$url[] = "://sandbox.google.com/checkout/buttons/checkout.gif?merchant_id=";
		$url[] = $this->GCMerchantID;
		$url[] = "&w=160&h=43&style=trans&variant=$variant&loc=en_US";

		return implode("", $url);
	}

	function getGoogleCheckoutButtonImgNum()
	{
		$imgMap = array
		(
            ""  => 1,
            "2-columns_modern"  => 1,
            "3-columns_modern"  => 2,
            "2-columns_classic" => 3,
            "3-columns_classic" => 4,
		);

		if (in_array($this->CurrentSkin, $imgMap)) {
			$vlaue = $imgMap[$this->CurrentSkin];
		} else {
			$vlaue = 1;
		}

		return $vlaue;
	}

	function isGoogleAllowPay()
	{
		$cart = $this->get("dialog.cart");
		if (is_null($cart) || !is_object($cart)) {
			$cart = func_get_instance("Cart");
		}

		return $cart->isGoogleAllowPay();
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
