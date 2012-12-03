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

namespace XLite\Module\CDev\Paypal;

/**
 * Paypal module
 *
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Paypal methods service names
     */
    const PP_METHOD_PPA = 'PaypalAdvanced';
    const PP_METHOD_PFL = 'PayflowLink';
    const PP_METHOD_EC  = 'ExpressCheckout';
    const PP_METHOD_PPS = 'PaypalWPSUS';


    /**
     * Author name
     *
     * @return string
     */
    public static function getAuthorName()
    {
        return 'X-Cart team';
    }

    /**
     * Module name
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'PayPal US';
    }

    /**
     * Module major version
     *
     * @return string
     */
    public static function getMajorVersion()
    {
        return '1.1';
    }


    /**
     * Module version
     *
     * @return string
     */
    public static function getMinorVersion()
    {
        return '1';
    }

    /**
     * Module description
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'Enables taking payments for your online store via PayPal services (for US merchants).';
    }

    /**
     * Determines if we need to show settings form link
     *
     * @return boolean
     */
    public static function showSettingsForm()
    {
        return false;
    }

    /**
     * Add record to the module log file
     *
     * @param string $message Text message OPTIONAL
     * @param mixed  $data    Data (can be any type) OPTIONAL
     *
     * @return void
     */
    public static function addLog($message = null, $data = null)
    {
        if ($message && $data) {
            $msg = array(
                'message' => $message,
                'data'    => $data,
            );

        } else {
            $msg = ($message ?: ($data ?: null));
        }

        if (!is_string($msg)) {
            $msg = var_export($msg, true);
        }

        \XLite\Logger::getInstance()->logCustom(
            self::getModuleName(),
            $msg
        );
    }

    /**
     * Returns true if ExpressCheckout payment is enabled 
     *
     * @param \XLite\Model\Cart $order Cart object OPTIONAL
     *
     * @return boolean
     */
    public static function isExpressCheckoutEnabled($order = null)
    {
        static $result;

        $index = isset($order) ? 1 : 0;

        if (!isset($result[$index])) {
            $paymentMethod = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
                ->findOneBy(
                    array(
                        'service_name' => 'ExpressCheckout',
                        'enabled'      => true
                    )
                );
            $result[$index] = !empty($paymentMethod) && $paymentMethod->isEnabled();

            if ($order && $result[$index]) {
                $result[$index] = $paymentMethod->getProcessor()->isApplicable($order, $paymentMethod);
            }
        }

        return $result[$index];
    }

    /**
     * Return list of mutually exclusive modules
     *
     * @return array
     */
    public static function getMutualModulesList()
    {
        $list = parent::getMutualModulesList();
        $list[] = 'CDev\PaypalWPS';

        return $list;
    }
}
