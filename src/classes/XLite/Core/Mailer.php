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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
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
     * Interface to use in mail
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $mailInterface = \XLite::CUSTOMER_INTERFACE;

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
        // Register variables
        static::register('profile', $profile);

        // Compose and send email
        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            $profile->getLogin(),
            'signin_notification'
        );
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
        static::register('profile', $profile);

        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->users_department,
            'signin_admin_notification'
        );
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
        static::register('profile', $profile);

        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            $profile->getLogin(),
            'profile_modified'
        );
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
        static::register('profile', $profile);

        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->users_department,
            'profile_admin_modified'
        );
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
        static::register('userLogin', $userLogin);

        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->users_department,
            'profile_admin_deleted'
        );
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
        static::register(
            array(
                'login'                 => isset($postedLogin) ? $postedLogin : 'unknown',
                'REMOTE_ADDR'           => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown',
                'HTTP_X_FORWARDED_FOR'  => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : 'unknown',
            )
        );

        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            'login_error'
        );
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
        static::register(
            'url', 
            \XLite::getInstance()->getShopURL(
                'cart.php?target=recover_password&action=confirm&email=' . 
                urlencode($userLogin) . 
                '&request_id=' . 
                $userPassword
            )
        );
        
        static::compose(
            \XLite\Core\Config::getInstance()->Company->users_department,
            $userLogin,
            'recover_request'
        );
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
        static::register(
            array(
                'email'         => $userLogin,
                'new_password'  => $userPassword,
            )
        );

        static::compose(
            \XLite\Core\Config::getInstance()->Company->users_department,
            $userLogin,
            'recover_recover'
        );
    }

    /**
     * Send created order mails.
     * 
     * @param \XLite\Model\Order $order Order model
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendOrderCreated(\XLite\Model\Order $order)
    {
        static::register(
            array(
                'order' => $order,
            )
        );

        static::sendOrderCreatedCustomer($order->getProfile()->getLogin());

        static::sendOrderCreatedAdmin();
    }

    /**
     * Send created order mail to customer
     * 
     * @param string $login Customer email
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendOrderCreatedCustomer($login)
    {
        static::setMailInterface(\XLite::CUSTOMER_INTERFACE);

        static::compose(
            \XLite\Core\Config::getInstance()->Company->orders_department,
            $login,
            'order_created'
        );
    }

    /**
     * Send created order mail to admin
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendOrderCreatedAdmin()
    {
        if (\XLite\Core\Config::getInstance()->Email->enable_init_order_notif) {

            static::setMailInterface(\XLite::ADMIN_INTERFACE);

            static::compose(
                \XLite\Core\Config::getInstance()->Company->site_administrator,
                \XLite\Core\Config::getInstance()->Company->orders_department,
                'order_created'
            );
        }
    }

    /**
     * Send processed order mails
     * 
     * @param \XLite\Model\Order $order ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendProcessOrder(\XLite\Model\Order $order)
    {   
        static::register(
            array(
                'order' => $order,
            )
        );

        static::sendProcessOrderAdmin();

        static::sendProcessOrderCustomer($order);
    }

    /**
     * Send processed order mail to Admin
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendProcessOrderAdmin()
    {
        static::setMailInterface(\XLite::ADMIN_INTERFACE);

        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->orders_department,
            'order_processed'
        );
    }        

    /**
     * Send processed order mail to Customer
     * 
     * @param \XLite\Model\Order $order Order model
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendProcessOrderCustomer(\XLite\Model\Order $order)
    {
        if ($order->getProfile()) {
            static::compose(
                \XLite\Core\Config::getInstance()->Company->site_administrator,
                $order->getProfile()->getLogin(),
                'order_processed'
            );
        }
    }

    /**
     * Send failed order mails
     * 
     * @param \XLite\Model\Order $order Order model
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendFailedOrder(\XLite\Model\Order $order)
    {
        static::register(
            array(
                'order' => $order,
            )
        );

        static::sendFailedOrderAdmin();

        static::sendFailedOrderCustomer($order);
    }

    /**
     * Send failed order mail to Admin
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendFailedOrderAdmin()
    {
        static::setMailInterface(\XLite::ADMIN_INTERFACE);

        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->orders_department,
            'order_failed'
        );
    }

    /**
     * Send failed order mail to Customer
     * 
     * @param \XLite\Model\Order $order Order model
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendFailedOrderCustomer(\XLite\Model\Order $order)
    {
        static::setMailInterface(\XLite::CUSTOMER_INTERFACE);

        if ($order->getProfile()) {
            static::compose(
                \XLite\Core\Config::getInstance()->Company->orders_department,
                $order->getProfile()->getLogin(),
                'order_failed'
            );  
        }   
    }

    /**
     * Send notification about generated safe mode access key
     * 
     * @param string $key Access key
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sendSafeModeAccessKeyNotification($key)
    {
        static::setMailInterface(\XLite::ADMIN_INTERFACE);

        // Register variables
        static::register('key', $key);
        static::register('hard_reset_url', \Includes\SafeMode::getResetURL());
        static::register('soft_reset_url', \Includes\SafeMode::getResetURL(true));

        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            'safe_mode_key_generated'
        );
    }


    /**
     * Set mail interface
     * 
     * @param string $interface Interface to use in mail
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function setMailInterface($interface = \XLite::CUSTOMER_INTERFACE)
    {
        static::$mailInterface = $interface;
    }


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
        if (!isset(static::$mailer)) {

            static::$mailer = new \XLite\View\Mailer();
        }

        return static::$mailer;
    }

    /**
     * Register variable into mail viewer
     *
     * @param string $name  Variable name
     * @param mixed  $value Variable value
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function register($name, $value = '')
    {
        $variables = is_array($name) ? $name : array($name => $value);

        $mailer = static::getMailer();

        foreach ($variables as $name => $value) {

            $mailer->set($name, $value);
        }
    }

    /**
     * Compose and send wrapper for \XLite\View\Mailer::compose()
     * 
     * @param string  $from          ____param_comment____
     * @param string  $to            ____param_comment____
     * @param string  $dir           ____param_comment____
     * @param array   $customHeaders ____param_comment____
     * @param boolean $doSend        ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function compose($from, $to, $dir, $customHeaders = array(), $doSend = true)
    {
        static::getMailer()->compose($from, $to, $dir, $customHeaders, static::$mailInterface);

        if ($doSend) {

            static::getMailer()->send();

            static::setMailInterface();
        }
    }
}
