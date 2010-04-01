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
* PaymentMethod_parentpay description.
*
* @package Module_SecureTrading
* @access public
* @version $Id$
*/

class XLite_Module_SecureTrading_Model_PaymentMethod_Securetrading extends XLite_Model_PaymentMethod_CreditCard
{	

	public $configurationTemplate = "modules/SecureTrading/config.tpl";	
	public $processorName = "SecureTrading";	
	public $formTemplate = "modules/SecureTrading/checkout.tpl";

	function handleRequest(XLite_Model_Cart $order) { 
		require_once LC_MODULES_DIR . 'SecureTrading' . LC_DS . 'encoded.php';
		PaymentMethod_securetrading_handleRequest($this, $order, true);
	}
	function getTotalCost($cart)	{
		return $cart->get("total")*100;
	}
	function getBillingState($cart) { 
		$state = new XLite_Model_State($cart->getComplex('profile.billing_state'));
		return $state->get("state");
	}
	function getCountry($cart)	{
		$country = new XLite_Model_Country($cart->getComplex('profile.billing_country'));
		return $country->get("country");
	}
	function getMerchantEmail() {
		return $this->config->getComplex('Company.orders_department');
	}
	function getReturnURL($cart)	{
		return $this->xlite->getShopUrl("cart.php?target=checkout&action=return&order_id=" . $cart->get("order_id"));
	}
}
?>
