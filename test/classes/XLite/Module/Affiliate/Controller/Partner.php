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
class XLite_Module_Affiliate_Controller_Partner extends XLite_Controller_Abstract
{	
    public $template = "modules/Affiliate/main.tpl";

	protected $shopLayout = null;


    /**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        if ('partner' !== XLite_Core_Request::getInstance()->target) {
            $this->locationPath->addNode(new XLite_Model_Location('Partner zone', $this->buildURL('partner')));
        }
    }



    function init()
    {
        parent::init();
        if ($_SERVER["REQUEST_METHOD"] == "GET" && $this->get("target") != "banner" && $this->get("target") != "product_banner" && !$this->xlite->is("adminZone") && isset($_GET["partner"]) && (!isset($_COOKIE["PartnerID"]) || (isset($_COOKIE["PartnerID"]) && $_COOKIE["PartnerID"] != $_GET["partner"]))) {
            $stats = new XLite_Module_Affiliate_Model_BannerStats();
            $stats->logClick();
            // issue a partner cookie
            if ($this->getComplex('config.Affiliate.partner_cookie_lifetime')) {
                // store for "lifetime" days
                $expire = time() + $this->getComplex('config.Affiliate.partner_cookie_lifetime') * 3600 * 24;
                $domain = func_parse_host(XLite::getInstance()->getOptions(array('host_details', 'http_host')));
                setcookie("PartnerID", $_GET["partner"], $expire, "/", $domain);
                setcookie("PartnerClick", $stats->get("stat_id"), $expire, "/", $domain);
            }
            $this->session->set("PartnerID", $_GET["partner"]);
            $this->session->set("PartnerClick", $stats->get("stat_id"));
        }
    }

	protected function redirect($url = null)
    {
        if ($this->get("mode") == "access_denied") {
            $this->set("mode", "accessDenied");
        }

        parent::redirect($url);
    }

    function getShopLayout()
    {
        if (is_null($this->shopLayout)) {
            $this->shopLayout = XLite_Model_Layout::getInstance();
        }
        return $this->shopLayout;
    }

    function getRowClass($idx, $class1, $class2 = null)
    {
        $classMethods = array_map("strtolower", get_class_methods($this));
        $isNewRC = in_array("isoddrow", $classMethods);
        if ($isNewRC) {
            return parent::getRowClass($idx, $class1, $class2);
        } else {
            return ($idx % 2 == 0) ? $class1 : $class2;
        }
    }

    function getShopUrl($url, $secure = false, $pure_url = false)
    {
        $url = parent::getShopUrl($url, $secure, $pure_url);
        if ($pure_url) {
            $sid = $this->session->getName() . "=" . $this->session->getID();
            if (strpos($url, $sid) !== false) {
                if (strpos($url, $sid . "&") !== false) {
                    $sid = $sid . "&";
                }
                $url = str_replace($sid, "", $url);
                $lastSymbol = substr($url, strlen($url)-1, 1);
                if ($lastSymbol == "?" || $lastSymbol == "&") {
                    $url = substr($url, 0, strlen($url)-1);
                }
            }
        }
        return $url;
    }

    function fillForm()
    {
        if (!isset($this->startDate)) {
            $date = getdate(time());
            $this->set("startDate", mktime(0,0,0,$date['mon'],1,$date['year']));
        }
        parent::fillForm();
    }

    function getAccessLevel()
    {
        return $this->auth->get("partnerAccessLevel");
    }

    function getSecure()
    {
        return $this->getComplex('config.Security.customer_security');
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
