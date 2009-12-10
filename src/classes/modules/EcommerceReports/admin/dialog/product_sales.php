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
* @version $Id: product_sales.php,v 1.13 2008/10/23 11:53:26 sheriff Exp $
*/
class Admin_dialog_product_sales extends Admin_dialog_Ecommerce_reports
{
    function &getProductSales() // {{{
    {
        if (is_null($this->productSales)) {
            $this->productSales = array();
            $items =& $this->get("rawItems");
            array_map(array(&$this, 'sumProductSales'), $items);
            usort($this->productSales, array(&$this, "cmpProducts"));
            $productSales = array_reverse($this->productSales);
            $this->productSales = $productSales;
        }
        return $this->productSales;
    } // }}}

    function cmpProducts($p1, $p2) // {{{
    {
        $key = $this->sort_by;
        if ($p1[$key] == $p2[$key]) {
            return 0;
        }
        return ($p1[$key] < $p2[$key]) ? -1 : 1;
    } // }}}

    function sumProductSales($item) // {{{
    {
        $id = $item["product_id"] . (strlen($item["options"]) ? md5($item["options"]) : "");
        $orderItem =& func_new("OrderItem");
        $found = $orderItem->find("order_id=".$item["order_id"]." AND item_id='".addslashes($item["item_id"])."'");
		$order =& func_new("Order", $item["order_id"]);
		$orderItem->set("order", $order);
		$item['price'] = $orderItem->get("price");
		 
        if (!isset($this->productSales[$id])) {
            $this->productSales[$id] = $item;
            $this->productSales[$id]["total"] = 0;
            $this->productSales[$id]["order_item"] = $orderItem;
        } else {
            $this->productSales[$id]["amount"] += $item["amount"];
        }

        $this->productSales[$id]["total"] += $item["amount"] * $item["price"];
        $this->productSales[$id]["avg_price"] = $this->productSales[$id]["total"] / $this->productSales[$id]["amount"];
    } // }}}
    
    function sumTotal($field) // {{{
    {
        $total = 0;
        foreach ($this->get("productSales") as $sale) {
            $total += $sale[$field];
        }
        return $total;
    } // }}}

	function getAveragePrice($total, $amount)
	{
		return $this->sumTotal($total)/$this->sumTotal($amount);
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
