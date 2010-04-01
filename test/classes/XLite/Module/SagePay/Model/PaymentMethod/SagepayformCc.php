<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003 Creative Development <info@creativedevelopment.biz>       |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL:                                                        |
| http://www.litecommerce.com/software_license_agreement.html                  |
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
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* @package Module_SagePay
* @access public
* @version $Id$
*/
class XLite_Module_SagePay_Model_PaymentMethod_SagepayformCc extends XLite_Model_PaymentMethod_CreditCard
{	
    public $processorName = "SagePay VSP Form";	
	public $hasConfigurationForm = true;	
    public $configurationTemplate = "modules/SagePay/config.tpl";

    function handleRequest(XLite_Model_Cart $cart)
    {
        require_once LC_MODULES_DIR . 'SagePay' . LC_DS . 'encoded.php';
        PaymentMethod_SagePayForm_handleRequest($this, $cart);
    }

	function getFormTemplate()
	{
		return "modules/SagePay/checkout.tpl";
	}

	function getSuccessUrl($order_id)
	{
		return $this->xlite->getShopUrl("cart.php?target=sagepayform_checkout&action=return", $this->getComplex('config.Security.customer_security'));
	}

	function getFailureUrl($order_id)
	{
		return $this->xlite->getShopUrl("cart.php?target=sagepayform_checkout&action=return&failed=1", $this->getComplex('config.Security.customer_security'));
	}

	function get($name)
	{
		if ($name == "params") {
			$pm = XLite_Model_PaymentMethod::factory('sagepaydirect_cc');
			return $pm->get("params");
		}
		if (preg_match("/order.*status/i", $name, $matches)) {
			$pm = XLite_Model_PaymentMethod::factory('sagepaydirect_cc');
			return $pm->get($matches[0]);
		}

		return parent::get($name);
	}


//////////// Fill "SagePay VSP Form" form methods ////////////
	function getVendorName()
	{
		return $this->getComplex('params.vendor_name');
	}

	function getFormPostUrl($is_simulator=false)
	{
		if ($is_simulator) {
            return "https://test.sagepay.com/Simulator/VSPFormGateway.asp";
		}
        $subtag = (($this->getComplex('params.testmode') == "N") ? "live" : "test");
		return "https://$subtag.sagepay.com/gateway/service/vspform-register.vsp";
	}

	function getCryptedInfo($cart)
	{
		require_once LC_MODULES_DIR . 'SagePay' . LC_DS . 'encoded.php';

		return func_SagePayForm_compileInfoCrypt($this, $cart);
	}

	function getPaymentType()
	{
		if (in_array($this->getComplex('params.trans_type'), array("PAYMENT", "DEFERRED", "AUTHENTICATE")))
			return $this->getComplex('params.trans_type');

		return "AUTHENTICATE";
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
