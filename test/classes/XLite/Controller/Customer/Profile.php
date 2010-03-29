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
class XLite_Controller_Customer_Profile extends XLite_Controller_Customer_Abstract
{	
    public $params = array("target", "mode", "submode", "returnUrl"); // mode ::= register | modify | success | delete	 
    public $mode = "register"; // default mode	
    public $submode = "warning"; // delete profile status: warning | confirmed | cancelled

	/**
	 * getCountriesStates 
	 * 
	 * @return array
	 * @access public
	 * @since  3.0.0
	 */
	public function getCountriesStates()
    {
		$statesInfo = XLite_Model_Factory::create('XLite_Model_Country')->getCountryStatesListSchema('enabled = \'1\'');

		foreach (XLite_Model_Factory::create('XLite_Model_State')->findAll() as $state) {

			$countryCode = $state->get('country_code');

			if (isset($statesInfo[$countryCode])) {
				$statesInfo[$countryCode]['number']++;
				$statesInfo[$countryCode]['data'][$state->get('state_id')] = $state->get('state');
			}
		}

		return $statesInfo;
    }


	/**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0
     */
    protected function getLocation()
    {
		$location = parent::getLocation();

		switch ($this->get('mode')) {
			case 'login':
				$location = 'Authentication';
				break;
			case 'modify':
				$location = 'Modify profile';
                break;
			case 'delete':
				$location = 'Delete profile';
                break;
			case 'register':
			case 'success':
				$location = 'New member';
                break;
		}

		return $location;
    }


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
        if (isset($this->profileForm) && !is_null($this->auth->get("profile"))) {
            $this->profileForm->profile = $this->auth->get("profile");
			$this->profileForm->fillForm();
        }
    }

    function init()
    {
        parent::init();

		if (isset($this->profileForm) && $this->profileForm->isFromCheckout()) {

            $cart = XLite_Model_Cart::getInstance();
			$cart->isEmpty() ? $this->_initAuthProfile() : $this->profileForm->profile = $cart->get('profile');

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
        if ($this->getComplex('config.Security.full_customer_security')) {
            return true;
        } else {
            switch ($this->get("mode")) {
                case "register":
                case "modify"  : 
                case "login"  : 
                case "account" : 
                case "success" : 
                    return $this->getComplex('config.Security.customer_security');
                default:
                    return false;
            }
        }
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
			$cart = XLite_Model_Cart::getInstance();
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
			$this->profile = $this->auth->get("profile");
            if ($this->profile->isAdmin()) {
                $this->set("mode", "delete");
                $this->set("submode", "cancelled");
                return;
            }

            $this->auth->unregister($this->profile);
        	$this->recalcCart();
            $this->set("mode", "delete");
            $this->set("submode", "confirmed");
        }
    }
}

