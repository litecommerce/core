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
* eSelect processor unit. This implementation complies the following
*
* @package Module_eSelect
* @access public
* @version $Id: eselect_cc.php,v 1.12 2008/10/23 12:06:15 sheriff Exp $
*/

class PaymentMethod_eselect_cc extends PaymentMethod_credit_card
{
	var $configurationTemplate = "modules/eSelect/config.tpl";
    var $hasConfigurationForm = true;
    var $processorName = "eSelect";
	
    function process(&$cart)
	{
		require_once('modules/eSelect/encoded.php');
		func_eSelect_process($cart, $this);
	}

	function prepareUrl($url)
	{
		$url = htmlspecialchars($url);

		return $url;
	}

	function getReturnUrl() // {{{ 
	{
		$url = $this->xlite->shopURL("cart.php?target=eselect_checkout&action=return", $this->get("config.Security.customer_security"));
		return $this->prepareUrl($url);
	}   // }}}

	function getAVSMessageText($code)
	{
		$avs_messages = array(
			"A"	=> "Street addresses match. The street addresses match but the postal/ZIP codes do not, or the request does not include the postal/ZIP code.",
			"B"	=> "Street addresses match. Postal code not verified due to incompatible formats. (Acquirer sent both street address and postal code.)",
			"C"	=> "Street address and postal code not verified due to incompatible formats. (Acquirer sent both street address and postal code.)",
			"D"	=> "Street addresses and postal codes match.",
			"G"	=> "Address information not verified for international transaction.",
			"I"	=> "Address information not verified.",
			"M"	=> "Street address and postal code match.",
			"N"	=> "No match. Acquirer sent postal/ZIP code only, or street address only, or both postal code and street address.",
			"P"	=> "Postal code match. Acquirer sent both postal code and street address, but street address not verified due to incompatible formats.",
			"R"	=> "Retry: System unavailable or timed out. Issuer ordinarily performs its own AVS but was unavailable. Available for U.S. issuers only.",
			"S"	=> "Not applicable. If present, replaced with G (for international) or U (for domestic) by V.I.P. Available for U.S. Issuers only.",
			"U"	=> "Address not verified for domestic transaction. Visa tried to perform check on issuers behalf but no AVS information was available on record, issuer is not an AVS participant, or AVS data was present in the request but issuer did not return an AVS result.",
			"W"	=> "Not applicable. If present, replaced with Z by V.I.P. Available for U.S. issuers only.",
			"X"	=> "Not applicable. If present, replaced with Y by V.I.P. Available for U.S. issuers only.",
			"Y"	=> "Street address and postal code match.",
			"Z"	=> "Postal/ZIP matches; street address does not match or street address not included in request.",
		);

		$ucode = strtoupper($code);
		return ((isset($avs_messages[$ucode])) ? $avs_messages[$ucode] : $code);
	}

	function getCVDMessageText($code)
	{
		$cvd_messages = array(
			"M"	=> "Match",
			"N"	=> "No Match",
			"P"	=> "Not Processed",
			"S"	=> "CVD should be on the card, but Merchant has indicated that CVD is not present",
			"U"	=> "Issuer is not a CVD participant"
		);

		$ucode = strtoupper($code);
		$message = "($ucode) ";
		if (preg_match("/[MNPSU]/",$ucode, $match)) {
			$message .= ((isset($cvd_messages[$match[0]])) ? $cvd_messages[$match[0]] : "");
		}
		return $message;
	}

	function getMonerisMPG_URL()
	{
		$url = "";
		if ($this->get("params.account_type") == "CA")
		{
			$url = "https://".(($this->get("params.testmode") == "Y") ? "esqa.moneris.com" : "www3.moneris.com").":443/gateway2/servlet/MpgRequest";
		} else {
			$url ="https://".(($this->get("params.testmode") == "Y") ? "esplusqa" : "esplus").".moneris.com:443/gateway_us/servlet/MpgRequest";
		}

		return $url;
	}

	function getMonerisMPI_URL()
	{
		$url = "";
		if ($this->get("params.account_type") == "CA") {
        	$url = "https://".(($this->get("params.testmode") == "Y") ? "esqa" : "www3").".moneris.com:443/mpi/servlet/MpiServlet";
		} else {
			$url = "https://".(($this->get("params.testmode") == "Y") ? "esplusqa" : "esplus").".moneris.com:443/mpi/servlet/MpiServlet";
		}

		return $url;
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

	function getOrderSuccessStatus()
	{
		return $this->getOrderStatus("success", "P");
	}

	function getOrderFailStatus()
	{
		return $this->getOrderStatus("fail", "F");
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
