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
* @package Module_Affiliate
* @access public
* @version $Id$
*/
class XLite_Module_Affiliate_Controller_Customer_PartnerSales extends XLite_Module_Affiliate_Controller_Partner
{	
    public $qty              = 0;	
    public $saleTotal        = 0;	
    public $commissionsTotal = 0;	
    public $affiliatePending = 0;	
    public $affiliatePaid    = 0;

	/**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0 
     */
    protected function getLocation()
    {
        return 'Referred sales';
    }

    function getSalesStats()
    {
        if (!$this->auth->isAuthorized($this)) {
        	return null;
        }

        if (is_null($this->salesStats)) {
            $this->salesStats = array();
            $pp = new XLite_Module_Affiliate_Model_PartnerPayment();
            $salesStats = $pp->searchSales(
                    $this->get("startDate"),
                    $this->get("endDate") + 24 * 3600,
                    $this->get("product_id"),
                    $this->getComplex('auth.profile.profile_id'),
                    $this->get("payment_status"),
                    null,
                    null,
                    null,
                    true
                    );
            // summarize search result into $this->salesStats
            array_map(array($this, 'sumSale'), $salesStats);
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
                    $this->topProducts[$id]["amount"]["total"] = sprintf("%.02f", doubleval($this->topProducts[$id]["amount"]["total"] + $item->get("total")));
                    $this->topProducts[$id]["amount"]["commissions"] = sprintf("%.02f", doubleval($this->topProducts[$id]["amount"]["commissions"] + $item->get("commissions")));
                }    
            }
            usort($this->topProducts, array($this, "cmpProducts"));
            $topProducts = array_chunk(array_reverse($this->topProducts), 10);
            $this->topProducts = $topProducts[0];
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
        if ($pp->get("affiliate") == 0) {  // it's a partner buyer
            $this->salesStats[] = $pp;
            foreach ($pp->getComplex('order.items') as $item) {
                $this->qty += $item->get("amount");
            }
            if ($pp->isComplex('order.processed')) {
                $this->items = array_merge($this->items, $pp->getComplex('order.items'));
            }    
            $this->salesTotal += $pp->getComplex('order.subtotal');
        } else { // it's a partner affiliate
            if ($pp->get("paid")) { // 
                $this->affiliatePaid += $pp->get("commissions");
            } else {
                $this->affiliatePending += $pp->get("commissions");
            }
        }
        $this->commissionsTotal += $pp->get("commissions");
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
