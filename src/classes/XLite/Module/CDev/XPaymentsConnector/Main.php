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

namespace XLite\Module\CDev\XPaymentsConnector;

/**
 * X-Payments connector module
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAuthorName()
    {
        return 'Creative Development LLC';
    }

    /**
     * Get module major version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getMajorVersion()
    {
        return '1.1';
    }

    /**
     * Module version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getMinorVersion()
    {
        return '0';
    }

    /**
     * Module name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getModuleName()
    {
        return 'X-Payments connector';
    }

    /**
     * Module description
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getDescription()
    {
        return 'X-Payments connector';
    }

    /**
     * Determines if we need to show settings form link
     *
     * @return boolean 
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function showSettingsForm()
    {
        return true;
    }

}
