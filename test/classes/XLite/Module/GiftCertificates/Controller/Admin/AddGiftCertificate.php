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
* Admin_Dialog_add_gift_certificate description.
*
* @package Module_GiftCertificates
* @access public
* @version $Id$
*/
class XLite_Module_GiftCertificates_Controller_Admin_AddGiftCertificate extends XLite_Controller_Admin_Abstract
{
    var $params = array('target', 'gcid');
    var $gc = null;

    function getGC()
    {
        if (is_null($this->gc)) {
            if ($this->get("gcid")) {
                $this->gc = new XLite_Module_GiftCertificates_Model_GiftCertificate($this->get("gcid"));
            } else {
                // set default form values
                $this->gc = new XLite_Module_GiftCertificates_Model_GiftCertificate();
                $this->gc->set("send_via", "E");
                $this->gc->set("border", "no_border");
                $auth = XLite_Model_Auth::getInstance();
                if ($auth->isLogged()) {
                    $profile = $auth->get("profile");
                    $this->gc->set("purchaser", $profile->get("billing_title") . " " . $profile->get("billing_firstname") . " " . $profile->get("billing_lastname"));
                }
                $this->gc->set("recipient_country", $this->config->get("General.default_country"));
            }
        }
        return $this->gc;
    }

	function fillForm()
	{
        $this->set("properties", $this->get("gc.properties"));
		if (!$this->get("expiration_date")) {
			$month = 30 * 24 * 3600;
			$this->set("expiration_date", time() + $month * $this->get("gc.defaultExpirationPeriod"));
		}
		parent::fillForm();
    }

    function action_add()
    {
        $this->sendGC();
        $this->set("returnUrl", "admin.php?target=gift_certificates");
    }

    function action_select_ecard()
    {
        $this->saveGC();
        $this->set("returnUrl", "admin.php?target=gift_certificate_select_ecard&gcid=" . $this->get("gc.gcid"));
    }

    function action_delete_ecard()
    {
        $this->saveGC();
		if (!is_null($this->get("gc"))) {
			$gc = $this->get("gc");
        	$gc->set("ecard_id", 0);
        	$gc->update();
        	$this->set("returnUrl", "admin.php?target=add_gift_certificate&gcid=" . $gc->get("gcid"));
        }
    }

    function action_preview_ecard()
    {
        $this->saveGC();
        $this->set("returnUrl", "admin.php?target=preview_ecard&gcid=" . $this->get("gc.gcid"));
    }

    function saveGC()
    {
		if (!is_null($this->get("gc"))) {
			$gc = $this->get("gc");
            $gc->setProperties($_REQUEST);
            $gc->set("add_date", time());
			$gc->set("expiration_date", $this->get("expiration_date"));
            if (empty($_REQUEST["debit"])) {
                $gc->set("debit", $gc->get("amount"));
            }
            if (!$gc->get("gcid")) {
                $gc->set("gcid", $gc->generateGC());
                $gc->create();
            }
            $gc->update();
        }
    }

	function sendGC()
	{
		$this->saveGC();
		$gc = $this->get("gc");
		if ( !is_null($gc) ) {
        	$gc->set("status", "A"); // Activate and send GC (for send_via = E)
            $gc->update();
        }
    }
    
    function getCountriesStates() {
        $countriesArray = array();

        $country = new XLite_Model_Country();
        $countries = $country->findAll("enabled='1'");
        foreach($countries as $country) {
            $countriesArray[$country->get("code")]["number"] = 0;
            $countriesArray[$country->get("code")]["data"] = array();

            $state = new XLite_Model_State();
            $states = $state->findAll("country_code='".$country->get("code")."'");
            if (is_array($states) && count($states) > 0) {
                $countriesArray[$country->get("code")]["number"] = count($states);
                foreach($states as $state) {
                    $countriesArray[$country->get("code")]["data"][$state->get("state_id")] = $state->get("state");
                }
            }
        }

        return $countriesArray;
    }
    
    function isVersionUpper2_1()
	{	
		return ($this->get("config.Version.version") >= "2.2") ? true : false;
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
