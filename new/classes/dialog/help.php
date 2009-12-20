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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class description.
*
* @package Dialog
* @access public
* @version $Id$
*/
class Dialog_help extends Dialog
{
    var $params = array("target", "mode");

    function fillForm()
    {
        if ($this->get("mode") == "contactus" ) {
            if ($this->auth->is("logged")) {
                // fill in contact us form with default values
                $this->set("email", $this->auth->get("profile.login"));
                $this->set("firstname", $this->auth->get("profile.billing_firstname"));
                $this->set("lastname", $this->auth->get("profile.billing_lastname"));
                $this->set("address", $this->auth->get("profile.billing_address"));
                $this->set("zipcode", $this->auth->get("profile.billing_zipcode"));
                $this->set("city", $this->auth->get("profile.billing_city"));
                $this->set("contactus_state", $this->auth->get("profile.billing_state"));
				$this->set("contactus_custom_state", $this->auth->get("profile.billing_custom_state"));
                $this->set("contactus_country", $this->auth->get("profile.billing_country"));
                $this->set("phone", $this->auth->get("profile.billing_phone"));
                $this->set("fax", $this->auth->get("profile.billing_fax"));
            } else {
                $this->set("contactus_state", $this->config->get("General.default_state"));
                $this->set("contactus_country", $this->config->get("General.default_country"));
            }
        }
    }

    function getState()
    {
        $s = func_new("State", $this->get("state_id"));
        return $s->get("state");
    }

    function getCountry()
    {
        $c = func_new("Country", $this->get("country_id"));
        return $c->get("country");
    }
    
    function action_contactus()
    {
        $mailer =& func_new("Mailer");
        $mailer->mapRequest();
        $st = func_new("State", $_REQUEST["contactus_state"]);
		if ($st->get("state_id") == -1) {
			$st->set("state", $_REQUEST["contactus_custom_state"]);
		}
        $mailer->set("state", $st->get("state")); // fetch state name
        $cn = func_new("Country", $_REQUEST["contactus_country"]);
        $mailer->set("country", $cn->get("country")); // fetch country name
		$mailer->set("charset", $cn->get("charset"));
        $mailer->compose($this->get("email"), $this->config->get("Company.support_department"), "contactus");
        $mailer->send();
        $this->set("mode", "contactusMessage");
    }

    function getCountriesStates()
    {
        if (!isset($this->_profileDialog)) {
            $this->_profileDialog =& func_new("Dialog_profile");
        }
        return $this->_profileDialog->getCountriesStates();
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
