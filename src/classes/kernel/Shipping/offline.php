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
* Shipping_offline description.
*
* @package Kernel
* @access public
* @version $Id: offline.php,v 1.24 2008/11/14 14:02:48 sheriff Exp $
*/
class Shipping_offline extends Shipping
{
    function getModuleName()
    {
        return "Manually defined shipping methods";
    }

    function _buildRatesSql($sql, &$order)
    {
    	return $sql;
    }

    function getRates(&$order)
    {
        $shop_country = $this->config->get("Company.location_country");
        if (is_null($order->get("profile")) && !$this->config->get("General.def_calc_shippings_taxes")) {
        	return array();
        }
        $dest_country = (is_null($order->get("profile"))) ? $this->config->get("General.default_country") : $order->get("profile.shipping_country");
        // select all national/international shipping methods
        if ($dest_country == $shop_country) {
            $dest = 'L';
        } else {
            $dest = 'I';
        }
        $sql = "destination='$dest' AND enabled=1 AND class='offline'";
        $methods =& $this->findAll($this->_buildRatesSql($sql, $order));
        // join with rates table
        $result = array();
        for ($i=0; $i<count($methods); $i++)
        {
            $method =& $methods[$i];
            $rate = $this->getRate($order, $method);
            if (isset($rate)) {
                $result[$method->get("shipping_id")] = $rate;
            }    
        }
        // TODO: sort by rate
        return $result;
    }

    function _buildRateSql($sql, &$order, &$method)
    {
    	return $sql;
    }

    function getRate(&$order, &$method)
    {
        $shipping_id = $method->get("shipping_id");
        $weight = (double) $order->get("weight");
        $total = (double) $order->calcSubTotal(true); // SubTotal for "shipped only" items
        $r =& func_new("ShippingRate");
        $zone = $this->getZone($order);
        $items = $order->get("shippedItemsCount");
        $sql = "(shipping_id=-1 OR shipping_id='$shipping_id') AND (shipping_zone=-1 OR shipping_zone='$zone') AND min_weight<=$weight AND max_weight>=$weight AND min_total<=$total AND min_items<=$items AND max_items>=$items AND max_total>$total";
        if ($r->find($this->_buildRateSql($sql, $order, $method), "shipping_id DESC, shipping_zone DESC")) {
            $r->rate = (double)$r->get("flat") + (double)$r->get("per_item") * $items + (double)$r->get("percent")*$total/100 + (double)$r->get("per_lbs")*$weight;
            $r->shipping =& $method;
            return $r;
        }
        return null;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
