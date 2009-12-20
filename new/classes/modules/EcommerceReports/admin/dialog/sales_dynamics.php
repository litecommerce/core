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
class Admin_dialog_sales_dynamics extends Admin_dialog_Ecommerce_reports
{
    function handleRequest()
    {
        if ($this->get("search")) {
            $this->set("session.salesDynamics", $_POST);
        } else {
            $this->set("session.salesDynamics", null);
        }
        parent::handleRequest();
    }

    function getLabel($date) // {{{
    {
        switch ($this->get("stat_step")) {
            case "day":
            case "week":
                $label = @date("M",$date) . " " . @date("j", $date);
                break;
            case "month":
            case "quarter":
                $label = @date("M",$date);
                break;
            case "year":
                $label = @date("Y",$date);
                break;
        }
        return $label;
    } // }}}

    function sumSale($items, $range) // {{{
    {
        $sum = 0;
        foreach ($items as $item) {
            if ($item["date"] >= $range[0] && $item["date"] < $range[1]) {
                $sum = $sum + $item["price"] * $item["amount"];
            }
        }
        return $sum;
    } // }}}

    function sumSaleQuantity($items, $range) // {{{
    {
        $qty = 0;
        foreach ($items as $item) {
            if ($item["date"] >= $range[0] && $item["date"] <= $range[1]) {
                $qty = $qty + $item["amount"];
            }
        }
        return $qty;
    } // }}}

    function sumSaleNumber($items, $range) // {{{
    {
        $number = array();
        foreach ($items as $item) {
            if ($item["date"] >= $range[0] && $item["date"] <= $range[1]) {
                $number[$item["order_id"]] = 1;
            }
        }
        return count($number);
    } // }}}

    function &getSales() // {{{
    {
        if (is_null($this->sales)) {
            $this->sales = array(
                    "x" => array(),
                    "y" => array(),
                    "labels" => array(),
                    );
            $func = "sumSale" . $this->get("show");
            $startDate = $this->get("period.fromDate");
            $items =& $this->get("rawItems");
            $x = array();
            $y = array();
            $labels = array();
            while (($nextDate = $this->get("nextDate")) !== false) {
                $x[] = $startDate;
                $range = array($startDate, $nextDate);
                $y[] = $this->$func($items, $range);
                $labels[] = $this->getLabel($startDate);
                $startDate = $nextDate;
            }
            $this->sales["x"] = $x;
            $this->sales["y"] = $y;
            $this->sales["labels"] = $labels;
        }
		return $this->sales;
    } // }}}

    function exportSales() // {{{
    {
        $this->salesData = array();
        $sales = $this->get("sales");
        foreach ($sales["x"] as $xid => $x) {
            $this->salesData[$x] = $sales["y"][$xid];
        }
        $w = func_new("Widget");
        $w->component =& $this;
        $w->set("template", "modules/EcommerceReports/export_xls.tpl");
        $this->startDownload("sales.xls");
        $this->ColumnCount = 2;
        $this->RowCount = count($this->salesData) + 2;
        $this->endRow = count($this->salesData) + 1;
        $profile =& $this->auth->get("profile");
        $time = time();
        $this->create_date = strftime("%Y-%m-%d", $time);
        $this->create_time = strftime("%H:%M:%S", $time);
        $this->author = $profile->get("billing_firstname") . " " . $profile->get("billing_lastname");
        $w->init();
        $w->display();

        // do not output anything
        $this->set("silent", true);
    } // }}}

    function action_get_data() // {{{
    {
        if ($this->get("export")) {
            $this->exportSales();
        }
        parent::action_get_data();
    } // }}}

    function getStartXML() // {{{
    {       
        return '<?xml version="1.0"?>'."\n";;
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
