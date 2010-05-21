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
class XLite_Module_EcommerceReports_Controller_Admin_GeneralStats extends XLite_Module_EcommerceReports_Controller_Admin_EcommerceReports
{
    function getGS()
    {
        if (is_null($this->gs)) {
            $this->gs = array();
            $this->order = $order = new XLite_Model_Order();
            $this->table = $table = $order->db->getTableByAlias($order->alias);
            $fd = $this->getComplex('period.fromDate');
            $td = $this->getComplex('period.toDate');
            // total orders
            $this->gs['placed'] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            // queued
            $this->gs['queued'] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status='Q' AND date BETWEEN $fd AND $td");
            // processed
            $this->gs['processed'] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status='P' AND date BETWEEN $fd AND $td");
            // incomplete
            $this->gs['incomplete'] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status='I' AND date BETWEEN $fd AND $td");
            // failed
            $this->gs['failed'] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status='F' AND date BETWEEN $fd AND $td");
            // declined
            $this->gs['declined'] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status='D' AND date BETWEEN $fd AND $td");
            // completed
            $this->gs['completed'] = $order->db->getOne("SELECT COUNT(*) FROM $table WHERE status='C' AND date BETWEEN $fd AND $td");
            // subtotal
            $this->gs['subtotal'] = $order->db->getOne("SELECT SUM(subtotal) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");

            // total tax
            $this->gs['total_tax'] = $order->db->getOne("SELECT SUM(tax) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            // taxes in details
            $allTaxes = $order->db->getAll("SELECT taxes FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            foreach ($allTaxes as $k => $v) {
                $taxes = unserialize($v['taxes']);
                foreach ($taxes as $taxName => $taxAmount) {
                    if ($taxName != "Tax") {
                        if (!isset($this->gs['taxDetails'][$taxName])) {
                            $this->gs['taxDetails'][$taxName] = $taxAmount;
                        } else {
                            $this->gs['taxDetails'][$taxName] += $taxAmount;
                        }
                    }
                }
            }

            // shipping cost
            $this->gs['total_shipping'] = $order->db->getOne("SELECT SUM(shipping_cost) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");

            // Promotion add-on
            // discounts
            if ($this->getComplex('xlite.mm.activeModules.Promotion')) {
                $discount = $order->db->getOne("SELECT SUM(discount) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
                $payedByPoints = $order->db->getOne("SELECT SUM(payedByPoints) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            } else {
                $discount = 0;
                $payedByPoints = 0;
            }
            $this->gs['discount'] = $discount;
            $this->gs['payedByPoints'] = $payedByPoints;
            
            // GiftCertificates add-on
            if ($this->getComplex('xlite.mm.activeModules.GiftCertificates')) {
                $payedByGC = $order->db->getOne("SELECT SUM(payedByGC) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            } else {
                $payedByGC = 0;
            }
            $this->gs['payedByGC'] = $payedByGC;
            
            // Wholesalers add-on
            if ($this->getComplex('xlite.mm.activeModules.WholesaleTrading')) {
                $global_discount = $order->db->getOne("SELECT SUM(global_discount) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");
            } else {
                $global_discount = 0;
            }
            $this->gs['global_discount'] = $global_discount;

            // calc total discount
            $this->gs['total_discounts'] = $discount + $payedByPoints + $payedByGC + $global_discount;

            // gross total
            $this->gs['gross_total'] = $order->db->getOne("SELECT SUM(total) FROM $table WHERE status!='T' AND date BETWEEN $fd AND $td");

            // calculate extra sales info (for modules)
            $this->calcExtraGS();

            // members
            $profile = new XLite_Model_Profile();
            $table = $profile->db->getTableByAlias($profile->alias);
            $this->gs['active_accounts'] = $profile->db->getOne("SELECT COUNT(*) FROM $table WHERE status='E' AND order_id=0");
            $this->gs['new_accounts'] = $profile->db->getOne("SELECT COUNT(*) FROM $table WHERE status='E' AND first_login BETWEEN $fd AND $td AND order_id=0");
        }
        return $this->gs;
    }

    function calcExtraGS()
    {
        // void
    }
}
