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
/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

define('SHIPPING_CACHE_EXPIRATION', 3600*12); // half of a day

/**
* An abstract class for real-time shipping modules. 
*
* @package kernel
* @access public
* @version $Id$
*/
class XLite_Model_Shipping_Online extends XLite_Model_Shipping
{	
    public $optionsTable;

    public function getOptions()
    {
        
        $name = $this->configCategory;
        if (isset($this->config->$name)) {
            $options = $this->config->$name;
        } else {
            $options = new XLite_Base();
        }
        foreach ($this->optionsFields as $field) {
            if (!isset($options->$field)) {
                $options->$field = '';
            }
        }
        return $options;
    }

    function setOptions($options)
    {
        
        $c = new XLite_Model_Config();
        $category = $this->configCategory;
        $c->set("category", $category);
        $this->config->$category = new XLite_Base();
        foreach ($this->optionsFields as $field) {
            if (!isset($options->$field)) {
                continue;
            }
            $c->set("name", $field);
            $c->set("value", $options->$field);
            $this->config->$category->$field = $options->$field;
            if ($c->is("exists")) {
                $c->update();
            } else {
                $c->create();
            }
        }
    }
    
    function unserializeCacheRates($rates)
    {
        $result = array();
        $cart = XLite_Model_Cart::getInstance();
        $order = new XLite_Model_Order($cart->get("order_id"));
        $weight = (double) $order->get("weight");
        $total = (double) $order->calcSubTotal(true); // SubTotal for "shipped only" items
        $items = $order->get("shippedItemsCount");
        $zone = $this->getZone($order);

        if (!empty($rates)) {
            foreach (explode(',', $rates) as $rate) {
                list($shipping_id, $rate_value) = explode(':', $rate);
                $rateObject = new XLite_Model_ShippingRate($shipping_id);
                $rateObject->find($sql="(shipping_id=-1 OR shipping_id='$shipping_id') AND (shipping_zone=-1 OR shipping_zone='$zone') AND min_weight<=$weight AND max_weight>=$weight AND min_total<=$total AND min_items<=$items AND max_items>=$items AND max_total>$total", "shipping_id DESC, shipping_zone DESC");
                $rateObject->shipping = new XLite_Model_Shipping($shipping_id);
                if ($rateObject->shipping->is("exists") && $rateObject->shipping->is("enabled")) {
                    // haven't we remove this shipping?
                    $rateObject->rate = (double)$rate_value + (double)$rateObject->get("flat") + (double)$rateObject->get("per_item") * $items + (double)$rateObject->get("percent")*$total/100 + (double)$rateObject->get("per_lbs")*$weight;
                    $result[$rateObject->shipping->get("shipping_id")] = $rateObject;
                }    
            }
        }
        return $result;
    }

    function serializeCacheRates($rates)
    {
        $serialized = array();
        foreach ($rates as $rate) {
            $serialized[] = $rate->shipping->get('shipping_id') . ':' . $rate->rate;
        }
        return join(',', $serialized);
    }
    function _cleanCache($table)
    {
        $cacheTable = $this->db->getTableByAlias($table);
        $this->db->query("DELETE FROM $cacheTable");
    }

    function _checkCache($table, $fields)
    {
        $cacheTable = $this->db->getTableByAlias($table);
        // garbage collection
		if ($this->get("shippingCacheExpiration") > 0) {
	        $this->db->query("DELETE FROM $cacheTable WHERE date<".(time()-$this->get("shippingCacheExpiration"))); 
		}

        // check cache for fresh values
        $condition = array();
        foreach ($fields as $key => $value) {
            $condition[] = "$key='".addslashes($value)."'";
        }
        $condition = join(" AND ", $condition);
        $cacheRow = $this->db->getRow("SELECT rates FROM $cacheTable WHERE $condition");
        if (!is_null($cacheRow)) {
            return $this->unserializeCacheRates($cacheRow["rates"]);
        }
        return false;
    }

    /**
    * store shipping rates in the cache    
    */
    function _cacheResult($table, $fields, &$rates)
    {
        $cacheTable = $this->db->getTableByAlias($table);
        $values = array();
        foreach ($fields as $value) {
            $values[] = "'".addslashes($value)."'";
        }
        
        $this->db->query("REPLACE INTO $cacheTable " . 
                     "(" . join(",", array_keys($fields)) .  ",date,rates)".
                     " VALUES (" . join(",", $values) . ",".time().",'" . 
                     $this->serializeCacheRates($rates) . "')");
    }
    

    /**
    * Obtains the order's weight, in ounces.
    */
    function getOunces($order)
    {
        
        $w = $order->get("weight");
        switch ($this->config->getComplex('General.weight_unit')) {
        case 'lbs': return ceil($w*16.0);
        case 'oz':  return ceil($w*1.0);
        case 'kg':  return ceil($w*35.2740);
        case 'g':   return ceil($w*0.035274);
        }
        return 0;
    }

    /**
    * Strip off the last 4 digits from an extended US ZIP format #####-####
    */
    function _normalizeZip($zip)
    {
        if (preg_match("/([0-9]{5,5})[- ]+[0-9]{4,4}/", $zip, $result)) {
            return $result[1];
        }
        
        return preg_replace("/[ -]/", "", $zip);
    }
    
	function getShippingCacheExpiration()
	{
		return SHIPPING_CACHE_EXPIRATION;
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
