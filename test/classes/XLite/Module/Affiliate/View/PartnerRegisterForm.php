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
* @package Module_Affiliate
* @version $Id$
*/
class XLite_Module_Affiliate_View_PartnerRegisterForm extends XLite_View_RegisterForm implements XLite_Base_IDecorator
{
    function fillForm()
    {
        parent::fillForm();
        $this->pending_plan = $this->config->getComplex('Affiliate.default_plan');
        if (!$this->xlite->is("adminZone") && $this->auth->is("logged")) {
			$this->_savedParent = (isset($this->parent)) ? $this->parent : null;
            $this->set("properties", $this->auth->getComplex('profile.properties'));
            if (isset($this->_savedParent)) {
            	$this->set("parent", $this->_savedParent);
            }
            $this->setComplex("profile.parent", $this->parent);
            // don't show passwords
            $this->password = $this->confirm_password = "";
        }
    }

    function action_register()
    {
        parent::action_register();
        if (isset($_POST["pending_plan"])) { // partner's profile POST'ed..
            if ($this->is("userExists") && !$this->auth->is("logged")) {
                // new partner profile but existing user
                return;
            }
            // register partner
            $result = $this->auth->registerPartner($this->profile);
            if ($result == ACCESS_DENIED) {
                $this->set("invalidPassword", true);
            } else {
                $this->set("valid", true);
                $this->set("mode", $this->getComplex('config.Affiliate.moderated') ? "sent" : "success"); // go to success page
            }    
        }
    }    
    
    function getProfile()
    {
        if (!$this->xlite->is("adminZone") && $this->auth->is("logged")) {
            $this->profile = $this->auth->get("profile");
        }
        if (is_null($this->profile)) {
            $this->profile = new XLite_Model_Profile(isset($_REQUEST["profile_id"]) ? $_REQUEST["profile_id"] : null);
        }
        return $this->profile;
    }
    
    function isShowPartnerFields()
    {
        return !is_null($this->profile) && ($this->profile->is("declinedPartner") || $this->profile->is("pendingPartner") || $this->profile->is("partner"));
    }

    function getPartnerFields()
    {
        if (is_null($this->partnerFields)) {
            $pf = new XLite_Module_Affiliate_Model_PartnerField();
            $this->partnerFields = $pf->findAll();
        }
        return $this->partnerFields;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
