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

class Admin_Dialog_currencies extends Admin_Dialog // {{{ 
{ 
	
	var $params = array("target");
	var $countries = null;
	var $allCurrencies = null;
	var $defaultCurrency = null;

	function &getDefaultCurrency() // {{{
	{
		if (is_null($this->defaultCurrency)) {
	        $this->defaultCurrency =& func_new("CurrencyCountries");
       		$found = $this->defaultCurrency->find("base = 1");
			if (!$found) {
				$this->defaultCurrency =& func_new("CurrencyCountries");
				$this->defaultCurrency->set("code","USD");
				$this->defaultCurrency->set("name","US dollar");
				$this->defaultCurrency->set("exchange_rate",1);
				$this->defaultCurrency->set("price_format",$this->config->get("General.price_format"));
				$this->defaultCurrency->set("base",1);
				$this->defaultCurrency->set("enabled",1);
				$this->defaultCurrency->set("countries",serialize(array()));
				$this->defaultCurrency->create();
			}
		}
		return $this->defaultCurrency;
	} // }}}

	function &getAllCurrencies() // {{{ 
	{
        if (is_null($this->allCurrencies)) {
            $currency = & func_new("CurrencyCountries");
            $this->allCurrencies = $currency->findAll("base = 0");
        }
        return $this->allCurrencies;
	} // }}

	function action_update_default() // {{{ 
	{
		$currency =& $this->get("defaultCurrency");
		$properties = $this->currency;
		$currency->set("code",$properties['code']);
	    $currency->set("name",$properties['name']);
    	$currency->set("price_format",$properties['price_format']);
		$currency->update();

	} // }}}
	
	function &getCountries() // {{{ 
	{
		if (is_null($this->countries)) {
			$country =& func_new("Country");
			$this->countries = $country->findAll("enabled = 1");	
		}		
		return $this->countries;
	} // }}}
	
	function action_add() // {{{ 
	{
		$currency =& func_new("CurrencyCountries");
		$properties = $this->currency;
		$properties['countries'] = serialize(isset($properties['countries']) ? $properties['countries'] : array());
		$properties['enabled'] = "1";
		$currency->set("properties",$properties);
		$currency->create();

	} // }}}
	
	function action_update() // {{{ 
	{
		foreach($this->currencies as $currency_) {
			$currency =& func_new("CurrencyCountries",$currency_["currency_id"]);
			$currency_['countries'] = serialize(isset($currency_['countries']) ? $currency_['countries'] : array());
        	$currency_['enabled'] = isset($currency_['enabled']) ? "1" : "0";
			$currency->set("properties",$currency_);
			$currency->update();
		}

	} // }}}

	function action_delete() // {{{
	{
		if (isset($this->deleted)) { 
			foreach($this->deleted as $currency_id) {
				$currency = func_new("CurrencyCountries",$currency_id);
				$currency->delete();
			}
		}
	} // }}}

} // }}}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
