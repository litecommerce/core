<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Affiliate_Model_Auth extends XLite_Model_Auth implements XLite_Base_IDecorator
{
    function isAuthenticated($profile)
    {
        return $profile->find("login='".addslashes($profile->get('login'))."' AND status='E' AND password='".$this->encryptPassword($profile->get('password'))."' AND order_id=0");
    }

    function registerPartner($profile)
    {
        // if profile already exists, check password
        // register it otherwise
        $result = $this->register($profile);
        if ($result == USER_EXISTS && !$profile->find("login='".addslashes($profile->get('login'))."' AND status='E' AND password='".$this->encryptPassword($_POST['password'])."'")) {
            return ACCESS_DENIED;
        } elseif ($result == USER_EXISTS) {
            // unset existing profile password before update
            $profile->set("password", null);
        }
        $this->loginProfile($profile);
        if (!$this->getComplex('config.Affiliate.moderated')) {
            // approve partner for non-moderated registration
            $this->approvePartner($profile);
        } else {
            // assign pending access level
            $this->pendPartner($profile);
        }
        $profile->update();
        $mailer = new XLite_Model_Mailer();
        $mailer->profile = $profile;
        // mailto customer with a new signup notification
        $mailer->compose($this->getComplex('config.Company.site_administrator'),
                $profile->get('login'),
                $this->getComplex('config.Affiliate.moderated') ? "modules/Affiliate/partner_signin_notification" : "modules/Affiliate/partner_signin_confirmation"
                );
        $mailer->send();
        // mailto admin with a new partner signup notification
        $mailer->compose($this->getComplex('config.Company.site_administrator'),
                $this->getComplex('config.Company.users_department'),
                "modules/Affiliate/partner_signin_admin_notification"
                );
        $mailer->send();

        return REGISTER_SUCCESS;
    }

    function deletePartner($profile)
    {
        $this->unregister($profile);
    }

    function declinePartner($profile)
    {
        $profile->set("access_level", $this->get('declinedPartnerAccessLevel'));
        $profile->update();
        // sent notification to customer
        $mailer = new XLite_Model_Mailer();
        $mailer->profile = $profile;
        $mailer->compose(
                $this->getComplex('config.Company.site_administrator'),
                $profile->get('login'),
                "modules/Affiliate/partner_declined"
                );
        $mailer->send();
    }
    
    function pendPartner($profile)
    {
        if ($profile->get('access_level') < $this->get('pendingPartnerAccessLevel')) {
            $profile->set("access_level", $this->get('pendingPartnerAccessLevel'));
        }
        // mailto customer with a new signup notification
        $mailer = new XLite_Model_Mailer();
        $mailer->profile = $profile;
        $mailer->compose($this->getComplex('config.Company.site_administrator'),
                $profile->get('login'),
                "modules/Affiliate/partner_signin_notification"
                );
        $mailer->send();

    }
    
    function approvePartner($profile)
    {
        if ($profile->get('access_level') < $this->getPartnerAccessLevel()) {
            $profile->set("access_level", $this->getPartnerAccessLevel());
        }
        $profile->set("plan", $profile->get('pending_plan'));
        // mailto customer with a new signup notification
        $mailer = new XLite_Model_Mailer();
        $mailer->profile = $profile;
        $mailer->compose($this->getComplex('config.Company.site_administrator'),
                $profile->get('login'),
                "modules/Affiliate/partner_signin_confirmation"
                );
        $mailer->send();

    }
    
    function isPartner($profile)
    {
        return $profile->get('access_level') == $this->getPartnerAccessLevel();
    }
    function isPendingPartner($profile)
    {
        return $profile->get('access_level') == $this->getPendingPartnerAccessLevel();
    }
    function isDeclinedPartner($profile)
    {
        return $profile->get('access_level') == $this->getDeclinedPartnerAccessLevel();
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
        $userTypes['partner'] = "Partner";
        $userTypes['pendingPartner'] = "Pending Partner";
        $userTypes['declinedPartner'] = "Declined Partner";
        return $userTypes;
    }
}
