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

namespace XLite\Module\DrupalConnector\Controller\Customer;

/**
 * Checkout controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Checkout extends \XLite\Controller\Customer\Checkout implements \XLite\Base\IDecorator
{
    /**
     * Update profile 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function updateProfile()
    {
        if (\XLite\Core\Request::getInstance()->create_profile && \XLite\Core\CMSConnector::isCMSStarted()) {
            if (!\XLite\Core\Request::getInstance()->username) {

                // Username is empty
                $this->valid = false;
                $label = $this->t('This user name is empty');
                \XLite\Core\Event::invalidElement('username', $label);

            } elseif (user_load(array('name' => \XLite\Core\Request::getInstance()->username))) {

                // Username is already exists
                $this->valid = false;
                $label = $this->t(
                    'This user name is used for an existing account. Enter another user name or sign in',
                    array('URL' => $this->getLoginURL())
                );
                \XLite\Core\Event::invalidElement('username', $label);
            }
        }

        parent::updateProfile();

        if ($this->valid && \XLite\Core\CMSConnector::isCMSStarted() && isset(\XLite\Core\Request::getInstance()->create_profile)) {

            // Save username is session (temporary, wait place order procedure)
            \XLite\Core\Session::getInstance()->order_username = \XLite\Core\Request::getInstance()->create_profile
                ? \XLite\Core\Request::getInstance()->username
                : false;
        }
    }

    /**
     * Get login URL
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLoginURL()
    {
        return \XLite\Core\CMSConnector::isCMSStarted() ? url('user') : parent::getLoginURL();
    }

    /**
     * Save anonymous profile 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveAnonymousProfile()
    {
        parent::saveAnonymousProfile();

        $pass = user_password();

        $data = array(
            'name'   => \XLite\Core\Session::getInstance()->order_username,
            'init'   => $this->getCart()->getOrigProfile()->getLogin(),
            'mail'   => $this->getCart()->getOrigProfile()->getLogin(),
            'roles'  => array(),
            'status' => variable_get('user_register', 1) == 1,
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
                $this->getCart()->getOrigProfile()->getProfileId(),
                $account->uid
            );

            /* Auto-login
            db_query("UPDATE {users} SET status = 1 WHERE uid = %d", array($account->uid));

            global $user;
            $user = $account;

            user_authenticate_finalize($formState);
            */
        }

        unset(\XLite\Core\Session::getInstance()->order_username);
    }

    /**
     * Get redirect mode - force redirect or not
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRedirectMode()
    {
        return true;
    }

}
