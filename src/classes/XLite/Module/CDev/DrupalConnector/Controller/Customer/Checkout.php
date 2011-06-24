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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Controller\Customer;

/**
 * Checkout controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Checkout extends \XLite\Controller\Customer\Checkout implements \XLite\Base\IDecorator
{
    /**
     * Get login URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getLoginURL()
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            ? url('user')
            : parent::getLoginURL();
    }


    /**
     * isCreateProfile
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isCreateProfile()
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            && !empty(\XLite\Core\Request::getInstance()->create_profile);
    }

    /**
     * Update profile
     * FIXME
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function updateProfile()
    {
        if ($this->isCreateProfile()) {

            $error = user_validate_name(\XLite\Core\Request::getInstance()->username);

            if ($error) {

                // Username validation error
                $this->valid = false;
                \XLite\Core\Event::invalidElement('username', $error);

            } elseif (user_load_by_name(\XLite\Core\Request::getInstance()->username)) {

                // Username is already exists
                $this->valid = false;
                $label = static::t(
                    'This user name is used for an existing account. Enter another user name or sign in',
                    array('URL' => $this->getLoginURL())
                );
                \XLite\Core\Event::invalidElement('username', $label);

            } elseif (
                \XLite\Core\Request::getInstance()->email
                && user_load_multiple(array(), array('mail' => \XLite\Core\Request::getInstance()->email))
            ) {

                // E-mail is already exists in Drupal DB
                $this->valid = false;
                $label = static::t(
                    'This email address is used for an existing account. Enter another user name or sign in',
                    array('URL' => $this->getLoginURL())
                );
                \XLite\Core\Event::invalidElement('email', $label);
            }
        }

        parent::updateProfile();

        if ($this->isCreateProfile() && $this->valid) {

            // Save username is session (temporary, wait place order procedure)
            \XLite\Core\Session::getInstance()->order_username = \XLite\Core\Request::getInstance()->create_profile
                ? \XLite\Core\Request::getInstance()->username
                : false;
        }
    }

    /**
     * Save anonymous profile
     * FIXME
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function saveAnonymousProfile()
    {
        parent::saveAnonymousProfile();

        $pass = user_password();

        $status = variable_get('user_register', USER_REGISTER_VISITORS_ADMINISTRATIVE_APPROVAL) 
            == USER_REGISTER_VISITORS;

        $data = array(
            'name'   => \XLite\Core\Session::getInstance()->order_username,
            'init'   => $this->getCart()->getOrigProfile()->getLogin(),
            'mail'   => $this->getCart()->getOrigProfile()->getLogin(),
            'roles'  => array(),
            'status' => $status,
            'pass'   => $pass,
        );

        $account = user_save('', $data);

        if ($account) {

            $account->password = $pass;
            if ($account->status) {
                _user_mail_notify('register_no_approval_required', $account);

            } else {
                _user_mail_notify('register_pending_approval', $account);
            }

            $this->getCart()->getProfile()->setCmsName('');
            $this->getCart()->getProfile()->setCmsProfileId(0);
            $this->getCart()->getOrigProfile()->setPassword(md5($pass));

            \XLite\Core\Database::getRepo('XLite\Model\Profile')->linkProfiles(
                $this->getCart()->getOrigProfile(),
                $account->uid
            );

        }

        unset(\XLite\Core\Session::getInstance()->order_username);
    }

    /**
     * Login anonymous profile
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function loginAnonymousProfile()
    {
        $account = $this->getCart()->getOrigProfile()->getCMSProfile();

        if ($account && $account->status) {
            parent::loginAnonymousProfile();

            $GLOBALS['user'] = user_load($account->uid);
            user_login_finalize();
        }
    }

    /**
     * Send create profile notifications
     *
     * @param string $password Password
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function sendCreateProfileNotifications($password)
    {
    }

    /**
     * Clone profile and move profile to original profile
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function cloneProfile()
    {
        parent::cloneProfile();

        $this->getCart()->getProfile()->setCMSName('');
        $this->getCart()->getProfile()->setCMSProfileId(0);
    }

    /**
     * Get redirect mode - force redirect or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRedirectMode()
    {
        return true;
    }

}
