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
class XLite_Module_Affiliate_Controller_Customer_PartnerBannerStats extends XLite_Module_Affiliate_Controller_Partner
{	
    public $statsTotal = array("views" => 0, "click" => 0, "rate" => 0);

	/**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0 
     */
    protected function getLocation()
    {
        return 'Banner statistics';
    }

    function fillForm()
    {
        parent::fillForm();
        if (!isset($this->sort_by)) {
            $this->set("sort_by", "views");
        }
        if (!isset($this->default_banner) && !isset($this->search)) {
            $this->set("default_banner", "1");
        }
    }

    function getStats()
    {
        if (!$this->auth->isAuthorized($this)) {
        	return null;
        }

        if (is_null($this->stats)) {
            $stats = new XLite_Module_Affiliate_Model_BannerStats();
            $this->stats = $stats->search(
                    $this->getComplex('auth.profile.profile_id'),
                    $this->get("startDate"),
                    $this->get("endDate")+24*3600,
                    $this->get("sort_by"),
                    $this->get("default_banner"),
                    $this->get("product_banner"),
                    $this->get("direct_link"));
            // calculate stats total using callback
            array_map(array($this, 'sum'), $st = $this->stats);
        }
        return $this->stats;
    }

    function sum($rec)
    {
        $this->statsTotal["views"] += $rec["views"];
        $this->statsTotal["clicks"] += $rec["clicks"];
        if ($this->statsTotal["views"] != 0) {
            $this->statsTotal["rate"] = sprintf("%.02f", doubleval($this->statsTotal["clicks"] / $this->statsTotal["views"]));
        }
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
