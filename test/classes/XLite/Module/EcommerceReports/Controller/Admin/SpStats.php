<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_EcommerceReports_Controller_Admin_SpStats extends XLite_Module_EcommerceReports_Controller_Admin_EcommerceReports
{
    function getShippingMethods() 
    {
        if (is_null($this->shippingMethods)) {
            $this->shippingMethods = array();
            $table = $this->getComplex('order.table');
            $ids = $this->order->db->getAll("SELECT shipping_id, COUNT(*) as num_used FROM $table WHERE status!='T' GROUP BY shipping_id ORDER BY num_used DESC");
            foreach ($ids as $id) {
                $sid = $id["shipping_id"];
                $this->shippingMethods[] = $this->getShippingMethod($sid);
            }
        }
        return $this->shippingMethods;
    }

    function getShippingMethod($sid) 
    {
        $sm = new XLite_Model_Shipping();
        if (!$sm->find("shipping_id=$sid")) {
            $sm->set("shipping_id", $sid);
            $name = ($sid == 0)?"Free shipping":"Unknown (id:$sid)";
            $sm->set("name", $name);
        }
        return $sm;
    }
    
    function getPaymentMethods() 
    {
        if (is_null($this->paymentMethods)) {
            $this->paymentMethods = array();
            $table = $this->getComplex('order.table');
            $pms = $this->order->db->getAll("SELECT payment_method, COUNT(*) as num_used FROM $table WHERE status!='T' GROUP BY payment_method ORDER BY num_used DESC");
            foreach ($pms as $id) {
                $pn = $id["payment_method"];
                $this->paymentMethods[] = $this->getPaymentMethod($pn);
            }
        }
        return $this->paymentMethods;
    }

    function getPaymentMethod($pn) 
    {
        
        if (!$pm->find("payment_method='$pn'")) {
            $pm->set("payment_method", $pn);
            $pm->set("name", $pn);
        }
        return $pm;
    }

    function getOrders() 
    {
        if (is_null($this->orders)) {
            $this->orders = array();
            $table = $this->getComplex('order.table');
            $fd = $this->getComplex('period.fromDate');
            $td = $this->getComplex('period.toDate');
            $sql = "SELECT order_id, total, shipping_id, payment_method FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td";
            $this->totalOrders = $this->order->db->getOne("SELECT COUNT(*) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            $rawOrders = $this->order->db->getAll($sql);
            array_map(array($this, 'sumOrders'), $rawOrders);
        }
        return $this->orders;
    }

    function sumOrders($row) 
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
    }

    function getOrder() 
    {
        if (is_null($this->order)) {
            $this->order = new XLite_Model_Order();
        }
        return $this->order;
    }

    function countOrders($od) 
    {
        $total = 0;
        foreach($od as $o) {
            $total += $o["orders"];
        }
        return $total;
    }
}
