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
class XLite_Controller_Admin_Profile extends XLite_Controller_Admin_Abstract
{	
    public $params = array("target", "mode", "profile_id", "backUrl"); // mode ::= register | modify | success | delete	 
    public $mode = "modify"; // default mode	
    public $backUrl = "admin.php?target=users";

	protected $profile = null;

    function getDeleteUrl()
    {
        $params = $this->get("allParams");
        $params["mode"] = "delete";
        return $this->getUrl($params);
    }

    function init()
    {
        parent::init();
        $this->profileForm->profile = $this->get("profile");
    }

    function handleRequest()
    {
        if ($this->get("mode") == "delete") {
            if (!$this->is("profile.admin") ||
                !$this->is("profile.enabled") ||
                !$this->auth->isLastAdmin($this->get("profile"))) {
                // perform delete; no confirmation
                $_REQUEST['action'] = "delete";
            }
        }
        parent::handleRequest();
    }

    function getProfile()
    {
        if (is_null($this->profile)) {
            $this->profile = new XLite_Model_Profile($this->get("profile_id"));
        }
        return $this->profile;
    }

    function getCountriesStates()
    {
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

    function action_register()
    {
        $this->registerForm->action_register();
        if ($this->registerForm->get("mode") == "success") {
            $this->set("returnUrl", "admin.php?target=profile&profile_id=" . $this->registerForm->get("profile.profile_id"));
        }
    }

    function action_modify()
    {
        $this->profileForm->action_modify();
        $this->set("mode", $this->profileForm->get("mode"));
    }

    function action_delete()
    {
        // unregister and delete profile
        $this->auth->unregister($this->get("profile"));
        // switch back to search for user
        $this->set("returnUrl", $this->get("backUrl"));
    }

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
