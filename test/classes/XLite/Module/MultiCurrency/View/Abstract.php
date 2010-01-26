<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
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
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
*	 
*
* @package Module_MultiCurrency
* @access public
* @version $Id$
*/

class XLite_Module_MultiCurrency_View_Abstract extends XLite_View_Abstract implements XLite_Base_IDecorator
{	
	public $currencies 		= null;	
	public $defaultCurrency 	= null;

	function getCurrencies() // {{{
	{
		if(is_null($this->currencies)) {
			$currency = new XLite_Module_MultiCurrency_Model_Currency();
			$this->currencies = $currency->findAll("enabled = 1 and base = 0");
		}
		return $this->currencies;

	} // }}}
	
	function getDefaultCurrency() // {{{ 
	{
		if (is_null($this->defaultCurrency)) { 
			$this->defaultCurrency = new XLite_Module_MultiCurrency_Model_Currency();
			$this->defaultCurrency->find("base = 1");
		}	
		return $this->defaultCurrency;
	} // }}}

	function price_format($base, $field = "", $thousand_delim = null, $decimal_delim = null) // {{{
	{
        $price_format 	= $this->config->getComplex('General.price_format');
        $price		 	= is_Object($base) ? $base->get($field) : $base;
		$default		= $this->get("defaultCurrency");
		$currencies 	= $this->get("currencies");
		
		$this->config->set("General.price_format",$default->get("price_format"));
		$result = parent::price_format($price, $field, $thousand_delim, $decimal_delim);
		if (!empty($currencies) && ($this->isTargetAllowed())) {
			$additional = "";
			foreach ($currencies as $currency) {
				$this->config->set("General.price_format",$currency->get("price_format"));
				$currency_price = $price * $currency->get('exchange_rate');
				$currency_price = parent::price_format($currency_price, $field, $thousand_delim, $decimal_delim);
				if ($this->auth->is('logged')&&$this->config->getComplex('MultiCurrency.country_currency')) {
					if ($currency->inCurrencyCountries($this->auth->getComplex('profile.billing_country')))
						$additional .= $currency_price . ", ";
				} else {
					$additional .= $currency_price . ", ";
				}
			}	
			if (!empty($additional)) $result .= " (" . substr($additional,0,-2) . ")";
		}

		return $result;
	} // }}}
	
	function isTargetAllowed() // {{{
	{
		$result = true;
		$target = $this->get("target");
		if ($this->xlite->is("adminZone")) {
			$page = $this->get("page");
			if ((in_array($target, array('order', 'create_order'))) && (in_array($page, array('order_info','order_preview')))) {
				$result = false;
			}
		} else {
			$exceptionTargets = array('checkoutSuccess', 'order');
			$result = !in_array($target, $exceptionTargets);
		}
		return $result;
	}  // }}}

} // }}}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
