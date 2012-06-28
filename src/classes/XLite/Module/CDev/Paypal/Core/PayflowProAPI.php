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

namespace XLite\Module\CDev\Paypal\Core;

/**
 * Paypal Payflow Pro API (name-value pairs) implementation
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class PayflowProAPI extends \XLite\Base\Singleton
{
    public function preprocessRequestData($data)
    {
    }

    public function sendRequest($data)
    {
    }

    public function parseResponse($response)
    {
    }

    public function getRequestHeaders()
    {
    }

    public function getRequestTimeout()
    {
        return 45; // Default value recommended by Paypal; Max value - 120 seconds
    }

    public function getCommonRequestParams($trxType)
    {
        return array(
            // Merchant login ID that was created when the merchant registered for their PayPal Payments Advanced or Payflow Link account
            'VENDOR' => \XLite\Core\Config::getInstance()->CDev->Paypal->vendor,

            // Username of the user that is authorized to run transactions
            'USER'   => (\XLite\Core\Config::getInstance()->CDev->Paypal->user ?: \XLite\Core\Config::getInstance()->CDev->Paypal->vendor),

            // The ID provided to the merchant by the authorized PayPal Reseller who registered them for their PayPal Payments Advanced or Payflow Link account
            'PARTNER' => 'Paypal',

            // The password that the merchant created for the username specified in the USER field
            'PWD' => \XLite\Core\Config::getInstance()->CDev->Paypal->pwd,

            // Transaction type:
            //   S (sale)
            //   A (authorization)
            //   D (delayed capture)
            //   V (void)
            //   C (credit)
            'TRXTYPE' => $trxType,

            'BUTTONSOURCE' => 'Qualiteam_Cart_LC_PHS',
        );
    }

    /*
    public function method()
    {
    }

    public function method()
    {
    }
     */
}
