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
* @access public
* @version $Id$
*/
class XLite_Module_Affiliate_Controller_Admin_TopPerformers extends XLite_Module_Affiliate_Controller_Admin_PartnerStats
{
    function getPageTemplate()
    {
        return "modules/Affiliate/top_performers.tpl";
    }

    function fillForm()
    {
        parent::fillForm();
        if (!isset($this->sort_by)) {
            $this->set("sort_by", "clicks");
        }
        if (!isset($this->report_by)) {
            $this->set("report_by", "affiliate");
        }
    }
    
    function getStats()
    {
        if (is_null($this->stats)) {
            $ss = new XLite_Module_Affiliate_Model_BannerStats();
            $this->stats = array();
            $this->stats = $ss->searchTopPerformers(
                $this->get("startDate"),
                $this->get("endDate")+24*3600,
                $this->get("report_by"));
            usort($this->stats, array($this, "cmpStats"));
            $this->stats = array_reverse($this->stats);
            foreach ($this->stats as $sid => $stat) {
                if (isset($stat["partner_id"])) {
                    $partner = new XLite_Model_Profile();
                    if (!$partner->find("profile_id=".$stat["partner_id"])) {
                        $partner->set("login", "Unknown");
                    }
                    $this->stats[$sid]["partner"] = $partner;
                }
            }
        }
        return $this->stats;
    }

    function cmpStats($p1, $p2)
    {
        $key = $this->sort_by;
        if ($p1[$key] == $p2[$key]) {
            return 0;
        }
        return ($p1[$key] < $p2[$key]) ? -1 : 1;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
