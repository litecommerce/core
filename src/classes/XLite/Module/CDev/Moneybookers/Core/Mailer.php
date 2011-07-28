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

namespace XLite\Module\CDev\Moneybookers\Core;

/**
 * Mailer 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Mailer extends \XLite\Core\Mailer implements \XLite\Base\IDecorator
{
    /**
     * Send Moneybookers activation message
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function sendMoneybookersActivation()
    {
        // Register variables
        static::register(
            'platform_name',
            \XLite\Module\CDev\Moneybookers\Model\Payment\Processor\Moneybookers::getPlatformName()
        );
        $address = \XLite\Core\Auth::getInstance()->getProfile()->getBillingAddress();
        if ($address) {
            static::register('first_name', $address->getFirstName());
            static::register('last_name', $address->getLastName());

        } else {
            static::register('first_name', '');
            static::register('last_name', '');

        }
        static::register('email', \XLite\Core\Config::getInstance()->CDev->Moneybookers->email);
        static::register('id', \XLite\Core\Config::getInstance()->CDev->Moneybookers->id);
        static::register('url', \XLite::getInstance()->getShopURL());
        static::register('language', \XLite\Core\Session::getInstance()->getLanguage()->getCode());

        // Compose and send email
        static::compose(
            \XLite\Core\Config::getInstance()->Company->site_administrator,
            'ecommerce@moneybookers.com',
            'modules/CDev/Moneybookers/activation'
        );
    }

}

