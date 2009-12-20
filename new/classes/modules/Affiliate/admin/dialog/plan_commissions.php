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
*
*/
class Admin_Dialog_plan_commissions extends Admin_Dialog
{
    var $params = array("target", "plan_id");
    
    function action_update_commission()
    {
        if ($this->get("update")) {
            $commissions = $this->get("commission");
            $types = $this->get("commission_type");
            if (!is_array($commissions) || !is_array($types)) {
                return; // wrong data
            }
            foreach ($this->get("commission") as $itemID => $commission) {
                $pc =& func_new("PlanCommission", $this->get("plan_id"), $itemID, $this->get("item_type"));
                $pc->set("commission", $commissions[$itemID]);
                $pc->set("commission_type", $types[$itemID]);
                $pc->update();
            }
        } elseif ($this->get("delete")) {
            $deleteItems = $this->get("delete_items");
            if (is_array($deleteItems)) {
                foreach ($deleteItems as $itemID => $status) {
                    $pc =& func_new("PlanCommission", $this->get("plan_id"), $itemID, $this->get("item_type"));
                    $pc->delete();
                }
            }
        }
    }

    function action_add_commission()
    {
        $pc =& $this->get("planCommission");
        $pc->set("properties", $_POST);
        $pc->create();
    }
    
    function action_basic_commission()
    {
        $pc =& $this->get("basicCommission");
        $pc->set("properties", $_POST);        
        if ($this->foundBasicCommission) {
            $pc->update();
        } else {
            $pc->create();
        }
    }

    function &getAffiliatePlan()
    {
        $ap =& func_new("AffiliatePlan", isset($_REQUEST["plan_id"]) ? $_REQUEST["plan_id"] : null);
        $ap->set("properties", $_REQUEST);
        return $ap;
    }

    function &getCategoryCommissions()
    {
        $pc =& $this->get("planCommission");
        return $pc->findAll("plan_id=".$_REQUEST["plan_id"]." AND item_type='C'");
    }

    function &getProductCommissions()
    {
        $pc =& $this->get("planCommission");
        return $pc->findAll("plan_id=".$_REQUEST["plan_id"]." AND item_type='P'");
    }

    function &getBasicCommission()
    {
        $pc =& $this->get("planCommission");
        $this->foundBasicCommission = $pc->find("plan_id=".$_REQUEST["plan_id"]." AND item_type='B'");
        return $pc;
    }

    function &getPlanCommission()
    {
        if (is_null($this->pc)) {
            $this->pc =& func_new("PlanCommission");
        }
        return $this->pc;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
