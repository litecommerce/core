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
class XLite_Controller_Customer_Profile extends XLite_Controller_Customer_Abstract
{
    public $params = array('target', "mode", "submode", "returnUrl"); // mode ::= register | modify | success | delete	 
    public $mode = "register"; // default mode	
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

        $login = $this->get('login');
        if ( $this->get('mode') == "login" && empty($login) ) {
            $this->set('login', $this->auth->remindLogin());
        }
    }

    function _initAuthProfile()
    {
        if (isset($this->profileForm) && !is_null($this->auth->get('profile'))) {
            $this->profileForm->profile = $this->auth->get('profile');
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
        if (($this->get('mode') == "modify" || $this->get('mode') == "account") && !$this->auth->is('logged'))
        {
            // can't modify profile if not logged - create one
            $this->set('mode', "register");
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
            switch ($this->get('mode')) {
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
        $this->set('mode', $this->registerForm->get('mode'));
        if ($this->registerForm->is('valid')) {
            $this->auth->loginProfile($this->registerForm->get('profile'));
            $this->recalcCart();
        }
    }

    function action_modify()
    {
        $this->profileForm->action_modify();
        $this->set('mode', $this->profileForm->get('mode'));

        if ($this->registerForm->is('valid')) {
            $cart = XLite_Model_Cart::getInstance();
            if (!$cart->isEmpty()) {
                $cart->set('profile_id', $this->profileForm->profile->get('profile_id'));
                $cart->setProfile($this->profileForm->profile);
                $cart->update();
        		$this->recalcCart();
            }
        }
    }

    function action_delete()
    {
        if ($this->auth->is('logged')) {
            $this->profile = $this->auth->get('profile');
            if ($this->profile->isAdmin()) {
                $this->set('mode', "delete");
                $this->set('submode', "cancelled");
                return;
            }

            $this->auth->unregister($this->profile);
        	$this->recalcCart();
            $this->set('mode', "delete");
            $this->set('submode', "confirmed");
        }
    }
}
