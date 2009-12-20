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
class Admin_dialog_general_stats extends Admin_dialog_Ecommerce_reports
{
    function getGS()
    {
        if (is_null($this->gs)) {
            $this->gs = array();
            $this->order = $order = func_new("Order");
            $this->table = $table = $order->db->getTableByAlias($order->alias);
            $fd = $this->get("period.fromDate");
            $td = $this->get("period.toDate");
            // total orders
            $this->gs["placed"] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            // queued
            $this->gs["queued"] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status='Q' AND date BETWEEN $fd AND $td");
            // processed
            $this->gs["processed"] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status='P' AND date BETWEEN $fd AND $td");
            // incomplete
            $this->gs["incomplete"] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status='I' AND date BETWEEN $fd AND $td");
            // failed
            $this->gs["failed"] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status='F' AND date BETWEEN $fd AND $td");
            // declined
            $this->gs["declined"] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status='D' AND date BETWEEN $fd AND $td");
            // completed
            $this->gs["completed"] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status='C' AND date BETWEEN $fd AND $td");
            // subtotal
            $this->gs["subtotal"] = $order->db->getOne("SELECT SUM(subtotal) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");

            // total tax
            $this->gs["total_tax"] = $order->db->getOne("SELECT SUM(tax) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            // taxes in details
            $allTaxes = $order->db->getAll("SELECT taxes FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            foreach ($allTaxes as $k => $v) {
                $taxes = unserialize($v["taxes"]);
                foreach ($taxes as $taxName => $taxAmount) {
                    if ($taxName != "Tax") {
                        if (!isset($this->gs["taxDetails"][$taxName])) {
                            $this->gs["taxDetails"][$taxName] = $taxAmount;
                        } else {
                            $this->gs["taxDetails"][$taxName] += $taxAmount;
                        }
                    }
                }
            }

            // shipping cost
            $this->gs["total_shipping"] = $order->db->getOne("SELECT SUM(shipping_cost) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");

            // Promotion add-on
            // discounts
            if ($this->get("xlite.mm.activeModules.Promotion")) {
                $discount = $order->db->getOne("SELECT SUM(discount) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
                $payedByPoints = $order->db->getOne("SELECT SUM(payedByPoints) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            } else {
                $discount = 0;
                $payedByPoints = 0;
            }
            $this->gs["discount"] = $discount;
            $this->gs["payedByPoints"] = $payedByPoints;
            
            // GiftCertificates add-on
            if ($this->get("xlite.mm.activeModules.GiftCertificates")) {
                $payedByGC = $order->db->getOne("SELECT SUM(payedByGC) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            } else {
                $payedByGC = 0;
            }
            $this->gs["payedByGC"] = $payedByGC;
            
            // Wholesalers add-on
            if ($this->get("xlite.mm.activeModules.WholesaleTrading")) {
                $global_discount = $order->db->getOne("SELECT SUM(global_discount) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            } else {
                $global_discount = 0;
            }
            $this->gs["global_discount"] = $global_discount;

            // calc total discount
            $this->gs["total_discounts"] = $discount + $payedByPoints + $payedByGC + $global_discount;

            // gross total
            $this->gs["gross_total"] = $order->db->getOne("SELECT SUM(total) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");

            // calculate extra sales info (for modules)
            $this->calcExtraGS();

            // members
            $profile =& func_new("Profile");
            $table = $profile->db->getTableByAlias($profile->alias);
            $this->gs["active_accounts"] = $profile->db->getOne("SELECT COUNT(*) FROM $table WHERE status='E' AND order_id=0");
            $this->gs["new_accounts"] = $profile->db->getOne("SELECT COUNT(*) FROM $table WHERE status='E' AND first_login BETWEEN $fd AND $td AND order_id=0");
        }    
        return $this->gs;
    }

    function calcExtraGS()
    {
        // void
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
