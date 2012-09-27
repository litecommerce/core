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

namespace XLite\Module\CDev\GoSocial\View;

/**
 * Header 
 * 
 */
abstract class Header extends \XLite\View\Header implements \XLite\Base\IDecorator
{
    /**
     * Get head prefixes
     *
     * @return array
     */
    public static function defineHeadPrefixes()
    {
        $list = parent::defineHeadPrefixes();

        $list['og'] = 'http://ogp.me/ns#';
        $list['fb'] = 'http://ogp.me/ns/fb#';

        if (\XLite\Core\Config::getInstance()->CDev->GoSocial->fb_app_namespace) {
            $ns = \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_app_namespace;
            $list[$ns] = 'http://ogp.me/ns/' . $ns . '#';
        }

        return $list;
    }
}

