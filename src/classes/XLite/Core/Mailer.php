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

namespace XLite\Core;

/**
 * Mailer core class
 *
 */
class Mailer extends \XLite\Base\Singleton
{
    /**
     * Mailer instance
     *
     * @var \XLite\View\Mailer
     */
    protected static $mailer = null;


    /**
     * Interface to use in mail
     *
     * @var string
     */
    protected static $mailInterface = \XLite::CUSTOMER_INTERFACE;

    /**
     * Send notification about created profile to the user
     *
     * @param \XLite\Model\Profile $profile  Profile object
     * @param string               $password Profile password
     *
     * @return void
     */
    public static function sendProfileCreatedUserNotification(\XLite\Model\Profile $profile, $password = null)
    {
        // Register variables
        static::register('profile', $profile);
        static::register('password', $password);

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
     */
    public static function sendFailedAdminLoginNotification($postedLogin)
    {
        static::register(
            array(
                'login'                 => isset($postedLogin) ? $postedLogin : 'unknown',
                'REMOTE_ADDR'           => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown',
                'HTTP_X_FORWARDED_FOR'  => isset($_SERVER['HTTP_X_FORWARDED_FOR'])
                    ? $_SERVER['HTTP_X_FORWARDED_FOR']
                    : 'unknown',
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
     */
    public static function sendOrderCreated(\XLite\Model\Order $order)
    {
        static::register(
            array(
                'order' => $order,
            )
        );

        if (\XLite\Core\Config::getInstance()->Email->enable_init_order_notif_customer) {
            static::sendOrderCreatedCustomer($order);
        }

        if (\XLite\Core\Config::getInstance()->Email->enable_init_order_notif) {
            static::sendOrderCreatedAdmin($order);
        }
    }

    /**
     * Send created order mail to customer
     *
     * @param \XLite\Model\Order $order Order model
     *
     * @return void
     */
    public static function sendOrderCreatedCustomer(\XLite\Model\Order $order)
    {
        static::setMailInterface(\XLite::CUSTOMER_INTERFACE);

        static::compose(
            \XLite\Core\Config::getInstance()->Company->orders_department,
            $order->getProfile()->getLogin(),
            'order_created',
            array(),
            true,
            \XLite::MAIL_INTERFACE
        );

        \XLite\Core\OrderHistory::getInstance()->registerCustomerEmailSent($order->getOrderId());
    }

    /**
     * Send created order mail to admin
     *
     * @param \XLite\Model\Order $order Order model
     *
     * @return void
     */
    public static function sendOrderCreatedAdmin(\XLite\Model\Order $order)
    {
        static::setMailInterface(\XLite::ADMIN_INTERFACE);

        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->orders_department,
            'order_created_admin',
            array(),
            true,
            \XLite::MAIL_INTERFACE
        );

        \XLite\Core\OrderHistory::getInstance()->registerAdminEmailSent($order->getOrderId());
    }

    /**
     * Send processed order mails
     *
     * @param \XLite\Model\Order $order ____param_comment____
     *
     * @return void
     */
    public static function sendProcessOrder(\XLite\Model\Order $order)
    {
        static::register(
            array(
                'order' => $order,
            )
        );

        static::sendProcessOrderAdmin($order);

        static::sendProcessOrderCustomer($order);
    }

    /**
     * Send processed order mail to Admin
     *
     * @param \XLite\Model\Order $order Order model
     *
     * @return void
     */
    public static function sendProcessOrderAdmin(\XLite\Model\Order $order)
    {
        static::setMailInterface(\XLite::ADMIN_INTERFACE);

        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->orders_department,
            'order_processed',
            array(),
            true,
            \XLite::MAIL_INTERFACE
        );

        \XLite\Core\OrderHistory::getInstance()->registerAdminEmailSent($order->getOrderId());
    }

    /**
     * Send processed order mail to Customer
     *
     * @param \XLite\Model\Order $order Order model
     *
     * @return void
     */
    public static function sendProcessOrderCustomer(\XLite\Model\Order $order)
    {
        static::setMailInterface(\XLite::CUSTOMER_INTERFACE);

        if ($order->getProfile()) {
            static::compose(
                \XLite\Core\Config::getInstance()->Company->site_administrator,
                $order->getProfile()->getLogin(),
                'order_processed',
                array(),
                true,
                \XLite::MAIL_INTERFACE
            );

            \XLite\Core\OrderHistory::getInstance()->registerCustomerEmailSent($order->getOrderId());
        }
    }

    /**
     * Send failed order mails
     *
     * @param \XLite\Model\Order $order Order model
     *
     * @return void
     */
    public static function sendFailedOrder(\XLite\Model\Order $order)
    {
        static::register(
            array(
                'order' => $order,
            )
        );

        static::sendFailedOrderAdmin($order);

        static::sendFailedOrderCustomer($order);
    }

    /**
     * Send failed order mail to Admin
     *
     * @param \XLite\Model\Order $order Order model
     *
     * @return void
     */
    public static function sendFailedOrderAdmin(\XLite\Model\Order $order)
    {
        static::setMailInterface(\XLite::ADMIN_INTERFACE);

        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            \XLite\Core\Config::getInstance()->Company->orders_department,
            'order_failed',
            array(),
            true,
            \XLite::MAIL_INTERFACE
        );

        \XLite\Core\OrderHistory::getInstance()->registerAdminEmailSent($order->getOrderId());
    }

    /**
     * Send failed order mail to Customer
     *
     * @param \XLite\Model\Order $order Order model
     *
     * @return void
     */
    public static function sendFailedOrderCustomer(\XLite\Model\Order $order)
    {
        static::setMailInterface(\XLite::CUSTOMER_INTERFACE);

        if ($order->getProfile()) {
            static::compose(
                \XLite\Core\Config::getInstance()->Company->orders_department,
                $order->getProfile()->getLogin(),
                'order_failed',
                array(),
                true,
                \XLite::MAIL_INTERFACE
            );

            \XLite\Core\OrderHistory::getInstance()->registerCustomerEmailSent($order->getOrderId());
        }
    }

    /**
     * Send notification about generated safe mode access key
     *
     * @param string $key Access key
     *
     * @return void
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
     * Send test email
     *
     * @param string $from Email address to send test email from
     * @param string $to   Email address to send test email to
     * @param string $body Body test email text
     *
     * @return string
     */
    public static function sendTestEmail($from, $to, $body = '')
    {
        static::register(
            array(
                'body' => $body,
            )
        );

        static::setMailInterface(\XLite::ADMIN_INTERFACE);

        static::compose(
            $from,
            $to,
            'test_email'
        );

        return static::getMailer()->getLastError();
    }

    /**
     * Set mail interface
     *
     * @param string $interface Interface to use in mail OPTIONAL
     *
     * @return void
     */
    protected static function setMailInterface($interface = \XLite::CUSTOMER_INTERFACE)
    {
        static::$mailInterface = $interface;
    }


    /**
     * Returns mailer instance
     *
     * @return \XLite\View\Mailer
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
     * @param mixed  $value Variable value OPTIONAL
     *
     * @return void
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
     * @param string  $from          Email FROM
     * @param string  $to            Email TO
     * @param string  $dir           Directory where mail templates are located
     * @param array   $customHeaders Array of custom mail headers OPTIONAL
     * @param boolean $doSend        Flag: if true - send email immediately OPTIONAL
     * @param string  $mailInterface Intarface to compile mail templates (skin name: customer, admin or mail) OPTIONAL
     *
     * @return void
     */
    protected static function compose($from, $to, $dir, $customHeaders = array(), $doSend = true, $mailInterface = \XLite::CUSTOMER_INTERFACE)
    {
        static::getMailer()->compose($from, $to, $dir, $customHeaders, $mailInterface);

        if ($doSend) {

            static::getMailer()->send();

            static::setMailInterface();
        }
    }
}
