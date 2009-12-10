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
* @version $Id: Profile.php,v 1.13 2008/10/23 11:51:23 sheriff Exp $
*/
class Module_Affiliate_Profile extends Profile
{
    function constructor($id = null) // {{{
    {
        $this->fields["parent"] = 0;
        $this->fields["partner_fields"] = "";
        $this->fields["plan"] = 0;
        $this->fields["pending_plan"] = 0;
        $this->fields["reason"] = ""; // approval / not approval reason
        $this->fields["partner_signup"] = time();
        // fields available only for core
        $this->_securefields["plan"] = "";
        $this->_securefields["reason"] = "";
        parent::constructor($id);
    } // }}}

    function set($name, $value) // {{{
    {
        if ($name == "partner_fields" && is_array($value)) {
            $value = serialize($value);
        }
        parent::set($name, $value);
    } // }}}

    function &get($name) // {{{
    {
        $value =& parent::get($name);
        if ($name == "partner_fields") {
            $result = unserialize($value);
            if (is_array($result)) {
                $value = $result;
            }
        }
        return $value;
    } // }}}

    function &getParentProfile() // {{{
    {
        if (is_null($this->parentProfile)) {
            $pp =& func_new("Profile");
            if ($pp->find("profile_id=".$this->get("parent"))) {
                $this->parentProfile = $pp;
            }
        }
        return $this->parentProfile;
    } // }}}

    // IS_A methods {{{
    function isDeclinedPartner()
    {
        return $this->auth->isDeclinedPartner($this);
    }
    function isPendingPartner()
    {
        return $this->auth->isPendingPartner($this);
    }
    function isPartner()
    {
        return $this->auth->isPartner($this);
    }
    // }}}

    function &getPartnerPlan() // {{{
    {
        if (is_null($this->partnerPlan)) {
            $this->partnerPlan =& func_new("AffiliatePlan", $this->get("plan"));
        }
        return $this->partnerPlan;
    } // }}}

    function &getParents() // {{{
    {
        $parents = array();
        $tiers = intval($this->get("config.Affiliate.tiers_number"));
        if ($tiers > 1) {
            $parent = $this->get("parent");
            $level = 2; // start from level 2 affiliate
            // search for parents chain
            do {
                $p =& func_new("Profile");
                $found = $p->find("profile_id=".$parent);
                if ($found) {
                    $parents[$level] = $p;
                    $parent = $p->get("parent");
                }
            } while ($found && $level++ < $tiers);
        }
        return $parents;
    } // }}}

    function &getAffiliates() // {{{
    {
        if (is_null($this->affiliates)) {
            $this->affiliates = array();
            $level = 2;
            $this->buildAffiliatesTree($this->affiliates, $level);
        }    
        return $this->affiliates;
   } // }}}

    function buildAffiliatesTree(&$affiliates, $level) // {{{
    {
        $tiers = intval($this->get("config.Affiliate.tiers_number"));
        $pp = func_new("PartnerPayment");
        foreach ($this->findAll("parent=".$this->get("profile_id")) as $cid => $child) {
            $child->set("level", $level);
            $child->set("relative", $level <= $tiers); // parent gets commissions from this child
            $affiliates[] = $child;
            $child->buildAffiliatesTree($affiliates, $level + 1);
        }
    } // }}}

    function &getPartnerCommissions() // {{{
    {
        if (is_null($this->partnerCommissions)) {
            $this->partnerCommissions = 0.00;
            $pp =& func_new("PartnerPayment");
            // own commissions
            foreach ((array)$pp->findAll("partner_id=".$this->get("profile_id")." AND affiliate=0") as $payment) {
                $this->partnerCommissions += $payment->get("commissions");
            }
        }
        return $this->partnerCommissions;
    } // }}}

    function &getAffiliateCommissions() // {{{
    {
        if (is_null($this->affiliateCommissions)) {
            $this->affiliateCommissions = 0.00;
            $pp =& func_new("PartnerPayment");
            // own commissions
            foreach ((array)$pp->findAll("partner_id=".$this->get("profile_id")." AND affiliate<>0") as $payment) {
                $this->affiliateCommissions += $payment->get("commissions");
            }
        }
        return $this->affiliateCommissions;
    } // }}}

    function &getBranchCommissions() // {{{
    {
        if (is_null($this->branchCommissions)) {
            $this->branchCommissions = 0.00;
            foreach ((array)$this->get("affiliates") as $partner) {
                $pp =& func_new("PartnerPayment");
                foreach ((array)$pp->findAll("partner_id=".$partner->get("profile_id")) as $payment) {
                    $this->branchCommissions += $payment->get("commissions");
                }
            }

        }
        return $this->branchCommissions;
    } // }}}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
