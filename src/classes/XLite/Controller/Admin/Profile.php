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

namespace XLite\Controller\Admin;

/**
 * Profile management controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Profile extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Class name for the \XLite\View\Model\ form (optional)
     * 
     * @return string|null
     * @access protected
     * @since  3.0.0
     */
    protected function getModelFormClass()
    {
        return '\XLite\View\Model\Profile\Main';
    }

    /**
     * Modify profile
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function doActionModify()
    {
        return $this->getModelForm()->performAction('modify');
    }

    /**
     * actionPostprocessModify 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function actionPostprocessModify()
    {
        $params = array();

        if ($profileId = $this->getModelForm()->getRequestProfileId()) {

            // Update existsing profile: get profile ID from request
            $params = array('profile_id' => $profileId);

        } elseif ($this->getModelForm()->isRegisterMode()) {

            // Create new: getID of created profile or return to register page
            $params = $this->isActionError()
                ? array(self::PARAM_MODE => self::getRegisterMode())
                : array('profile_id' => $this->getModelForm()->getProfileId(false));
        }

        if (!empty($params)) {
            $this->setReturnUrl($this->buildURL('profile', '', $params));
        }
    }

    /**
     * Delete profile
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        $result = $this->getModelForm()->performAction('delete');

        if (!$this->isActionError()) {
            $this->setReturnUrl($this->buildURL('users'));
        }
    }


    /**
     * Return value for the "register" mode param
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getRegisterMode()
    {
        return 'register';
    }







    /**
     * Get countries/states arrays
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    /*public function getCountriesStates()
    {
        $countriesArray = array();

        $country = new \XLite\Model\Country();
        $countries = $country->findAll("enabled='1'");
        foreach ($countries as $country) {
            $countriesArray[$country->get('code')]['number'] = 0;
            $countriesArray[$country->get('code')]['data'] = array();

            $state = new \XLite\Model\State();
            $states = $state->findAll("country_code='".$country->get('code')."'");
            if (is_array($states) && count($states) > 0) {
                $countriesArray[$country->get('code')]['number'] = count($states);
                foreach ($states as $state) {
                    $countriesArray[$country->get('code')]['data'][$state->get('state_id')] = $state->get('state');
                }
            }
        }

        return $countriesArray;
    }*/


    /**
     * params 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
/*    public $params = array('target', "mode", "profile_id", "backUrl");

    /**
     * Default mode value (register | modify | success | delete)
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
/*	protected $defaultMode = "modify";

    /**
     * alowedModes 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
/*    protected $allowedModes = array('modify', 'register');

    /**
     * backUrl 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
/*	public $backUrl = "admin.php?target=users";

    /**
     * User profile
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
/*	protected $profile = null;

    /**
     * Create/modify profile status data 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
/*    protected $statusData = array(
        'userExists' => null,
        'userAdmin' => null,
        'valid' => null,
        'success' => null
    );

    /**
     * getMode 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
  /*  public function getMode() {
        $_mode = \XLite\Core\Request::getInstance()->mode;

        if (empty($_mode) || !in_array($_mode, $this->allowedModes))
            $_mode = $this->defaultMode;

        return $_mode;
    }

    /*
    protected function getDeleteUrl()
    {
        $params = $this->get('allParams');
        $params['mode'] = "delete";
        return $this->getUrl($params);
    }
     */

    /**
     * Request modification if mode='delete'
     * TODO: Delete confirmation should be reviewed and this function must be removed
     * (confirmation must be for all users)
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function handleRequest()
    {
        if ('delete' == $this->getMode()) {

            $profile = $this->getProfile();

            if (!$profile->isAdmin() || !$profile->isEnabled() || !$this->auth->isLastAdmin($profile)) {
                // perform delete; no confirmation
                \XLite\Core\Request::getInstance()->action = 'delete';
            }
        }

        parent::handleRequest();
    }

    /**
     * Get user profile 
     * 
     * @return \XLite\Model\Profile object
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function getProfile()
    {
        if (is_null($this->profile)) {
            $this->profile = new \XLite\Model\Profile(\XLite\Core\Request::getInstance()->profile_id);
        }

        return $this->profile;
    }

    /**
     * Get countries/states arrays
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */

    /**
     * Do action 'register'
     * TODO: code is need to be refactored
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function doActionRegister()
    {
/* TODO: remove - do not need to reinitialize sesion when admin creates user 

    	if (
            isset($_REQUEST[\XLite\Model\Session::SESSION_DEFAULT_NAME]) 
            && !(isset($_GET[\XLite\Model\Session::SESSION_DEFAULT_NAME]) || isset($_POST[\XLite\Model\Session::SESSION_DEFAULT_NAME]))
        ) {
    		unset($_REQUEST[\XLite\Model\Session::SESSION_DEFAULT_NAME]);
        }

        $this->xlite->session->set('_' . \XLite\Model\Session::SESSION_DEFAULT_NAME, \XLite\Model\Session::SESSION_DEFAULT_NAME . '=' . $this->xlite->session->getID());
        $this->xlite->session->destroy();
        $this->xlite->session->setID(SESSION_DEFAULT_ID);
        $this->xlite->session->_initialize();
 */
/*        $this->profile = new \XLite\Model\Profile();

        if ($this->xlite->is('adminZone')) {
            $this->profile->modifyAdminProperties(\XLite\Core\Request::getInstance()->getData());

        } else {
            $this->profile->modifyCustomerProperties(\XLite\Core\Request::getInstance()->getData());
        }

        if (!$this->isFromCheckout()) {

            $result = $this->auth->register($this->profile);

            if (USER_EXISTS == $result) {
                $this->statusData['userExists'] = true;
                $this->statusData['valid'] = false; // can't go thru

            } else {
                $this->set('mode', 'success'); // go to success page
            }

        } else {
            // fill in shipping info
            $this->auth->copyBillingInfo($this->profile);
            $this->profile->update();
            $this->statusData['success'] = true;
        }

        if ('success' == $this->getMode()) {
            $this->set('returnUrl', $this->buildUrl('profile', '', array('profile_id' => $this->profile->get('profile_id'))));
        }
    }

    /**
     * Do action 'modify'
     * TODO: code is need to be refactored
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function doActionModify()
    {
        if ($this->xlite->is('adminZone')) {
            $this->profile->modifyAdminProperties(\XLite\Core\Request::getInstance()->getData());

        } else {

        	if ($this->xlite->auth->isAdmin($this->profile)) {
        		$this->statusData['valid'] = false;
        		$this->statusData['userAdmin'] = true;
        		return;
        	}

            $this->profile->modifyCustomerProperties(\XLite\Core\Request::getInstance()->getData());
        }

        if (!$this->isFromCheckout()) {

            $result = $this->auth->modifyProfile($this->profile);

            if (USER_EXISTS == $result) {
                // user already exists
                $this->statusData['userExists'] = true;
                $this->statusData['valid'] = false;

            } else {
                $this->statusData['success'] = true;
            }

        } else {
            // fill in shipping info
            $this->auth->copyBillingInfo($this->profile);
            $this->profile->update();
            $this->statusData['success'] = true;
        }
    }

    /**
     * Do action 'delete'
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function doActionDelete()
    {
        // unregister and delete profile
        $this->auth->unregister($this->get('profile'));
        // switch back to search for user
        $this->set('returnUrl', $this->get('backUrl'));
    }

    // TODO: remove this from admin controller
    protected function isFromCheckout()
    {
        return (strpos($this->returnUrl, 'target=checkout') !== false) ? true : false;
    }
*/
}

