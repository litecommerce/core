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
 * @subpackage Controller
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
class XLite_Module_Affiliate_Controller_Customer_PartnerProfile extends XLite_Module_Affiliate_Controller_Partner
{
    public $params = array('target', "mode", "submode", "returnUrl","parent"); // mode ::= register | modify | success | delete	
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


    function getTemplate() 
    {
        if ($this->get('mode') == "sent") {
            return "modules/Affiliate/login.tpl";
        }
        
        if ($this->get('mode') == "register") {
            if ($this->auth->is('logged')) {
                $this->redirect("cart.php?target=partner");
            } else {
                return "modules/Affiliate/login.tpl";
            }
        }
        return parent::getTemplate();
    }
    
    function getAccessLevel() 
    {
        if ($this->get('mode') == "register" || $this->get('mode') == "sent" || ($this->get('mode') == "delete" && $this->get('submode') == "confirmed")) {
            return 0;
        } else {
            return parent::getAccessLevel();
        }
    }

    function action_register()
    {
        if (!$this->getComplex('config.Affiliate.registration_enabled')) {
            $this->set('returnUrl', "cart.php?target=partner_profile&mode=register");
        } else {
            $this->registerForm->action_register();
            $this->set('mode', $this->registerForm->get('mode'));
        }
    }

    function action_modify()
    {
        $this->profileForm->action_modify();
        $this->set('mode', $this->profileForm->get('mode'));
    }

    function action_delete()
    {
        if ($this->auth->is('logged')) {
            $this->profile = $this->auth->get('profile');
            $this->auth->deletePartner($this->profile);
            $this->set('mode', "delete");
            $this->set('submode', "confirmed");
        }
    }
}
