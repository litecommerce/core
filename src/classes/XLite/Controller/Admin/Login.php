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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller\Admin;

/**
 * Login
 * FIXME: must be completely refactored
 *
 */
class Login extends \XLite\Controller\Admin\AAdmin
{
    /**
     * handleRequest
     *
     * @return void
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
     */
    public function getAccessLevel()
    {
        return \XLite\Core\Auth::getInstance()->getCustomerAccessLevel();
    }

    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        if (empty(\XLite\Core\Request::getInstance()->login)) {
            \XLite\Core\Request::getInstance()->login = \XLite\Core\Auth::getInstance()->remindLogin();
        }
    }

    /**
     * Login
     *
     * @return void
     */
    protected function doActionLogin()
    {
        $profile = \XLite\Core\Auth::getInstance()->loginAdministrator(
            \XLite\Core\Request::getInstance()->login,
            \XLite\Core\Request::getInstance()->password
        );

        if (
            is_int($profile)
            && \XLite\Core\Auth::RESULT_ACCESS_DENIED === $profile
        ) {
            $this->set('valid', false);

            \XLite\Core\TopMessage::addError('Invalid login or password');

            $returnURL = $this->buildURL('login');

        } elseif (isset(\XLite\Core\Session::getInstance()->lastWorkingURL)) {

            $returnURL = \XLite\Core\Session::getInstance()->get('lastWorkingURL');

            \XLite\Core\Session::getInstance()->set('lastWorkingURL', null);

        } else {

            $returnURL = $this->buildURL();
        }

        $this->setReturnURL($returnURL);
    }

    /**
     * Logoff
     *
     * @return void
     */
    protected function doActionLogoff()
    {
        \XLite\Core\Auth::getInstance()->logoff();
    }
}
