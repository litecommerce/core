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
class XLite_Module_Affiliate_Controller_Customer_PartnerProfile extends XLite_Module_Affiliate_Controller_Partner
{	
    public $params = array("target", "mode", "submode", "returnUrl","parent"); // mode ::= register | modify | success | delete	
    public $mode = "register";	
    public $submode = "warning"; // delete profile status: warning | confirmed | cancelled


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


    function getTemplate() // {{{
    {
        if ($this->get("mode") == "sent") {
            return "modules/Affiliate/login.tpl";
        }
        
        if ($this->get("mode") == "register") {
            if ($this->auth->is("logged")) {
                $this->redirect("cart.php?target=partner");
            } else {
                return "modules/Affiliate/login.tpl"; 
            }
        }
        return parent::getTemplate();
    } // }}}
    
    function getAccessLevel() // {{{
    {
        if ($this->get("mode") == "register" || $this->get("mode") == "sent" || ($this->get("mode") == "delete" && $this->get("submode") == "confirmed")) {
            return 0;
        } else {
            return parent::getAccessLevel();
        }
    } // }}}

    function action_register()
    {
        if (!$this->getComplex('config.Affiliate.registration_enabled')) {
            $this->set("returnUrl", "cart.php?target=partner_profile&mode=register");
        } else {
            $this->registerForm->action_register();
            $this->set("mode", $this->registerForm->get("mode"));
        } 
    }

    function action_modify()
    {
        $this->profileForm->action_modify();
        $this->set("mode", $this->profileForm->get("mode"));
    }

    function action_delete()
    {
        if ($this->auth->is("logged")) {
            $this->profile = $this->auth->get("profile");
            $this->auth->deletePartner($this->profile);
            $this->set("mode", "delete");
            $this->set("submode", "confirmed");
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
