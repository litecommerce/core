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
* Class description.
*
* @package Dialog
* @access public
* @version $Id$
*/

class XLite_Module_SnsIntegration_Model_Auth extends XLite_Model_Auth implements XLite_Base_IDecorator
{
    function register($profile) // {{{
    {
        $result = parent::register($profile);
        if ($result == REGISTER_SUCCESS) {
        	if (strlen($profile->get("password")) && !($this->xlite->is("adminZone") || $this->xlite->is("aspZone"))) {
            	// not anonymous
            	$this->sendProfileAction($profile, "Register");
            }
        }
        return $result;
    } // }}}

    function modify($profile) // {{{
    {
        $result = parent::modify($profile);
        if ($result == REGISTER_SUCCESS) {
        	if (!$this->session->get("anonymous") && !($this->xlite->is("adminZone") || $this->xlite->is("aspZone"))) {
            	// not anonymous
            	$this->sendProfileAction($profile, "Register");
            }
        }
        return $result;
    } // }}}
            
    function loginProfile(XLite_Model_Profile $profile) // {{{
    {
        if (strlen($profile->get("password")) && !($this->xlite->is("adminZone") || $this->xlite->is("aspZone"))) {
            // not anonymous
			$this->sendProfileAction($profile, "Login");
        }
        parent::loginProfile($profile);
    } // }}}

    function sendProfileAction($profile, $action) // {{{
    {    
        require_once LC_MODULES_DIR . 'SnsIntegration' . LC_DS . 'include' . LC_DS . 'misc.php';

        $snsClientId  = func_get_sns_client_id(); 
        
        $actions = array();
        $action = "name=$action".func_sns_profile_params($profile);
        $actions[]= $action;

        func_sns_request($this->config, $snsClientId, $actions);
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
