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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Registration form widget
 *
 * @package    XLite
 * @subpackage View
 * @since      3.0.0
 */
class XLite_View_RegisterForm extends XLite_View_Dialog
{
    /*
     * Widget parameters names
     */
    const PARAM_HEAD = 'head';

    public $params = array("success");

	protected $success = false;

//	public $profile = null;

    // whether to login user after successful registration or not	
    public $autoLogin = true;
    // true if the user already exists in the register form	
    public $userExists = false;	
    public $allowAnonymous = false;

    /**
     * initView 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function initView()
    {
        parent::initView();
        $this->fillForm();
    }

    /**
     * Get directory where template is located (body.tpl)
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'profile';
    }

    /**
     * Get dialog title
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHead()
    {
        return $this->getParam(self::PARAM_HEAD);
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

		$this->widgetParams += array(
            self::PARAM_HEAD      => new XLite_Model_WidgetParam_String('Dialog title', 'Profile details', false),
		);
    }

    /**
     * Get mode 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMode()
    {
        $mode = XLite_Core_Request::getInstance()->mode;

        if (is_null($mode) || !in_array($mode, array('modify', 'register'))) {
            $mode = 'modify';
        }

        return $mode;
    }

    protected function isShowMembership()
    {
        return count($this->config->Memberships->memberships) > 0;
    }

    protected function isFromCheckout()
    {
        return (strpos($this->returnUrl, 'target=checkout') !== false) ? true : false;
    }

    protected function getSuccess()
    {
        return $this->is('valid') && $this->success;
    }

    
    /**
     * Get profile
     * 
     * @return XLite_Model_Profile object
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProfile() 
    {
        $className = ($this->xlite->is('adminZone') ? 'XLite_Controller_Admin_Profile' : 'XLite_Controller_Customer_Profile');
        $controllerObj = $this->getInternalInstance($className);
        return $controllerObj->getProfile();
    }

    /**
     * Fill the form with the profile details 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
	protected function fillForm()
    {
        if ('register' == XLite_Core_Request::getInstance()->mode) {
            // default registration form values
            $this->billing_country = $this->config->General->default_country;
            $this->billing_zipcode = $this->config->General->default_zipcode;
            $this->shipping_country = '';
            $this->billing_state = $this->shipping_state = '';
        }

        $profile = $this->getProfile();

        if (!is_null($profile)) {
            $this->set('properties', $profile->get('properties'));
            // don't show passwords
            $this->password = $this->confirm_password = '';
        }

        parent::fillForm();
    }

    // TODO: remove the functions below (move it to controllers)

    protected function action_register()
    {
    	if (
			isset($_REQUEST[XLite_Model_Session::SESSION_DEFAULT_NAME]) 
			&& !(isset($_GET[XLite_Model_Session::SESSION_DEFAULT_NAME]) || isset($_POST[XLite_Model_Session::SESSION_DEFAULT_NAME]))
		) {
    		unset($_REQUEST[XLite_Model_Session::SESSION_DEFAULT_NAME]);
        }

		$this->xlite->session->set('_' . XLite_Model_Session::SESSION_DEFAULT_NAME, XLite_Model_Session::SESSION_DEFAULT_NAME . '=' . $this->xlite->session->getID());
		$this->xlite->session->destroy();
		$this->xlite->session->setID(SESSION_DEFAULT_ID);
		$this->xlite->session->_initialize();

        $this->profile = new XLite_Model_Profile();

        if ($this->xlite->is('adminZone')) {
            $this->profile->modifyAdminProperties($_REQUEST);

        } else {
            $this->profile->modifyProperties($_REQUEST);
        }

        if (!$this->isFromCheckout()) {

            $result = $this->auth->register($this->profile);

            if ($result == USER_EXISTS) {
                $this->set('userExists', true);
                $this->set('valid', false); // can't go thru

            } else {
                $this->set('mode', 'success'); // go to success page
            }

        } else {
            // fill in shipping info
            $this->auth->copyBillingInfo($this->profile);
            $this->profile->update();
			$this->set('success', true);
        }
    }

        protected function action_modify()
    {
        if ($this->xlite->is('adminZone')) {
            $this->profile->modifyAdminProperties($_REQUEST);

        } else {

        	if ($this->xlite->auth->isAdmin($this->profile)) {
        		$this->set('valid', false);
        		$this->set('userAdmin', true);
        		return;
        	}

            $this->profile->modifyProperties($_REQUEST);
        }

        if (!$this->isFromCheckout()) {

            $result = $this->auth->modify($this->profile);

            if ($result == USER_EXISTS) {
                // user already exists
                $this->set('userExists', true);
                $this->set('valid', false);

            } else {
                $this->set('success', true);
            }

        } else {
            // fill in shipping info
            $this->auth->copyBillingInfo($this->profile);
            $this->profile->update();
			$this->set('success', true);
        }
    }
    
}

