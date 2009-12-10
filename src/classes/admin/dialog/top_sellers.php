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
* Base class for statistics pages.
*
* @package Dialog
* @access public
* @version $Id: top_sellers.php,v 1.5 2008/10/23 11:46:42 sheriff Exp $
*/
class Admin_Dialog_top_sellers extends Admin_Dialog_stats
{
    var $todayItems = array();
    var $weekItems = array();
    var $monthItems = array();
    var $sort_by = "amount";
    var $counter = array(0,1,2,3,4,5,6,7,8,9);

    function &getPageTemplate()
    {
        return "top_sellers.tpl";
    }

    function handleRequest()
    {
        // typedef
        $statRec = array("today" => 0, "week" => 0, "month" => 0);
        $this->stat = array(
                "processed" => $statRec,
                "queued" => $statRec,
                "failed" => $statRec,
                "not_finished" => $statRec,
                "total" => $statRec,
                "paid" => $statRec);

        $order =& func_new("Order");
        $date = $this->get("monthDate");
        array_map(array(&$this, "collect"), $order->findAll("(status='P' OR status='C') AND date>=$date"));
        $this->sort("todayItems");
        $this->sort("weekItems");
        $this->sort("monthItems");

        parent::handleRequest();
    }

    function getTopProduct($period, $pos, $property)
    {
        $val = $this->get("topProducts." . $period . "Items." . $pos . "." . $property);
        return is_null($val) ? "" : $val;
    }

    function collect($order)
    {
        $items = $order->get("items");
        if ($order->get("date") >= $this->get("todayDate")) {
            $this->todayItems = array_merge($this->todayItems, $items);
        }
        if ($order->get("date") >= $this->get("weekDate")) {
            $this->weekItems = array_merge($this->weekItems, $items);
        }
        if ($order->get("date") >= $this->get("monthDate")) {
            $this->monthItems = array_merge($this->monthItems, $items);
        }
    }

    function sort($name)
    {
        $this->topProducts[$name] = array();
        foreach ((array) $this->get($name) as $item) {
            $id = $item->get("product_id");
            if (!$id) continue;
            if (!isset($this->topProducts[$name][$id])) {
                $this->topProducts[$name][$id] = array(
                        "id" => $id,
                        "name" => $item->get("name"),
                        "amount" => $item->get("amount")
                        );
            } else {
                $this->topProducts[$name][$id]["amount"] += $item->get("amount");
            }
        }            
        usort($this->topProducts[$name], array(&$this, "cmpProducts"));
        $topProducts = array_chunk(array_reverse($this->topProducts[$name]), 10);
        $this->topProducts[$name] = $topProducts[0];
    }

    function cmpProducts($p1, $p2)
    {
        $key = $this->sort_by;
        if ($p1[$key] == $p2[$key]) {
            return 0;
        }
        return ($p1[$key] < $p2[$key]) ? -1 : 1;
    }

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
