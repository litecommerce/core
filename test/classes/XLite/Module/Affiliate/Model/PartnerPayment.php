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
 * @subpackage Model
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
class XLite_Module_Affiliate_Model_PartnerPayment extends XLite_Model_Abstract
{	
    public $fields = array (
            "payment_id" => null,
            "partner_id" => 0,
            "order_id"   => 0,
            "commissions"=> 0.00,
            "paid"       => 0,
            "add_date"   => 0,
            "paid_date"  => 0,
            "affiliate"  => 0
            );	

    public $autoIncrement = "payment_id";	
    public $alias = "partner_payments";	
    public $defaultOrder = "add_date, affiliate";	

    public $partner;	
    public $parent;	
    public $order;	
    public $affiliates;

    function charge($order) // {{{
    {
        require_once LC_MODULES_DIR . 'Affiliate' . LC_DS . 'encoded.php';
        return func_Affiliate_charge($this, $order);
    } // }}}

    function set($name, $value) // {{{
    {
        if ($name == "order_id") {
            $this->set("add_date", time());
        }
        if ($name == "paid" && (boolean)$value == true) {
            $this->set("paid_date", time());
        }
        parent::set($name, $value);
    } // }}}

    // sends payment nofitication
    function notifyPartner() // {{{
    {
        $mail = new XLite_Model_Mailer();
        $mail->payment = $this;
        $mail->partner = new XLite_Model_Profile($this->get("partner_id"));
        $mail->compose(
                $this->config->getComplex('Company.orders_department'),
                $mail->getComplex('partner.login'),
                "modules/Affiliate/partner_order_processed");
        $mail->send();
    } // }}}

    function filter() // {{{
    {
        /*
         * NOTE: possible limitation for 1-tier affiliates
         *
        if (!$this->xlite->is("adminZone")) {
            return $this->get("partner_id") == $this->getComplex('auth.profile.profile_id');
        }
        */
        return parent::filter();
    } // }}}

    function getPartner() // {{{
    {
        if (is_null($this->partner)) {
            $this->partner = new XLite_Model_Profile($this->get("partner_id"));
        }
        return $this->partner;
    } // }}}

    function getAffiliates() // {{{
    {
        if (is_null($this->affiliates)) {
            $this->affiliates = array();
            $level = 1;
            foreach ($this->findAll("order_id=".$this->get("order_id"), "affiliate") as $p) {
                $this->affiliates[$level] = $p->get("partner");
                $level++;
            }
        }
        return $this->affiliates;
    } // }}}
    
    function getParent() // {{{
    {
        if (is_null($this->parent)) {
            $this->parent = new XLite_Model_Profile($this->get("affiliate"));
        }
        return $this->parent;
    } // }}}

    function getOrder() // {{{
    {
        if (is_null($this->order)) {
            $this->order = new XLite_Model_Order($this->get("order_id"));
        }
        return $this->order;
    } // }}}
    
    function searchSales($startDate, $endDate, $productID, $partnerID, $paymentStatus, $orderStatus = null, $id1 = null, $id2 = null, $searchAffiliates = false)  // {{{
    {
        $where = array();
        if ($startDate) {
            $where[] = "add_date>=$startDate";
        }
        if ($endDate) {
            $where[] = "add_date<=$endDate";
        }
        if (is_numeric($partnerID)) {
            $where[] = "partner_id=$partnerID";
        }
        if (is_numeric($paymentStatus)) {
            $where[] = "paid=$paymentStatus";
        }
        if (!empty($id1)) {
            $where[] = "order_id>=".(int)$id1;
        }
        if (!empty($id2)) {
            $where[] = "order_id<=".(int)$id2;
        }
        // exclude affiliate payments?
        if (!$searchAffiliates) {
            $where[] = "affiliate=0";
        }    
        $result = $this->findAll(implode(" AND ", $where));
        // filter payments by order status
        $result1 = array();
        foreach ($result as $pp) {
            if (!empty($orderStatus) && $pp->getComplex('order.status') != $orderStatus) {
                continue;
            }
            $result1[] = $pp;
        }
        $result = $result1;
        if (is_numeric($productID)) {
            $result2 = array();
            foreach ($result as $pp) {
                foreach ($pp->getComplex('order.items') as $item) {
                    if (empty($productID) || $item->get("product_id") == $productID) {
                        $result2[] = $pp;
                    }
                }
            }
            $result = $result2;
        }
        return $result;
    } // }}}

    function pay($partnerID) // {{{
    {
        foreach ((array)$this->findAll("partner_id=$partnerID") as $payment) {
            if ($payment->isComplex('order.processed')) { // process all order payments
                foreach ((array)$payment->findAll("order_id=".$payment->getComplex('order.order_id')) as $pp)
                {
                    if (!$pp->get("paid") && $pp->get("partner_id") == $partnerID) {
                    	$pp->set("paid", 1);
                    	$pp->update();
					}
                }
            }    
        }
    } // }}}

    function _import(array $options) // {{{
    {
        $data = $options["properties"];
        $w = new XLite_View_Abstract();

        static $line_no;
        if (!isset($line_no)) $line_no = 1; else $line_no++;
        echo "<b>Importing CSV file line# $line_no: </b><br>";
        
        $orderId = $data["order_id"];
        
        if (is_numeric($orderId)) {
            echo "Updating order ID# ".$orderId." payment status ... <br>";
            
            echo "<table border=0 cellpadding=5>";
            foreach ((array)$this->findAll("order_id=".$orderId, "commissions") as $p) {
                $paid = ($data["paid"] == "Y" || $data["paid"] == "y");
                $p->set("paid", $paid);
                $p->update();
                echo "<tr><td>Partner: ".$p->getComplex('partner.billing_firstname')." ".$p->getComplex('partner.billing_lastname')." &lt;".$p->getComplex('partner.login')."&gt;</td><td>Commissions: ".$w->price_format($p->get("commissions"))."</td><td>Status: " . ($paid ? "PAID" : "CANCELLED")."</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<font color=red>Error:</font> order #ID is invalid (not numeric value)<br>";
        }
    } // }}}
}
