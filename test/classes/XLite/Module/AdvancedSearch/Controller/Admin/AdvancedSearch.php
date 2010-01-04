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
* @package AdvancedSearch 
* @access public
* @version $Id$
*/

class XLite_Module_AdvancedSearch_Controller_Admin_AdvancedSearch extends XLite_Controller_Admin_Abstract
{
	function getAllPrices() // {{{ 
	{
		return unserialize($this->config->get("AdvancedSearch.prices"));
	} // }}}

	function getAllWeights() // {{{ 
	{
        return unserialize($this->config->get("AdvancedSearch.weights"));
	} // }}}	
	
	function action_update() // {{{ 
	{	
	    $config = new XLite_Model_Config();
		$config->set('category','AdvancedSearch');
		if (isset($this->prices)) {
			$config->set('name','prices');
			$config->set('value',serialize($this->prices));
		}
 	
	    if (isset($this->weights)) {
            $config->set('name','weights');
            $config->set('value',serialize($this->weights));
        }

		$config->update();
	} // }}}
	
	function action_delete() // {{{ 
	{
		if (isset($this->deleted_prices)) {
		    $prices = unserialize($this->config->get('AdvancedSearch.prices'));
			foreach($this->deleted_prices as $key => $value) {
				unset($prices[$value]);	
			}
            $config = new XLite_Model_Config();
            $config->set('category','AdvancedSearch');
            $config->set('name','prices');
            $config->set('value',serialize($prices));
            $config->update();
		}
        if (isset($this->deleted_weights)) {
            $weights = unserialize($this->config->get('AdvancedSearch.weights'));
            foreach($this->deleted_weights as $key => $value) {
                unset($weights[$value]);
            }
            $config = new XLite_Model_Config();
            $config->set('category','AdvancedSearch');
            $config->set('name','weights');
            $config->set('value',serialize($weights));
            $config->update();
        }
	} // }}}

	function action_add() // {{{ 
	{
		if (isset($this->new_price) && is_array($this->new_price) && strlen($this->new_price["start"]) > 0 && strlen($this->new_price["end"]) > 0) { 
			$prices = unserialize($this->config->get("AdvancedSearch.prices"));
		    $prices[] = $this->new_price;
			$config = new XLite_Model_Config();
			$config->set('category','AdvancedSearch');
			$config->set('name','prices');
			$config->set('value',serialize($prices));
			$config->update();
		}
        if (isset($this->new_weight) && is_array($this->new_weight) && strlen($this->new_weight["start"]) > 0 && strlen($this->new_weight["end"]) > 0) { 
            $weights = unserialize($this->config->get("AdvancedSearch.weights"));
            $weights[] = $this->new_weight;
            $config = new XLite_Model_Config();
            $config->set('category','AdvancedSearch');
            $config->set('name','weights');
            $config->set('value',serialize($weights));
            $config->update();
        }
	} // }}}
}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
