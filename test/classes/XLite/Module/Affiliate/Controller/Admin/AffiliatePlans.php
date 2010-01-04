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
class XLite_Module_Affiliate_Controller_Admin_AffiliatePlans extends XLite_Controller_Admin_Abstract
{
    function action_delete()
    {
        $ap = $this->get("affiliatePlan");
        if ($ap->get("plan_id") == $this->config->get("Affiliate.default_plan")) {
			$cfg = new XLite_Model_Config();
            $cfg->createOption("Affiliate", "default_plan", "");
        }
        $ap->delete();
    }
    
    function action_update()
    {
        $ap = $this->get("affiliatePlan");
        $ap->update();
        if ($ap->get("plan_id") == $this->config->get("Affiliate.default_plan") && !$ap->get("enabled")) {
			$cfg = new XLite_Model_Config();
            $cfg->createOption("Affiliate", "default_plan", "");
        }
    }
    
    function action_add()
    {
        $ap = $this->get("affiliatePlan");
        $ap->create();
        if (!is_null($this->get("returnUrl"))) {
            $this->set("returnUrl", $this->get("returnUrl") . $ap->get("plan_id"));
        }
    }

    function getAffiliatePlan()
    {
        $ap = new XLite_Module_Affiliate_Model_AffiliatePlan(isset($_REQUEST["plan_id"]) ? $_REQUEST["plan_id"] : null);
        $ap->set("properties", $_REQUEST);
        return $ap;
    }

    function getAffiliatePlans()
    {
        if (is_null($this->affiliatePlans)) {
            $ap = new XLite_Module_Affiliate_Model_AffiliatePlan();
            $this->affiliatePlans = $ap->findAll();
        }
        return $this->affiliatePlans;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
