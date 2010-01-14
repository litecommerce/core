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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* @package Module_Affiliate
* @version $Id$
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
                $this->config->get("Company.orders_department"),
                $mail->get("partner.login"),
                "modules/Affiliate/partner_order_processed");
        $mail->send();
    } // }}}

    function filter() // {{{
    {
        /*
         * NOTE: possible limitation for 1-tier affiliates
         *
        if (!$this->xlite->is("adminZone")) {
            return $this->get("partner_id") == $this->get("auth.profile.profile_id");
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
            if (!empty($orderStatus) && $pp->get("order.status") != $orderStatus) {
                continue;
            }
            $result1[] = $pp;
        }
        $result = $result1;
        if (is_numeric($productID)) {
            $result2 = array();
            foreach ($result as $pp) {
                foreach ($pp->get("order.items") as $item) {
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
            if ($payment->is("order.processed")) { // process all order payments
                foreach ((array)$payment->findAll("order_id=".$payment->get("order.order_id")) as $pp)
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
                echo "<tr><td>Partner: ".$p->get("partner.billing_firstname")." ".$p->get("partner.billing_lastname")." &lt;".$p->get("partner.login")."&gt;</td><td>Commissions: ".$w->price_format($p->get("commissions"))."</td><td>Status: " . ($paid ? "PAID" : "CANCELLED")."</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<font color=red>Error:</font> order #ID is invalid (not numeric value)<br>";
        }
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
