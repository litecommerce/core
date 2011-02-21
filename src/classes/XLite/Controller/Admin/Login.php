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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Login
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Login extends \XLite\Controller\Admin\AAdmin
{
    /**
     * handleRequest 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     */
    public function handleRequest()
    {
        if (
            \XLite\Core\Auth::getInstance()->isLogged()
            && 'logoff' !== \XLite\Core\Request::getInstance()->{static::PARAM_ACTION}
        ) {
            $this->setReturnURL($this->buildURL());
        }

        return parent::handleRequest();
    }

    /**
     * getAccessLevel 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAccessLevel()
    {
        return \XLite\Core\Auth::getInstance()->getCustomerAccessLevel();
    }

    /**
     * fillForm 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function fillForm()
    {
        parent::fillForm();
        
        $login = $this->get('login');
        
        if (empty($login)) {
            $this->set('login', $this->auth->remindLogin());
        }
    }

    /**
     * Login 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionLogin()
    {
        $profile = $this->auth->loginAdministrator(
            \XLite\Core\Request::getInstance()->login,
            \XLite\Core\Request::getInstance()->password
        );

        if (is_int($profile) && \XLite\Core\Auth::RESULT_ACCESS_DENIED === $profile) {

            $this->set('valid', false);
            \XLite\Core\TopMessage::getInstance()->add('Invalid login or password', \XLite\Core\TopMessage::ERROR);
            $returnURL = $this->buildURL('login');

        } elseif (isset($this->session->lastWorkingURL)) {
            $returnURL = $this->xlite->session->get('lastWorkingURL');
            $this->xlite->session->set('lastWorkingURL', null);

        } else {
            $returnURL = $this->buildURL();
        }

        $this->setReturnURL($returnURL);
    }

    function action_logoff()
    {
        $this->auth->logoff();
    }

    function getSecure()
    {
        return $this->session->get('no_https') ? false : $this->config->Security->admin_security;
    }
}
