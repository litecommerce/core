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
class XLite_Module_Affiliate_Model_PlanCommission extends XLite_Model_Abstract
{	
    public $fields = array(
            "plan_id" => 0,
            "commission" => "0.00",
            "commission_type" => '%',
            "item_id" => 0,
            "item_type" => "",
            );	

    public $alias = "partner_plan_commissions";	
    public $primaryKey = array("plan_id", "item_id", "item_type");

    function getProduct()
    {
        return new XLite_Model_Product($this->get("item_id"));
    }

    function getCategory()
    {
        return new XLite_Model_Category($this->get("item_id"));
    }

    function getBasicCommission()
    {
        $bc = new XLite_Module_Affiliate_Model_PlanCommission();
        if ($bc->find("plan_id=".$this->get("order.partner.plan")." AND item_id=0 AND item_type='B'")) {
            return $bc;
        }
        return null;
    }
    function getProductCommission($product_id)
    {
        $pc = new XLite_Module_Affiliate_Model_PlanCommission();
        if ($pc->find("plan_id=".$this->get("order.partner.plan")." AND item_id=$product_id AND item_type='P'")) {
            return $pc;
        }
        return null;
    }
    function getCategoryCommission($category_id)
    {
        $cc = new XLite_Module_Affiliate_Model_PlanCommission();
        if ($cc->find("plan_id=".$this->get("order.partner.plan")." AND item_id=$category_id AND item_type='C'")) {
            return $cc;
        }
        return null;
    }

    function getOrderCommissions()
    {
        require_once LC_MODULES_DIR . 'Affiliate' . LC_DS . 'encoded.php';
        return func_Affiliate_calc_order_commissions($this);
    }

    function calculate($order)
    {
        $this->order = $order;
        $commissions = 0;
        $ap = new XLite_Module_Affiliate_Model_AffiliatePlan($order->get("partner.plan"));
        if ($ap->is("enabled")) {
            $commissions = $this->get("orderCommissions");
        }            
        return $commissions;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
