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
* Netbilling processor unit. This implementation complies the following
* documentation: http://www.netbilling.com/
*
* @package Module_Netbilling
* @access public
* @version $Id$
*/
class XLite_Module_Netbilling_Model_PaymentMethod_NetbillingCc extends XLite_Model_PaymentMethod_CreditCard
{
    var $processorName = "Netbilling";
	var $configurationTemplate = "modules/Netbilling/config.tpl";
	var $hsaCongifurationForm = true;
    
    function process($cart)
    {
		require_once LC_MODULES_DIR . 'Netbilling' . LC_DS . 'encoded.php';
        return func_Netbilling_processor_process($cart, $this);
    }

	function getSucessedStatus()
	{
		return "P";
	}

	function getFailedStatus()
	{
		return "F";
	}

	function getQueuedStatus()
	{
		return "Q";
	}

	function getAVSString($code)
	{
		$code = strtoupper(urldecode($code));
		if (!$code || $code == "-") {
			return "No AVS data";
		}

		$codes = array(
			"XYFDM" => "Address and ZIP code match",
			"WZ"    => "ZIP code match, address is wrong",
			"ABP"   => "Address match, ZIP code is wrong",
			"N" => "No match, address and ZIP code are wrong",
			"U" => "No data from issuer/banknet switch",
			"R" => "System unable to process",
			"S" => "Address verification not supported",
			"E" => "Error, AVS not supported for your business",
			"?" => "Unrecognized (none of the above) response codes",
			"C" => "(Intl) Invalid address and ZIP format",
			"I" => "(Intl) Address not verifiable",
			"O" => "(Intl) No response from bank",
			"G" => "(Intl) Global non-verifiable address"
		);

		foreach ($codes as $k=>$v) {
			if (strpos($k, $code) !== false) {
				return "($code) $v";
			}
		}

		return "($code) Unknown AVS code";
	}

	function getCVV2String($code)
	{
		$code = strtoupper(urldecode($code));
		if (!$code || $code == "-") {
			return "No CVV2 data";
		}

		$codes = array(
			"M" => "CVV2 match",
			"P" => "CVV2 not processed",
			"U" => "No CVV2 data from issuer",
			"N" => "CVV2 does not match",
			"S" => "Card has CVV2, customer says it doesn't",
			"?"	=> "No card verification data"
		);

		foreach ($codes as $k=>$v) {
			if (strpos($k, $code) !== false) {
				return "($code) $v";
			}
		}

		return "($code) Unknown CVV2 code";
	}


	function initRequest($cart, &$request) 
	{
		if ($this->xlite->get("cc_initRequestAlternate")) {
			$_object = new XLite_Module_CardinalCommerce_Model_PaymentMethodNetbillingCc();
			$_object->set("CardinalMPI", $this->get("CardinalMPI"));
			$_object->cc_info = $this->cc_info;
			$_object->initRequest($cart, $request);
		}
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
