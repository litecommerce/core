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
* Dialog_add_gift_certificate  - an add GC form dialog.
*
* @package Module_GiftCertificates
* @access public
* @version $Id$
*/
class Dialog_add_gift_certificate extends Dialog
{
    var $params = array('target', 'gcid');
	var $gc = null;
    
    function getGC()
    {
        if (is_null($this->gc)) {
            if ($this->get("gcid")) {
                $this->gc = func_new("GiftCertificate", $this->get("gcid"));
            } else {
                // set default form values
                $this->gc = func_new("GiftCertificate");
                $this->gc->set("send_via", "E");
                $this->gc->set("border", "no_border");
                $auth = func_get_instance("Auth");
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
    }

    function isGCAdded()
    {
        if (is_null($this->get("gc")))
            return false;
        $items = $this->cart->get("items");
        $found = false;
        for ($i=0; $i<count($items); $i++) {
            if ($items[$i]->get("gcid") == $this->get("gc.gcid")) {
                $found = true;
                break;
            }
        }
        return $found;
    }

	function action_add()
	{
        $this->saveGC();

        $found = false;
		$items = $this->cart->get("items");
		for ($i=0; $i<count($items); $i++) {
			if ($items[$i]->get("gcid") == $this->get("gc.gcid")) {
				$items[$i]->set("GC", $this->get("gc"));
				$items[$i]->update();
                $found = true;
			}
		}
        if (!$found) {
			$oi = func_new("OrderItem");
			$oi->set("GC", $this->get("gc"));
			$this->cart->addItem($oi);
    	}
		if ($this->cart->isPersistent) {
			$this->cart->calcTotals();
			$this->cart->update();
    		$items = $this->cart->get("items");
    		for ($i=0; $i<count($items); $i++) {
    			if ($items[$i]->get("gcid") == $this->get("gc.gcid")) {
    				$this->cart->updateItem($items[$i]);
    			}
    		}
		}
        $this->set("returnUrl", "cart.php?target=cart");
	}

    function action_select_ecard()
    {
        $this->saveGC();
        $this->set("returnUrl", "cart.php?target=gift_certificate_ecards&gcid=" . $this->get("gc.gcid"));
    }

    function action_delete_ecard()
    {
        $this->saveGC();
		if (!is_null($this->get("gc"))) {
			$gc = $this->get("gc");
            $gc->set("ecard_id", 0);
            $gc->update();
            $this->set("returnUrl", "cart.php?target=add_gift_certificate&gcid=" . $gc->get("gcid"));
        }
    }

    function action_preview_ecard()
    {
        $this->saveGC();
        $this->set("returnUrl", "cart.php?target=preview_ecard&gcid=" . $this->get("gc.gcid"));
    }

    function saveGC()
    {
        if (isset($_REQUEST["border"])) {
            $_REQUEST["border"] = str_replace(array(".","/"), array("",""), $_REQUEST["border"]);
        }
		if (!is_null($this->get("gc"))) {
			$gc = $this->get("gc");
    		$gc->setProperties($_REQUEST);
    		$gc->set("status", "D");
    		$gc->set("debit", $gc->get("amount"));
    		$gc->set("add_date", time());
			if (!$gc->get("expiration_date")) {
				$month = 30 * 24 * 3600;
				$gc->set("expiration_date", time() + $month * $this->get("gc.defaultExpirationPeriod"));
			}

        	if ($gc->get("gcid")) {
                $gc->update();
            } else {
                $gc->set("gcid", $gc->generateGC());
				$gc->set("profile_id", $this->xlite->get("auth.profile.profile_id"));
                $gc->create();
            }
        }
    }
    
    function getCountriesStates() {
        $countriesArray = array();

        $country = func_new("Country");
        $countries = $country->findAll("enabled='1'");
        foreach($countries as $country) {
            $countriesArray[$country->get("code")]["number"] = 0;
            $countriesArray[$country->get("code")]["data"] = array();

            $state = func_new("State");
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
