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
* @package Module_HSBC
* @access public
* @version $Id$
*/
class XLite_Module_HSBC_Model_PaymentMethod_CcHsbc extends XLite_Model_PaymentMethod_CreditCard
{	
	public $configurationTemplate = "modules/HSBC/config.tpl";	
	public $formTemplate = "modules/HSBC/checkout.tpl";	
	public $processorName = "HSBC";	
	public $hasConfigurationForm = true;	
	public $timestamp = null;	
	public $merchant_data = null;

    function handleRequest(XLite_Model_Cart $cart)
    {
		require_once LC_MODULES_DIR . 'HSBC' . LC_DS . 'encoded.php';
        func_PaymentMethod_cc_hsbc_handleRequest($this, $cart);
    }

	function getUserId($cart)
	{	
		require_once LC_MODULES_DIR . 'HSBC' . LC_DS . 'encoded.php';
        return func_PaymentMethod_cc_hsbc_getUserId($this, $cart);
	}

	function getTransactionType()
	{
		return ($this->getComplex('params.param05') == "capture") ? "Capture" : "Auth";
	}

	function getHash($cart)
	{
		require_once LC_MODULES_DIR . 'HSBC' . LC_DS . 'encoded.php';

		// Merchant payment params
		$hsbc_storefrontid = $this->getComplex('params.param01');
		$hsbc_hashkey = $this->getComplex('params.param02');
		$hsbc_mode = $this->getComplex('params.param03'); 
		$hsbc_currency = $this->getComplex('params.param04');
		$hsbc_trans_type = $this->get("transactionType");
		// additional params
		$MerchantData = $this->getMerchantData($cart);
		// data to be crypted	
		$post_data = array(
			"CpiDirectResultUrl"	=> $this->getResultURL(),
			"CpiReturnUrl"			=> $this->getReturnURL($cart),
			"MerchantData"			=> $this->getMerchantData($cart),
			"Mode"					=> $hsbc_mode,
			"OrderDesc"				=> "LiteCommerce order",
			"OrderId"				=> $cart->get("order_id"),
			"PurchaseAmount"		=> $this->getTotalCost($cart),
			"PurchaseCurrency"		=> $hsbc_currency,
			"StorefrontId"			=> $hsbc_storefrontid,
			"TimeStamp"				=> $this->getTimeStamp(),
			"TransactionType"		=> $hsbc_trans_type,
			"UserId"				=> $this->getUserId($cart)
		);


		$billing_info = array(
			"BillingAddress1" 		=> $cart->getComplex('profile.billing_address'),
			"BillingCity" 			=> $cart->getComplex('profile.billing_city'),
			"BillingCountry" 		=> $this->getIsoCode("billing_country", $cart),
			"BillingCounty" 		=> $this->getBillingState($cart),
			"BillingFirstName" 		=> $cart->getComplex('profile.billing_firstname'),
			"BillingLastName" 		=> $cart->getComplex('profile.billing_lastname'),
			"BillingPostal" 		=> $cart->getComplex('profile.billing_zipcode'),
			"ShopperEmail" 			=> $cart->getComplex('profile.login')
		);

		$shipping_info = array (
			"ShippingAddress1"		=> $cart->getComplex('profile.shipping_address'),
			"ShippingCity" 			=> $cart->getComplex('profile.shipping_city'),
			"ShippingCountry" 		=> $this->getIsoCode("shipping_country", $cart),
			"ShippingCounty" 		=> $this->getShippingState($cart),
			"ShippingFirstName" 	=> $cart->getComplex('profile.shipping_firstname'),
			"ShippingLastName" 		=> $cart->getComplex('profile.shipping_lastname'),
			"ShippingPostal" 		=> $cart->getComplex('profile.shipping_zipcode')
		);

		$post = array_merge($post_data, $billing_info, $shipping_info);

		// UNIX. add library path to find required libraries
		putenv("LD_LIBRARY_PATH=" . getenv("LD_LIBRARY_PATH") . ":./classes/modules/HSBC/bin");

		$order_line = array();
		foreach ((array)$post as $key=>$val) {
			$order_line[$key] = escapeshellarg($val);
		}
		$order_line = join(" ", $order_line);

		$path = func_PaymentMethod_cc_hsbc_getCwd($this);
		$bin_exec = $path."/classes/modules/HSBC/bin/TestHash.e";
        if(!is_executable($bin_exec)) {
            @chmod($bin_exec, 0755);
        }
		// execute external binary to get hash key value
        $os_name = substr(php_uname(),0,strpos(php_uname(),' '));
        $os_code = strtolower(substr($os_name,0,3));
        if ($os_code == "win") {
        	$bin_exec = str_replace("/", "\\", $bin_exec);
        }
		exec($bin_exec . " " . escapeshellarg($hsbc_hashkey) . " " . $order_line, $data);
		$data = $data[0];

		if(!preg_match("/^Hash value:  (.*)$/", $data, $hash_result)) {
			return false;
		} else {
			return $hash_result[1];
		}
	}

