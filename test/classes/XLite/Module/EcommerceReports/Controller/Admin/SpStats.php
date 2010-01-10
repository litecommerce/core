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
class XLite_Module_EcommerceReports_Controller_Admin_SpStats extends XLite_Module_EcommerceReports_Controller_Admin_EcommerceReports
{
    function getShippingMethods() // {{{
    {
        if (is_null($this->shippingMethods)) {
            $this->shippingMethods = array();
            $table = $this->get("order.table");
            $ids = $this->order->db->getAll("SELECT shipping_id, COUNT(*) as num_used FROM $table WHERE status!='T' GROUP BY shipping_id ORDER BY num_used DESC");
            foreach ($ids as $id) {
                $sid = $id["shipping_id"];
                $this->shippingMethods[] = $this->getShippingMethod($sid);
            }
        }
        return $this->shippingMethods;
    } // }}}

    function getShippingMethod($sid) // {{{
    {
        $sm = new XLite_Model_Shipping();
        if (!$sm->find("shipping_id=$sid")) {
            $sm->set("shipping_id", $sid);
			$name = ($sid == 0)?"Free shipping":"Unknown (id:$sid)";
			$sm->set("name", $name);
        }
        return $sm;
    } // }}}
    
    function getPaymentMethods() // {{{
    {
        if (is_null($this->paymentMethods)) {
            $this->paymentMethods = array();
            $table = $this->get("order.table");
            $pms = $this->order->db->getAll("SELECT payment_method, COUNT(*) as num_used FROM $table WHERE status!='T' GROUP BY payment_method ORDER BY num_used DESC");
            foreach ($pms as $id) {
                $pn = $id["payment_method"];
                $this->paymentMethods[] = $this->getPaymentMethod($pn);
            }
        }
        return $this->paymentMethods;
    } // }}}

    function getPaymentMethod($pn) // {{{
    {
        
        if (!$pm->find("payment_method='$pn'")) {
            $pm->set("payment_method", $pn);
            $pm->set("name", $pn);
        }
        return $pm;
    } // }}}

    function getOrders() // {{{
    {
        if (is_null($this->orders)) {
            $this->orders = array();
            $table = $this->get("order.table");
            $fd = $this->get("period.fromDate");
            $td = $this->get("period.toDate");
            $sql = "SELECT order_id, total, shipping_id, payment_method FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td";
            $this->totalOrders = $this->order->db->getOne("SELECT COUNT(*) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            $rawOrders = $this->order->db->getAll($sql);
            array_map(array($this, 'sumOrders'), $rawOrders);
        }
        return $this->orders;
    } // }}}

    function sumOrders($row) // {{{
    {
        $sid = $row["shipping_id"];
        $pid = $row["payment_method"];
        $sok = $this->get("shipping_id") == "all" ? true : $this->get("shipping_id") == $sid;
        $pok = $this->get("payment_method") == "all" ? true : $this->get("payment_method") == $pid;
        if ($sok && $pok) {
            $hash = $sid."-".$pid;
            if (!isset($this->orders[$hash])) {
                $od = array();
                $od["orders"] = 1;
                $od["total"] = $row["total"];
                $od["percent"] = round(100 / $this->totalOrders, 2);
                $od["payment_method"] = $this->getPaymentMethod($row["payment_method"]);
                $od["shipping_method"] = $this->getShippingMethod($row["shipping_id"]);
                $this->orders[$hash] = $od; 
            } else {
                $od = $this->orders[$hash];
                $od["orders"]++;
                $od["total"] += $row["total"];
                $od["percent"] = round($od["orders"] * 100 / $this->totalOrders, 2);
            }
        }
    } // }}}

    function getOrder() // {{{
    {
        if (is_null($this->order)) {
            $this->order = new XLite_Model_Order();
        }
        return $this->order;
    } // }}}

    function countOrders($od) // {{{
    {
        $total = 0;
        foreach($od as $o) {
            $total += $o["orders"];
        }
        return $total;
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
