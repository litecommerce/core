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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Login
 * FIXME: must be completely refactored
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Login extends \XLite\Controller\Admin\AAdmin
{
    /**
     * handleRequest 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function handleRequest()
    {
        if (
            \XLite\Core\Auth::getInstance()->isLogged()
            && 'logoff' !== \XLite\Core\Request::getInstance()->{static::PARAM_ACTION}
        ) {
            
            if (!\XLite\Core\Auth::getInstance()->isAdmin()) {
                \XLite\Core\Auth::getInstance()->logoff();
            }

            $this->setReturnURL($this->buildURL());
        }

        return parent::handleRequest();
    }

    /**
     * getAccessLevel 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAccessLevel()
    {
        return \XLite\Core\Auth::getInstance()->getCustomerAccessLevel();
    }

    /**
     * init
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function init()
    {
        parent::init();
        
        if (empty(\XLite\Core\Request::getInstance()->login)) {
            \XLite\Core\Request::getInstance()->login = $this->auth->remindLogin();
        }
    }

    /**
     * Login 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionLogin()
    {
        $profile = $this->auth->loginAdministrator(
            \XLite\Core\Request::getInstance()->login,
            \XLite\Core\Request::getInstance()->password
        );

        if (is_int($profile) && \XLite\Core\Auth::RESULT_ACCESS_DENIED === $profile) {

            $this->set('valid', false);
            \XLite\Core\TopMessage::addError('Invalid login or password');
            $returnUrl = $this->buildUrl('login');

        } elseif (isset($this->session->lastWorkingURL)) {
            $returnURL = $this->xlite->session->get('lastWorkingURL');
            $this->xlite->session->set('lastWorkingURL', null);

        } else {
            $returnURL = $this->buildURL();
        }

        $this->setReturnURL($returnURL);
    }

    /**
     * Logoff 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionLogoff()
    {
        $this->auth->logoff();
    }

    /**
     * Perform some actions before redirect
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function actionPostprocessLogin()
    {
        $this->updateMarketplaceDataCache();
    }
}