	function getIsoCode($field, $cart)
	{
		// return country ISO code
		// ISO codes array {{{
		$iso_codes = array(	
			'AF' => '004',
			'AL' => '008',
			'DZ' => '012',
			'AS' => '016',
			'AD' => '020',
			'AO' => '024',
			'AI' => '660',
			'AQ' => '010',
			'AG' => '028',
			'AR' => '032',
			'AM' => '051',
			'AW' => '533',
			'AU' => '036',
			'AT' => '040',
			'AZ' => '031',
			'BS' => '044',
			'BH' => '048',
			'BD' => '050',
			'BB' => '052',
			'BY' => '112',
			'BE' => '056',
			'BZ' => '084',
			'BJ' => '204',
			'BM' => '060',
			'BT' => '064',
			'BO' => '068',
			'BA' => '070',
			'BW' => '072',
			'BV' => '074',
			'BR' => '076',
			'IO' => '086',
			'BN' => '096',
			'BG' => '100',
			'BF' => '854',
			'BI' => '108',
			'KH' => '116',
			'CM' => '120',
			'CA' => '124',
			'CV' => '132',
			'KY' => '136',
			'CF' => '140',
			'TD' => '148',
			'CL' => '152',
			'CN' => '156',
			'CX' => '162',
			'CC' => '166',
			'CO' => '170',
			'KM' => '174',
			'CG' => '178',
			'CK' => '184',
			'CR' => '188',
			'CI' => '384',
			'HR' => '191',
			'CU' => '192',
			'CY' => '196',
			'CZ' => '203',
			'DK' => '208',
			'DJ' => '262',
			'DM' => '212',
			'DO' => '214',
			'EC' => '218',
			'EG' => '818',
			'SV' => '222',
			'GQ' => '226',
			'ER' => '232',
			'EE' => '233',
			'ET' => '210',
			'FK' => '238',
			'FO' => '234',
			'FJ' => '242',
			'FI' => '246',
			'FR' => '250',
			'FX' => '249',
			'GF' => '254',
			'PF' => '258',
			'TF' => '260',
			'GA' => '266',
			'GM' => '270',
			'GE' => '268',
			'DE' => '276',
			'GH' => '288',
			'GI' => '292',
			'GR' => '300',
			'GL' => '304',
			'GD' => '308',
			'GP' => '312',
			'GU' => '316',
			'GT' => '320',
			'GN' => '324',
			'GW' => '624',
			'GY' => '328',
			'HT' => '332',
			'HM' => '334',
			'HN' => '340',
			'HK' => '344',
			'HU' => '348',
			'IS' => '352',
			'IN' => '356',
			'ID' => '360',
			'IR' => '364',
			'IQ' => '368',
			'IE' => '372',
			'IL' => '376',
			'IT' => '380',
			'JM' => '388',
			'JP' => '392',
			'JO' => '400',
			'KZ' => '398',
			'KE' => '404',
			'KI' => '296',
			'KP' => '408',
			'KR' => '410',
			'KW' => '414',
			'KG' => '417',
			'LA' => '418',
			'LV' => '428',
			'LB' => '422',
			'LS' => '426',
			'LR' => '430',
			'LY' => '434',
			'LI' => '438',
			'LT' => '440',
			'LU' => '442',
			'MO' => '446',
			'MK' => '807',
			'MG' => '450',
			'MW' => '454',
			'MY' => '458',
			'MV' => '462',
			'ML' => '466',
			'MT' => '470',
			'MH' => '584',
			'MQ' => '474',
			'MR' => '478',
			'MU' => '480',
			'YT' => '175',
			'MX' => '484',
			'FM' => '583',
			'MD' => '498',
			'MC' => '492',
			'MN' => '496',
			'MS' => '500',
			'MA' => '504',
			'MZ' => '508',
			'MM' => '104',
			'NA' => '516',
			'NR' => '520',
			'NP' => '524',
			'NL' => '528',
			'AN' => '530',
			'NC' => '540',
			'NZ' => '554',
			'NI' => '558',
			'NE' => '562',
			'NG' => '566',
			'NU' => '570',
			'NF' => '574',
			'MP' => '580',
			'NO' => '578',
			'OM' => '512',
			'PK' => '586',
			'PW' => '585',
			'PA' => '591',
			'PG' => '598',
			'PY' => '600',
			'PE' => '604',
			'PH' => '608',
			'PN' => '612',
			'PL' => '616',
			'PT' => '620',
			'PR' => '630',
			'QA' => '634',
			'RE' => '638',
			'RO' => '642',
			'RU' => '643',
			'RW' => '646',
			'KN' => '659',
			'LC' => '662',
			'VC' => '670',
			'WS' => '882',
			'SM' => '674',
			'ST' => '678',
			'SA' => '682',
			'SN' => '686',
			'SC' => '690',
			'SL' => '694',
			'SG' => '702',
			'SK' => '703',
			'SI' => '705',
			'SB' => '90',
			'SO' => '706',
			'ZA' => '710',
			'ES' => '724',
			'LK' => '144',
			'SH' => '654',
			'PM' => '666',
			'SD' => '736',
			'SR' => '740',
			'SJ' => '744',
			'SZ' => '748',
			'SE' => '752',
			'CH' => '756',
			'SY' => '760',
			'TW' => '158',
			'TJ' => '762',
			'TZ' => '834',
			'TH' => '764',
			'TG' => '768',
			'TK' => '772',
			'TO' => '776',
			'TT' => '780',
			'TN' => '788',
			'TR' => '792',
			'TM' => '795',
			'TC' => '796',
			'TV' => '798',
			'UG' => '800',
			'UA' => '804',
			'AE' => '784',
			'GB' => '826',
			'US' => '840',
			'UM' => '581',
			'UY' => '858',
			'UZ' => '860',
			'VU' => '548',
			'VA' => '336',
			'VE' => '862',
			'VN' => '704',
			'VG' => '92',
			'VI' => '850',
			'WF' => '876',
			'EH' => '732',
			'YE' => '887',
			'YU' => '891',
			'ZR' => '180',
			'ZM' => '894',
			'ZW' => '716',
		);
		// }}}
		return $iso_codes[$cart->get('profile.' . $field)];
	}

