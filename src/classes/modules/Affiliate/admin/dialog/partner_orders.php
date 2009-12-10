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
* @version $Id: partner_orders.php,v 1.5 2008/10/23 11:50:35 sheriff Exp $
*
*/
class Admin_Dialog_partner_orders extends Admin_Dialog
{
    var $params = array('target', 'mode', 'order_id1', 'order_id2', 'partner_id', 'status', 'payment_status');
    var $crlf = "\r\n";

    function action_export() // {{{
    {
        $w = func_new("Widget");
        $w->component =& $this;
        $w->set("template", "modules/Affiliate/orders.tpl");
        $this->startDownload("orders.csv");
        $w->init();
        $w->display();

        // do not output anything
        $this->set("silent", true);
    } // }}}
    
    function &getDelimiter() // {{{
    {
        global $DATA_DELIMITERS;
        return $DATA_DELIMITERS[$this->delimiter];
    } // }}}

    function &getSales() // {{{
    {
        if (is_null($this->sales)) {
            $pp =& func_new("PartnerPayment");
            $this->sales = $pp->searchSales(
                    $this->get("startDate"),
                    $this->get("endDate") + 24 * 3600,
                    null,
                    $this->get("partner_id"),
                    $this->get("payment_status"),
                    $this->get("status"),
                    $this->get("order_id1"),
                    $this->get("order_id2"),
                    true // show affiliate sales
                    );
            $this->salesCount = count($this->sales);
        }
        return $this->sales;
    } // }}}

    function &getSalesCount() // {{{
    {
        return count($this->get("sales"));
    } // }}}
    
    function &getOrder() // {{{
    {
        return $this->get("sale.order");
    } // }}}
    
    function fillForm() // {{{
    {
        if (!isset($this->startDate)) {
            $date = getdate(time());
            $this->set("startDate", mktime(0,0,0,$date['mon'],1,$date['year']));
        }
        if (!isset($this->partner_id)) {
            $this->set("partner_id", "");
        }
        parent::fillForm();
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
