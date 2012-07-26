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
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\SocialLogin\View;

/**
 * Google sign-in button
 *
 * @ListChild (list="social.login.buttons", zone="customer", weight="10")
 */
class GoogleButton extends Button
{
    /**
     * Widget display name
     */
    const DISPLAY_NAME = 'Google';

    /**
     * Widget icon path
     */
    const ICON_PATH = 'modules/CDev/SocialLogin/icons/google.png';

    /**
     * Returns an instance of auth provider
     *
     * @return \XLite\Module\CDev\SocialLogin\Core\AAuthProvider
     */
    protected function getAuthProvider()
    {
        return \XLite\Module\CDev\SocialLogin\Core\GoogleAuthProvider::getInstance();
    }
}
