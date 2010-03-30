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
class XLite_Module_GoogleCheckout_View_GoogleAltCheckout extends XLite_View_Abstract
{	
    public $template = "modules/GoogleCheckout/main_alt_checkout.tpl";	
    public $GCMerchantID = null;	
    public $CurrentSkin = null;

    function initGoogleData()
    {
    	if (!isset($this->GCMerchantID)) {
    		$pm = XLite_Model_PaymentMethod::factory('google_checkout');
    		$isAdminZone = $this->xlite->is("adminZone");
    		$this->xlite->set("adminZone", true);
    		$enabled = (bool) $pm->get("enabled");
    		$this->xlite->set("adminZone", $isAdminZone);
    		if ($enabled) {
    			$params = $pm->get("params");
    			$this->GCMerchantID = $params["merchant_id"];
    			$this->CurrentSkin = strval($this->getComplex('dialog.config.Skins.skin'));
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
    	$cart = $this->getComplex('dialog.cart');
    	$dialogTarget = $this->getComplex('dialog.target');
    	if (is_object($cart) && !$cart->is("empty") && !in_array($dialogTarget, $targets)) {
    		if (in_array($dialogTarget, $targetsProfile)) {
    			$this->setComplex("dialog.google_checkout_profile", true);
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

	function getGoogleCheckoutButtonUrl($variant='medium', $background='white')
	{
		// Available button styles
		$backgrounds = array('white'=>'white', 'transparent'=>'trans');
		$variants = array(
				'large' => array('width'=>180, 'height'=>46),
				'medium' => array('width'=>168, 'height'=>44),
				'small' => array('width'=>160, 'height'=>43),
				'mobile-hi' => array('width'=>152, 'height'=>30),
				'mobile-low' => array('width'=>118, 'height'=>24),
			);

		// Chosen button style	
		$background = $backgrounds[$background];
		$variant = $variants[$variant];
		$width = $variant['width'];
		$height = $variant['height'];

		$enabled = $this->isGoogleAllowPay() ? 'text' : 'disabled';
		$protocol = ($this->getComplex('dialog.secure')) ? 'https' : 'http';
		$merchant_id = $this->GCMerchantID;

		return "$protocol://checkout.google.com/buttons/checkout.gif?merchant_id=$merchant_id&w=$width&h=$height&style=$background&variant=$enabled&loc=en_US";

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
		$cart = $this->getComplex('dialog.cart');
		if (is_null($cart) || !is_object($cart)) {
			$cart = XLite_Model_Cart::getInstance();
		}

		return $cart->isGoogleAllowPay();
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
