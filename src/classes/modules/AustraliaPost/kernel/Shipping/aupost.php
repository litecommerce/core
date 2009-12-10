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
* @package AustraliaPost
* @access public
* @version $Id: aupost.php,v 1.3 2008/10/23 11:51:53 sheriff Exp $
*/
class Shipping_aupost extends Shipping_online // {{{ 
{

	var $configCategory = "AustraliaPost";
	var $optionsFields	= array("length","width","height","currency_rate");
    var $error = "";
    var $xmlError = false;

    function getWeightInGrams($order, $weight_unit=null) // {{{
    {
        $weight = (is_object($order)) ? $order->get("weight") : $order;
        $weight_unit = (isset($weight_unit)) ? $weight_unit : $this->config->get("General.weight_unit");

        switch ($weight_unit) {
        	case 'lbs': 
        		return $weight*453.0;
        	case 'oz':  
        		return $weight*28.31;
        	case 'kg':  
        		return $weight*1000.0;
        	case 'g':   
        		return $weight;
        }
        return 0;
    } // }}}

	function cleanCache() // {{{ 
	{
		$this->_cleanCache("aupost_cache");
	} // }}}

    function getModuleName() // {{{ 
    {
        return "Australia Post";
    } // }}} 

	function &getRates($order) // {{{
	{
		include_once "modules/AustraliaPost/encoded.php";
		return Shipping_aupost_getRates($this,$order);
	} // }}}
	
	function &queryRates($options, $originalZipcode, $destinationZipcode, $destinationCountry, $weight, $weight_unit=null) // {{{
	{
		include_once "modules/AustraliaPost/encoded.php";
		return Shipping_aupost_queryRates($this, $options, $originalZipcode, $destinationZipcode, $destinationCountry, $weight, $weight_unit);
	} // }}}

	function &parseResponse($rates_data, $destination) // {{{
	{
		include_once "modules/AustraliaPost/encoded.php";
		return Shipping_aupost_parseResponse($this, $rates_data, $destination);

	} // }}} 

    function &get($property)
	{
		if ($property == "name" && isset($this->shipping_time)) {
			return parent::get("$property") . " (" . $this->shipping_time . " day" . (($this->shipping_time > 1) ? "s" : "") . ")";
		} else {
			return parent::get($property);
		}
	}

    function _cacheResult($table, $fields, &$rates)
    {
    	parent::_cacheResult($table, $fields, $rates);

        $cacheTable = $this->db->getTableByAlias($table);
        $values = array();
        foreach ($fields as $field => $value) {
            $values[] = "$field='".addslashes($value)."'";
        }
        $values = implode(" AND ", $values);

        $serialized = array();
        foreach ($rates as $rate) {
            $serialized[] = $rate->shipping->get("shipping_id") . ":" . $rate->shipping->shipping_time;
        }
        $serialized = implode(",", $serialized);

        $this->db->query("UPDATE $cacheTable SET shipping_dates='$serialized' WHERE $values");
    }

    function _checkCache($table, $fields)
    {
    	$result = parent::_checkCache($table, $fields);
    	if ($result !== false) {
        	$cacheTable = $this->db->getTableByAlias($table);
            $condition = array();
            foreach ($fields as $key => $value) {
                $condition[] = "$key='".addslashes($value)."'";
            }
            $condition = join(" AND ", $condition);
            $cacheRow = $this->db->getRow("SELECT shipping_dates FROM $cacheTable WHERE $condition");
            if (!is_null($cacheRow)) {
            	$shipping_dates = explode(",", $cacheRow["shipping_dates"]);
            	foreach($result as $shr_key => $shr) {
            		$id = $shr->shipping->get("shipping_id");
            		foreach($shipping_dates as $shd) {
            			$shd = explode(":", $shd);
            			if ($id == $shd[0]) {
            				$result[$shr_key]->shipping->shipping_time = $shd[1];
            				break;
            			}
            		}
            	}
            }
    	}

    	return $result;
    }
} // }}} 

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