	function getTimeStamp()
	{
		if (null === $this->timestamp) {
			$this->timestamp = time() . "000";
		}
		return $this->timestamp;
	}

	function getMerchantData($cart)
	{
		if (null === $this->merchant_data) {
			$this->merchant_data = $this->session->getId();
			$cart->setComplex("details.secure_id", $this->merchant_data);
			$cart->update();
		}	
		return $this->merchant_data;
	}

	function getTotalCost($cart)
	{
		$hsbc_currency = $this->getCurrency();
		return $cart->get("total") * (($hsbc_currency!="392")? 100 : 1);
	}

	function getCurrency()
	{
		return $this->getComplex('params.param04');
	}

	function getHsbcMode()
	{
		return $this->getComplex('params.param03');	
	}

	function getStoreFrontId()
	{
		return $this->getComplex('params.param01');
	}

	function getBillingState($cart)
	{
		return $cart->getComplex('profile.billing_state') ? $cart->getComplex('profile.billingState.state') : "n/a";
	}

	function getShippingState($cart)
	{
		return $cart->getComplex('profile.shipping_state') ? $cart->getComplex('profile.shippingState.state') : "n/a";
	}

	function getResultURL()
	{
		return $this->getShopUrl("cart.php?target=callback&action=callback&order_id_name=OrderId", true, true);
	}

	function getReturnURL($cart)
	{
        $oid = $cart->get("order_id");
		return $this->getShopUrl("cart.php?target=checkout&action=return&order_id=$oid", true, true);
	}

	function handleConfigRequest()
	{
		$params = $_POST["params"];
		$subparams = $this->get("params");

		$statuses = array("processed", "queued", "failed");
		foreach ($statuses as $name) {
			$field = 'status_'.$name;
			if ($this->xlite->AOMEnabled) {
				$status = new XLite_Module_AOM_Model_OrderStatus();
				$status->find("status='".$params[$field]."'");

				$params[$field] = (($status->get("parent")) ? $status->get("parent") : $status->get("status"));
				$params["sub".$field] = $status->get("status");
			} else {
				$subparams = $this->get("params");
				$params["sub".$field] = $subparams["sub".$field];
			}
		}

		$_POST["params"] = $params;

		parent::handleConfigRequest();
	}

	function getStatusCode($name)
	{
		$params = $this->get("params");
		$result = $params[$name];
		if ($params["sub".$name] && $this->xlite->AOMEnabled) {
			$status = new XLite_Module_AOM_Model_OrderStatus();
			if ($status->find("status='".$params["sub".$name]."' AND parent='".$params[$name]."'")) {
				$result = $params["sub".$name];
			}
		}

		return $result;
	}

	function getShopUrl($url, $secure = false, $pure_url = false)
	{
		$url = $this->xlite->getShopUrl($url, $secure, $pure_url);
		if ($pure_url) {
			$sid = $this->session->getName() . "=" . $this->session->getID();
			if (strpos($url, $sid) !== false) {
				if (strpos($url, $sid . "&") !== false) {
					$sid = $sid . "&";
				}
				$url = str_replace($sid, "", $url);
				$lastSymbol = substr($url, strlen($url)-1, 1);
				if ($lastSymbol == "?" || $lastSymbol == "&") {
					$url = substr($url, 0, strlen($url)-1);
				}
			}
		}

		return $url;
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
