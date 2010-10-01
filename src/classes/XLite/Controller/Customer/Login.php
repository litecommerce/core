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

namespace XLite\Controller\Customer;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Login extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Index in request array; the secret token used for authorization
     */
    const SECURE_TOKEN = 'secureToken';


    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Authentication';
    }

     /**
     * Perform some actions before redirect
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function actionPostprocessLogin()
    {
        $this->redirectFromLogin();
    }

    /**
     * Return URL to redirect from login
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getRedirectFromLoginURL()
    {
        return null;
    }


    /**
     * Perform some actions after the "login" action
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function redirectFromLogin()
    {
        $url = $this->getRedirectFromLoginURL();

        if (isset($url)) {
            \XLite\Core\CMSConnector::isCMSStarted() 
                ? \XLite\Core\Operator::redirect($url, true) 
                : $this->setReturnUrl($url);
        }
    }



    public $params = array('target', "mode");

    protected $profile = null;

    /**
     * Log in using the login and password from request
     * 
     * @return \XLite\Model\Profile
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function performLogin()
    {
        $data = \XLite\Core\Request::getInstance()->getData();
        $token = empty($data[self::SECURE_TOKEN]) ? null : $data[self::SECURE_TOKEN];

        return \XLite\Model\Auth::getInstance()->login($data['login'], $data['password'], $token);
    }

    function action_login()
    {
        $this->profile = $this->performLogin();

        if ($this->profile === ACCESS_DENIED) {
            $this->set('valid', false);
            return;
        }

        $this->set('returnUrl', \XLite\Core\Request::getInstance()->returnUrl);

        if (!$this->get('returnUrl')) {
            $url = $this->getComplex('xlite.script');
            if (!$this->getCart()->isEmpty()) {
                $url .= "?target=cart";
            }

            $this->set('returnUrl', $url);
        }

        $this->getCart()->setProfileId($this->profile->get('profile_id'));

        $this->updateCart();
    }

    function getShopUrl($url, $secure = false, $pure_url = false)
    {
        $add = (strpos($url, '?') ? '&' : '?') . 'feed='.$this->get('action');
        return parent::getShopUrl($url . $add, $secure);
    }

    function action_logoff()
    {
        $this->auth->logoff();
        $this->returnUrl = $this->getComplex('xlite.script');
        if (!$this->getCart()->isEmpty()) {
        	if ($this->config->Security->logoff_clear_cart == 'Y') {

                \XLite\Core\Database::getEM()->remove($this->getCart());
                \XLite\Core\Database::getEM()->flush();

        	} else {
                $this->updateCart();
        	}
        }
    }

    function getSecure()
    {
        if ($this->get('action') == "login") {
            return $this->config->Security->customer_security;
        }
        return false;
    }
}
