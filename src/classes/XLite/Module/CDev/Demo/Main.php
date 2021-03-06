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

namespace XLite\Module\CDev\Demo;

/**
 * Demo module
 *
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @return string
     */
    public static function getAuthorName()
    {
        return 'Creative Development LLC';
    }

    /**
     * Module name
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'Demo mode';
    }

    /**
     * Get module major version
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
        return 'Demo mode';
    }

    /**
     * Forbid action processing
     *
     * @param string $message Action message OPTIONAL
     * @param string $url     Redirect URL OPTIONAL
     *
     * @return void
     */
    public static function doForbidAction($message = null, $url = null)
    {
        self::doForbidOperation($message);

        \Includes\Utils\Operator::redirect(
            $url ?: \XLite\Core\Converter::buildURL(\XLite\Core\Request::getInstance()->target)
        );
    }

    /**
     * Forbid operation processing
     *
     * @param string $message Message OPTIONAL
     *
     * @return void
     */
    public static function doForbidOperation($message = null)
    {
        \XLite\Core\TopMessage::addWarning($message ?: 'You cannot do this in demo mode.');
    }
}
