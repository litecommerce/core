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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Core;

/**
 * Mailer core class
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Mailer extends \XLite\Base\Singleton
{
    /**
     * Mailer instance
     * 
     * @var    \XLite\View\Mailer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $mailer = null;

    /**
     * Returns mailer instance
     * 
     * @return \XLite\View\Mailer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getMailer()
    {
        if (!isset(self::$mailer)) {
            self::$mailer = new \XLite\View\Mailer();
        }

        return self::$mailer;
    }

    /**
     * Send notification about created profile to the user 
     * 
     * @param \XLite\Model\Profile $profile Profile object
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendProfileCreatedUserNotification(\XLite\Model\Profile $profile)
    {
        $mailer = self::getMailer();

        // Prepare mailer
        $mailer->profile = $profile;
        $mailer->set('charset', \XLite\Base::getInstance()->config->Company->locationCountry->charset);

        $mailer->compose(
            \XLite\Base::getInstance()->config->Company->site_administrator,
            $profile->getLogin(),
            'signin_notification'
        );
        
        $mailer->send();
    }

    /**
     * Send notification about created profile to the users department 
     * 
     * @param \XLite\Model\Profile $profile Profile object
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendProfileCreatedAdminNotification(\XLite\Model\Profile $profile)
    {
        $mailer = self::getMailer();

        // Prepare mailer
        $mailer->profile = $profile;
        $mailer->set('charset', \XLite\Base::getInstance()->config->Company->locationCountry->charset);

        $mailer->compose(
            \XLite\Base::getInstance()->config->Company->site_administrator,
            \XLite\Base::getInstance()->config->Company->users_department,
            'signin_admin_notification'
        );
        
        $mailer->send();
    }

    /**
     * Send notification about updated profile to the user 
     * 
     * @param \XLite\Model\Profile $profile Profile object
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendProfileUpdatedUserNotification(\XLite\Model\Profile $profile)
    {
        $mailer = self::getMailer();

        // Prepare mailer
        $mailer->profile = $profile;
        $mailer->set('charset', \XLite\Base::getInstance()->config->Company->locationCountry->charset);

        $mailer->compose(
            \XLite\Base::getInstance()->config->Company->site_administrator,
            $profile->getLogin(),
            'profile_modified'
        );
        
        $mailer->send();
    }

    /**
     * Send notification about updated profile to the users department 
     * 
     * @param \XLite\Model\Profile $profile Profile object
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendProfileUpdatedAdminNotification(\XLite\Model\Profile $profile)
    {
        $mailer = self::getMailer();

        // Prepare mailer
        $mailer->profile = $profile;
        $mailer->set('charset', \XLite\Base::getInstance()->config->Company->locationCountry->charset);

        $mailer->compose(
            \XLite\Base::getInstance()->config->Company->site_administrator,
            \XLite\Base::getInstance()->config->Company->users_department,
            'profile_admin_modified'
        );
        
        $mailer->send();
    }

    /**
     * Send notification about deleted profile to the users department 
     * 
     * @param string $userLogin Login of deleted profile
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendProfileDeletedAdminNotification($userLogin)
    {
        $mailer = self::getMailer();

        // Prepare mailer
        $mailer->userLogin = $userLogin;
        $mailer->set('charset', \XLite\Base::getInstance()->config->Company->locationCountry->charset);

        $mailer->compose(
            \XLite\Base::getInstance()->config->Company->site_administrator,
            \XLite\Base::getInstance()->config->Company->users_department,
            'profile_admin_deleted'
        );
        
        $mailer->send();
    }

    /**
     * Send notification to the site administrator email about failed administrator login attempt
     *
     * @param string $postedLogin Login that was used in failed login attempt
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendFailedAdminLoginNotification($postedLogin) 
    {
        $mailer = self::getMailer();

        $mailer->set('login', isset($postedLogin) ? $postedLogin : 'unknown');
        $mailer->set(
            'REMOTE_ADDR', 
            isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown'
        );
        $mailer->set(
            'HTTP_X_FORWARDED_FOR', 
            isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : 'unknown'
        );
        $mailer->set('charset', \XLite\Base::getInstance()->config->Company->locationCountry->charset);

        $mailer->compose(
            \XLite\Base::getInstance()->config->Company->site_administrator,
            \XLite\Base::getInstance()->config->Company->site_administrator,
            'login_error'
        );

        $mailer->send();
    }

    /**
     * Send recover password request to the user
     * 
     * @param string $userLogin    User email (login)
     * @param string $userPassword User password
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendRecoverPasswordRequest($userLogin, $userPassword)
    {
        $mailer = self::getMailer();
        
        $mailer->url = \XLite::getInstance()->getShopUrl(
            'cart.php?target=recover_password&action=confirm&email=' . 
            urlencode($userLogin) . 
            '&request_id=' . 
            $userPassword
        );
        
        $mailer->set('charset', \XLite\Base::getInstance()->config->Company->locationCountry->charset);
        
        $mailer->compose(
            \XLite\Base::getInstance()->config->Company->users_department,
            $userLogin,
            'recover_request'
        );

        $mailer->send();
    }

    /**
     * Send password recovery confirmation to the user
     * 
     * @param string $userLogin    User email (login)
     * @param string $userPassword User password (unencrypted)
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendRecoverPasswordConfirmation($userLogin, $userPassword)
    {
        $mailer = self::getMailer();
        
        $mailer->set('email', $userLogin);
        $mailer->set('new_password', $userPassword);
        $mailer->set('charset', \XLite\Base::getInstance()->config->Company->locationCountry->charset);
        $mailer->compose(
            $this->config->Company->users_department,
            $userLogin,
            'recover_recover'
        );

        $mailer->send();
    }

}
