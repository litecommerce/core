<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2007 Creative Development <info@creativedevelopment.biz>  |
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
* @version $Id: profile.php,v 1.3 2007/05/21 11:53:28 osipov Exp $
*/
class Dialog_profile extends Dialog
{
    var $params = array("target", "mode", "submode", "returnUrl"); // mode ::= register | modify | success | delete 
    var $mode = "register"; // default mode
    var $submode = "warning"; // delete profile status: warning | confirmed | cancelled

	function fillForm()
	{
		parent::fillForm();

		$login = $this->get("login");
		if ( $this->get("mode") == "login" && empty($login) ) {
			$this->set("login", $this->auth->remindLogin());
		}
	}

    function _initAuthProfile()
    {
        if (!is_null($this->auth->get("profile"))) {
            $this->profileForm->profile = $this->auth->get("profile");
        }
    }

    function init()
    {
        parent::init();
		if ($this->profileForm->isFromCheckout()) {
            $cart =& func_get_instance("Cart");
            if (!$cart->isEmpty()) {
            	$this->profileForm->profile = $cart->get("profile");
    		} else {
    			$this->_initAuthProfile();
            }
		} else {
			$this->_initAuthProfile();
        }
    }
    
    function handleRequest()
    {
        if (($this->get("mode") == "modify" || $this->get("mode") == "account") && !$this->auth->is("logged"))
        {     
            // can't modify profile if not logged - create one
            $this->set("mode", "register");
            $this->redirect();
        } else {
            parent::handleRequest();
            $this->updateCart();
        }
    }

    function getSecure()
    {
        switch ($this->get("mode")) {
            case "register":
            case "modify"  : 
            case "login"  : 
            case "account" : 
            case "success" : 
                return $this->get("config.Security.customer_security");
            default:
                return false;
        }
    }

    function getCountriesStates()
    {
        $countriesArray = array();

        $country =& func_new("Country");
        $countries =& $country->findAll("enabled='1'");
        foreach($countries as $country) {
            $countriesArray[$country->get("code")]["number"] = 0;
            $countriesArray[$country->get("code")]["data"] = array();

            $state =& func_new("State");
            $states =& $state->findAll("country_code='".$country->get("code")."'");
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
        $this->set("mode", $this->registerForm->get("mode"));
        if ($this->registerForm->is("valid")) {
            $this->auth->loginProfile($this->registerForm->get("profile"));
            $this->recalcCart();
        }
    }

    function action_modify()
    {
        $this->profileForm->action_modify();
        $this->set("mode", $this->profileForm->get("mode"));

        if ($this->registerForm->is("valid")) {
			$cart =& func_get_instance("Cart");
			if (!$cart->isEmpty()) {
				$cart->set("profile_id", $this->profileForm->profile->get("profile_id"));
				$cart->setProfile($this->profileForm->profile);
				$cart->update();
        		$this->recalcCart();
			}
		}
    }

    function action_delete()
    {
        if ($this->auth->is("logged")) {
            $this->profile =& $this->auth->get("profile");
            $this->auth->unregister($this->profile);
        	$this->recalcCart();
            $this->set("mode", "delete");
            $this->set("submode", "confirmed");
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
