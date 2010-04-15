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
class XLite_Module_Affiliate_Controller_Admin_SalesStats extends XLite_Module_Affiliate_Controller_Admin_PartnerStats
{	
    public $qty = 0;	
    public $saleTotal = 0;	
    public $commissionsTotal = 0;

    function getPageTemplate()
    {
        return "modules/Affiliate/sales_stats.tpl";
    }

    function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = new XLite_Model_Product($this->product_id);
        }
        return $this->product;
    }

    function getSalesStats()
    {
        if (is_null($this->salesStats)) {
            $pp = new XLite_Module_Affiliate_Model_PartnerPayment();
            $this->salesStats = $pp->searchSales (
                    $this->get("startDate"),
                    $this->get("endDate") + 24 * 3600,
                    $this->get("product_id"),
                    $this->get("partner_id"),
                    $this->get("payment_status")
                    );
            array_map(array($this, 'sumSale'), $st = $this->salesStats);
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
                usort($this->topProducts, array($this, "cmpProducts"));
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
        foreach ($pp->getComplex('order.items') as $item) {
            $this->qty += $item->get("amount");
        }
        if ($pp->isComplex('order.processed')) {
                $this->items = is_array($this->items) ? array_merge($this->items, $pp->getComplex('order.items')) : $pp->getComplex('order.items');
        }    
        $this->salesTotal += $pp->getComplex('order.subtotal');
        $this->commissionsTotal += $pp->get("commissions");
    }
}
