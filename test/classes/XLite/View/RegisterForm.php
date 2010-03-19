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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Registration form component.
*
* @package $Package$
* @version $Id$
*/
class XLite_View_RegisterForm extends XLite_View_Abstract
{
    public $params = array("success");

	protected $success = false;

	public $profile = null;

    // whether to login user after successful registration or not	
    public $autoLogin = true;
    // true if the user already exists in the register form	
    public $userExists = false;	
    public $allowAnonymous = false;

    function isShowMembership()
    {
        return count($this->config->getComplex('Memberships.memberships')) > 0;
    }

    function isFromCheckout()
    {
        return (strpos($this->returnUrl, "target=checkout") !== false) ? true : false;
    }

    function getSuccess()
    {
        return $this->is("valid") && $this->success;
    }

	function fillForm()
    {
        if ($this->get("mode") == "register") {
            // default registration form values
            $this->billing_country = $this->config->getComplex('General.default_country');
            $this->billing_zipcode = $this->config->getComplex('General.default_zipcode');
            $this->shipping_country = "";
            $this->billing_state = $this->shipping_state = "";
        }

        if (!is_null($this->profile)) {
            $this->set("properties", $this->profile->get('properties'));
            // don't show passwords
            $this->password = $this->confirm_password = "";
        }

        parent::fillForm();
    }
    
    function action_register()
    {
    	if (
			isset($_REQUEST[XLite_Model_Session::SESSION_DEFAULT_NAME]) 
			&& !(isset($_GET[XLite_Model_Session::SESSION_DEFAULT_NAME]) || isset($_POST[XLite_Model_Session::SESSION_DEFAULT_NAME]))
		) {
    		unset($_REQUEST[XLite_Model_Session::SESSION_DEFAULT_NAME]);
    	}
		$this->xlite->session->set("_".XLite_Model_Session::SESSION_DEFAULT_NAME, XLite_Model_Session::SESSION_DEFAULT_NAME."=".$this->xlite->session->getID());
		$this->xlite->session->destroy();
		$this->xlite->session->setID(SESSION_DEFAULT_ID);
		$this->xlite->session->_initialize();

        $this->profile = new XLite_Model_Profile();
        if ($this->xlite->is("adminZone")) {
            $this->profile->modifyAdminProperties($_REQUEST);
        } else {
            $this->profile->modifyProperties($_REQUEST);
        }
        if (!$this->isFromCheckout()) {
            $result = $this->auth->register($this->profile);
            if ($result == USER_EXISTS) {
                $this->set("userExists", true);
                $this->set("valid", false); // can't go thru
            } else {
                $this->set("mode", "success"); // go to success page
            }
        } else {
            // fill in shipping info
            $this->auth->copyBillingInfo($this->profile);
            $this->profile->update();
			$this->set("success", true);
        }
    }

    function action_modify()
    {
        if ($this->xlite->is("adminZone")) {
            $this->profile->modifyAdminProperties($_REQUEST);
        } else {
        	if ($this->xlite->auth->isAdmin($this->profile)) {
        		$this->set("valid", false);
        		$this->set("userAdmin", true);
        		return;
        	}

            $this->profile->modifyProperties($_REQUEST);
        }
        if (!$this->isFromCheckout()) {
            $result = $this->auth->modify($this->profile);
            if ($result == USER_EXISTS) {
                // user already exists
                $this->set("userExists", true);
                $this->set("valid", false);
            } else {
                $this->set("success", true);
            }
        } else {
            // fill in shipping info
            $this->auth->copyBillingInfo($this->profile);
            $this->profile->update();
			$this->set("success", true);
        }
    }
    
}

