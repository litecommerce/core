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
* @access public
* @version $Id$
*/
class Admin_Dialog_sales_stats extends Admin_Dialog_partner_stats
{
    var $qty = 0;
    var $saleTotal = 0;
    var $commissionsTotal = 0;

    function getPageTemplate()
    {
        return "modules/Affiliate/sales_stats.tpl";
    }

    function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = func_new("Product", $this->product_id);
        }
        return $this->product;
    }

    function getSalesStats()
    {
        if (is_null($this->salesStats)) {
            $pp = func_new("PartnerPayment");
            $this->salesStats = $pp->searchSales (
                    $this->get("startDate"),
                    $this->get("endDate") + 24 * 3600,
                    $this->get("product_id"),
                    $this->get("partner_id"),
                    $this->get("payment_status")
                    );
            array_map(array(&$this, 'sumSale'), $st = $this->salesStats);
        }
        return $this->salesStats;
    }

    function getTopProducts()
    {
        if (is_null($this->topProducts)) {
            $this->topProducts = array();
            // getSalesStats must be called first to collect order items
            foreach ((array)$this->get("items") as $item) {
                $id = $item->get("product_id");
                if (!isset($this->topProducts[$id])) {
                    $this->topProducts[$id] = array(
                            "name" => $item->get("name"),
                            "amount" => $item->get("amount"),
                            "total" => $item->get("total"),
                            "commissions" => $item->get("commissions")
                            );
                } else {
                    $this->topProducts[$id]["amount"] += $item->get("amount");
                    $this->topProducts[$id]["total"] = sprintf("%.02f", doubleval($this->topProducts[$id]["total"] + $item->get("total")));
                    $this->topProducts[$id]["commissions"] = sprintf("%.02f", doubleval($this->topProducts[$id]["commissions"] + $item->get("commissions")));
                }    
            }
            if (is_array($this->topProducts) && count($this->topProducts) > 0) {
                usort($this->topProducts, array(&$this, "cmpProducts"));
                $topProducts = array_chunk(array_reverse($this->topProducts), 10);
                $this->topProducts = $topProducts[0];
            } else {
            	$this->topProducts = null;
            }
        }
        return $this->topProducts;
    }
    
    function cmpProducts($p1, $p2)
    {
        $key = $this->sort_by;
        if ($p1[$key] == $p2[$key]) {
            return 0;
        }
        return ($p1[$key] < $p2[$key]) ? -1 : 1;
    }
    
    function sumSale($pp)
    {
        foreach ($pp->get("order.items") as $item) {
            $this->qty += $item->get("amount");
        }
        if ($pp->is("order.processed")) {
                $this->items = is_array($this->items) ? array_merge($this->items, $pp->get("order.items")) : $pp->get("order.items");
        }    
        $this->salesTotal += $pp->get("order.subtotal");
        $this->commissionsTotal += $pp->get("commissions");
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
