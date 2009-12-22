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
* @package Module_EcommerceReports
* @access public
* @version $Id$
*/
class Admin_dialog_geographic_sales extends Admin_dialog_product_sales
{
    function getGeoSales() // {{{
    {
        if (is_null($this->geoSales)) {
            $this->geoSales = array();
            $items = $this->get("rawItems");
            // summarize
            array_map(array(&$this, 'sumProductSales'), $items);
            // sort
            foreach ($this->geoSales as $gl => $gs) {
                $productSales = $this->geoSales[$gl];
                usort($productSales, array(&$this, "cmpProducts"));
                $ps = array_reverse($productSales);
                $this->geoSales[$gl] = $ps;
            }    
        }
        return $this->geoSales;
    } // }}}

    function getInCountries($table) // {{{
    {
        $countryCodes = (array) $this->get("country_codes");
        if (!count($countryCodes)) {
            return parent::getInCountries($table);
        }
        foreach ($countryCodes as $idx => $code) {
            $countryCodes[$idx] = "'".$code."'";
        }
        $codes = implode(',', $countryCodes);
        $prefix = $this->get("group_by");
        return " AND {$table}.{$prefix}_country IN ($codes) ";
    } // }}}

    function getInStates($table) // {{{
    {
        $stateIds = (array) $this->get("state_ids");
        if (!count($stateIds)) {
            return parent::getInStates($table);
        }
		if (in_array(-1, $stateIds)) {
			array_push($stateIds, 0);
		}
        $ids = implode(',', $stateIds);
        $prefix = $this->get("group_by");
        return " AND {$table}.{$prefix}_state IN ($ids) ";
    } // }}}
    
    function sumProductSales($item) // {{{
    {
        $gid = $this->getGeoIndex($item);        
        if (!isset($this->geoSales[$gid])) {
            $this->geoSales[$gid] = array();
        }    
        $productSales = $this->geoSales[$gid];
        $id = $item["product_id"] . (strlen($item["options"]) ? md5($item["options"]) : "");
		$orderItem = func_new("OrderItem");
		$orderItem->find("order_id=".$item["order_id"]." AND item_id='".addslashes($item["item_id"])."'");
		$order = func_new("Order", $item["order_id"]);
		$orderItem->set("order", $order);
		$item["price"] = $orderItem->get("price");
        if (!isset($productSales[$id])) {
            $productSales[$id] = $item;
            $productSales[$id]["total"] = 0;
            $productSales[$id]["order_item"] = $orderItem;
        } else {
            $productSales[$id]["amount"] += $item["amount"];
        }
        $productSales[$id]["total"] += $item["amount"] * $item["price"];
        $productSales[$id]["avg_price"] = $productSales[$id]["total"] / $productSales[$id]["amount"];
    } // }}}
    
    function getGeoIndex($item) // {{{
    {
        $prefix = $this->get("group_by");
        if (!is_null($this->get("state_ids"))) { // has selected states
            $st = func_new("State", $item[$prefix . "_state"]);
            $state = $st->get("state");
        } else {
            $state = "All";
        }
        if (!is_null($this->get("country_codes"))) { // has selected country
            $cnt = func_new("Country", $item[$prefix . "_country"]);
            $country = $cnt->get("country");
        } else {
            $country = "All";
        }
        return $country . " / " . $state;
    } // }}}
    
    function getProductsFound() // {{{
    {
        $found = 0;
        foreach ((array)$this->get("geoSales") as $gl => $gs) {
            $found += count($gs);
        }
        return $found;
    } // }}}
    
    function getCountries() // {{{
    {
        if (is_null($this->countries)) {
            $country = func_new("Country");
            $this->countries = $country->findAll();
        }
        return $this->countries;
    } // }}}

    function getStates() // {{{
    {
        if (is_null($this->states)) {
            $state = func_new("State");
            $this->states = $state->findAll();
        }
        return $this->states;
    } // }}}
    
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
