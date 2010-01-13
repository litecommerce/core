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
* BeanStream class unit.
*
* @package BeanStream
* @access public
*
* @version $Id$
*/

class XLite_Module_BeanStream_Model_PaymentMethod_BeanstreamCc extends XLite_Model_PaymentMethod_CreditCard
{
    var $configurationTemplate = 'modules/BeanStream/config.tpl';
    var $processorName = 'BeanStream';

    function process($cart)
    {
		require_once LC_MODULES_DIR . 'BeanStream' . LC_DS . 'encoded.php';
        return BeanStream_processor_process($this, $cart);
    }

    function handleConfigRequest()
    {
		$params = $_POST['params'];
		$pm = XLite_Model_PaymentMethod::factory('beanstream_cc');
		$pm->set('params', $params);
		$pm->update();
    }

	function getPurchaseUrl()
	{
		return "https://www.beanstream.com:443/scripts/process_transaction.asp";
	}

	function getAuthorizationUrl()
	{
		return "https://www.beanstream.com:443/scripts/process_transaction_auth.asp";
	}

    function initRequest($cart, &$request)
    {
        $request->data["trnCardNumber"] = $this->cc_info["cc_number"];
        $request->data["trnExpMonth"]   = substr($this->cc_info["cc_date"],0,2);
		$request->data["trnExpYear"]    = substr($this->cc_info["cc_date"],2,2);
		$request->data["trnCardOwner"]  = trim($this->cc_info["cc_name"]);
        $request->data["trnCardCvd"]    = $this->cc_info["cc_cvv2"];
    }

	function getOrderStatus($type, $default = 'Q') {
		$param = "status_$type";
		$params = $this->get("params");
		if ($params["sub$param"] && $this->xlite->AOMEnabled) {
			return $params["sub$param"];
		} elseif ($params[$param]) {
			return $params[$param];
		} else {
			return $default;
		}
	}

	function getOrderSuccessStatus() // {{{
	{
//		return $this->getOrderStatus("success", "P");
		return "P";
	}

	function getOrderFailStatus() // {{{
	{
//		return $this->getOrderStatus("fail", "F");
		return "F";
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
