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
* @version $Id$
*/
class XLite_Controller_Admin_OrdersStats extends XLite_Controller_Admin_Stats
{
    function getPageTemplate()
    {
        return "orders_stats.tpl";
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

        $order = new XLite_Model_Order();
        $date = $this->get("monthDate");
        // fetch orders for this month
        array_map(array($this, "summarize"), $order->findAll("date>=$date"));

        parent::handleRequest();
    }

    function save($index, $order, $paid = false)
    {
        if ($order->get("date") >= $this->get("todayDate")) {
            $this->sum($index, "today", $order->get("total"), $paid);
        }
        if ($order->get("date") >= $this->get("weekDate")) {
            $this->sum($index, "week", $order->get("total"), $paid);
        }
        if ($order->get("date") >= $this->get("monthDate")) {
            $this->sum($index, "month", $order->get("total"), $paid);
        }
    }

    function sum($index, $period, $amount, $paid)
    {
        $this->stat[$index][$period]++;
        $this->stat["total"][$period] += $amount;
        if ($paid) {
            $this->stat["paid"][$period] += $amount;
        }
    }
    
    function summarize($order)
    {
        switch ($order->get("status")) {
            case "P":
            case "C":
                $this->save("processed", $order, true);
                break;
            case "Q":
                $this->save("queued", $order);
                break;
            case "I":
                $this->save("not_finished", $order);
                break;
            case "F":
            case "D":
                $this->save("failed", $order);
                break;
        }
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
