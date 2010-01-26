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
class XLite_Module_Affiliate_Controller_Admin_Partners extends XLite_Controller_Admin_Abstract
{	
    public $params = array("target", "search", "filter", "partnerStatus", "plan_id", "plan", "startDateMonth", "startDateDay", "startDateYear", "endDateMonth", "endDateDay", "endDateYear", "itemsPerPage");

    function fillForm()
    {
        if (!isset($this->startDate)) {
            $date = getdate(time());
            $this->set("startDate", mktime(0,0,0,$date['mon'],1,$date['year']));
        }
        parent::fillForm();
    }

    function action_update_partners()
    {
        $partners = $this->get("ids");
        if (!is_null($partners) && is_array($partners)) {
            foreach ($partners as $pid) {
                $partner = new XLite_Model_Profile($pid);
                if (!is_null($this->get("delete"))) {
                    $this->auth->deletePartner($partner);
                } else if (!is_null($this->get("update"))) {
                    $plan = $this->get("new_plan");
                    $status = $this->get("status");
                    if (!empty($status)) {
                        $action = $status . "Partner";
                        if (method_exists($this->auth, $action)) {
                            $this->auth->$action($partner);
                        }
                    }
                    if (!empty($plan)) {
                        $partner->set("plan", $plan);
                    }
                    if (!empty($plan) || !empty($status)) {
                        $partner->update();
                    }
                }
            }    
        }
    }
    
    function getPartners()
    {
        if (is_null($this->partners)) {
            $this->partners = array();
            $where = array();
            // build WHERE condition for profile info
            if (!is_null($this->get("filter")) && trim($this->get("filter")) != "") {
                $filter = "'%".trim($this->get("filter"))."%'";
                $where[] = "(login LIKE $filter".
                    "  OR billing_firstname LIKE $filter".
                    "  OR billing_lastname LIKE $filter)";
            }
            if (!is_null($this->get("partnerStatus")) && trim($this->get("partnerStatus")) != "") {
                $where[] = " access_level = ". trim($this->get("partnerStatus"));
            } else {    
                $where[] = " (access_level = ".   $this->getComplex('auth.partnerAccessLevel') .
                           " OR access_level = ". $this->getComplex('auth.pendingPartnerAccessLevel') . 
                           " OR access_level = ". $this->getComplex('auth.declinedPartnerAccessLevel') . ")"; 
            }
            if (!is_null($this->get("pending_plan")) && trim($this->get("pending_plan")) != "") {
                $where[] = " pending_plan = ".$this->get("pending_plan");
            }
            if (!is_null($this->get("plan_id")) && trim($this->get("plan_id")) != "") {
                $where[] = " ".$this->get("plan") . " = " . $this->get("plan_id");
            }
            if (!is_null($this->get("startDate"))) {
                $where[] = " partner_signup >= " . $this->get("startDate");
            }
            if (!is_null($this->get("endDate"))) {
                $where[] = " partner_signup <= " . ($this->get("endDate") + 24 * 3600);
            }
            $and = join(' AND ',$where);
            $profile = new XLite_Model_Profile();
            $this->partners = $profile->findAll($and, "partner_signup DESC");
            $this->partnersCount = count($this->partners);
        }
        return $this->partners;
    }

    function getPlan()
    {
        if (is_null($this->plan)) {
            return "plan";
        }
        return $this->plan;
    }

    function getItemsPerPage()
    {
        if (is_null($this->itemsPerPage)) {
            return 10;
        }
        return $this->itemsPerPage;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
