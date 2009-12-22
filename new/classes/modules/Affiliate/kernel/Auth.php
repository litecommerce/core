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
class Module_Affiliate_Auth extends Auth
{
    function isAuthenticated(&$profile)
    {
        return $profile->find("login='".addslashes($profile->get("login"))."' AND status='E' AND password='".$this->encryptPassword($profile->get("password"))."' AND order_id=0");
    }

    function registerPartner(&$profile)
    {
        // if profile already exists, check password
        // register it otherwise
        $result = $this->register($profile);
        if ($result == USER_EXISTS && !$profile->find("login='".addslashes($profile->get("login"))."' AND status='E' AND password='".$this->encryptPassword($_POST["password"])."'")) {
            return ACCESS_DENIED;
        } elseif ($result == USER_EXISTS) {
            // unset existing profile password before update
            $profile->set("password", null);
        }
        $this->loginProfile($profile);
        if (!$this->get("config.Affiliate.moderated")) {
            // approve partner for non-moderated registration
            $this->approvePartner($profile);
        } else {
            // assign pending access level
            $this->pendPartner($profile);
        }
        $profile->update();
        $mailer = func_new("Mailer");
        $mailer->profile = $profile;
        // mailto customer with a new signup notification
        $mailer->compose($this->get("config.Company.site_administrator"),
                $profile->get("login"),
                $this->get("config.Affiliate.moderated") ? "modules/Affiliate/partner_signin_notification" : "modules/Affiliate/partner_signin_confirmation"
                );
        $mailer->send();
        // mailto admin with a new partner signup notification
        $mailer->compose($this->get("config.Company.site_administrator"),
                $this->get("config.Company.users_department"),
                "modules/Affiliate/partner_signin_admin_notification"
                );
        $mailer->send();

        return REGISTER_SUCCESS;
    }

    function deletePartner(&$profile)
    {
        $this->unregister($profile);
    }

    function declinePartner(&$profile)
    {
        $profile->set("access_level", $this->get("declinedPartnerAccessLevel"));
        $profile->update();
        // sent notification to customer
        $mailer = func_new("Mailer");
        $mailer->profile = $profile;
        $mailer->compose(
                $this->get("config.Company.site_administrator"),
                $profile->get("login"),
                "modules/Affiliate/partner_declined"
                );
        $mailer->send();
    }
    
    function pendPartner(&$profile)
    {
        if ($profile->get("access_level") < $this->get("pendingPartnerAccessLevel")) {
            $profile->set("access_level", $this->get("pendingPartnerAccessLevel"));
        }    
        // mailto customer with a new signup notification
        $mailer = func_new("Mailer");
        $mailer->profile = $profile;
        $mailer->compose($this->get("config.Company.site_administrator"),
                $profile->get("login"),
                "modules/Affiliate/partner_signin_notification"
                );
        $mailer->send();

    }
    
    function approvePartner(&$profile)
    {
        if ($profile->get("access_level") < $this->getPartnerAccessLevel()) {
            $profile->set("access_level", $this->getPartnerAccessLevel());
        }    
        $profile->set("plan", $profile->get("pending_plan"));
        // mailto customer with a new signup notification
        $mailer = func_new("Mailer");
        $mailer->profile = $profile;
        $mailer->compose($this->get("config.Company.site_administrator"),
                $profile->get("login"),
                "modules/Affiliate/partner_signin_confirmation"
                );
        $mailer->send();

    }
    
    function isPartner(&$profile)
    {
        return $profile->get("access_level") == $this->getPartnerAccessLevel();
    }
    function isPendingPartner(&$profile)
    {
        return $profile->get("access_level") == $this->getPendingPartnerAccessLevel();
    }
    function isDeclinedPartner(&$profile)
    {
        return $profile->get("access_level") == $this->getDeclinedPartnerAccessLevel();
    }

    function getPartnerAccessLevel()
    {
        return 10;
    }
    function getPendingPartnerAccessLevel()
    {
        return 5;
    }
    function getDeclinedPartnerAccessLevel()
    {
        return 2;
    }

    function getAccessLevel($user)
    {
        return parent::getAccessLevel(preg_replace("/[ _]/i", "", $user));
    }
    
    function getUserTypes()
    {
        $userTypes = parent::getUserTypes();
        $userTypes["partner"] = "Partner";
        $userTypes["pendingPartner"] = "Pending Partner";
        $userTypes["declinedPartner"] = "Declined Partner";
        return $userTypes;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
