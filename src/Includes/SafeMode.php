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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace Includes;

/**
 * Safe Mode
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
abstract class SafeMode
{
    /**
     * Common params
     */

    const PARAM_SAFE_MODE  = 'safe_mode';
    const PARAM_ACCESS_KEY = 'access_key';
    const PARAM_SOFT_RESET = 'soft_reset';


    /**
     * Check request parameters
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isSafeModeRequested()
    {
        return static::checkAccessKey()
            && \Includes\Utils\ArrayManager::getIndex($_GET, static::PARAM_SAFE_MODE);
    }

    /**
     * Check request parameters
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isSafeModeStarted()
    {
        return \Includes\Utils\FileManager::isExists(static::getIndicatorFileName());
    }

    /**
     * Get Access Key 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAccessKey()
    {
        if (!\Includes\Utils\FileManager::isExists(static::getAccessKeyFileName())) {

            // Put access key file
            \Includes\Utils\FileManager::write(
                static::getAccessKeyFileName(),
                static::generateAccessKey()
            );

            // Send email notification
            \XLite\Core\Mailer::sendSafeModeAccessKeyNotification(
                \Includes\Utils\FileManager::read(static::getAccessKeyFileName())
            );

        }

        return \Includes\Utils\FileManager::read(static::getAccessKeyFileName());
    }

    /**
     * Get safe mode URL
     *
     * @param boolean $soft Soft reset flag
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getResetURL($soft = false)
    {
        $params = array(
            static::PARAM_SAFE_MODE => 1,
            static::PARAM_ACCESS_KEY => static::getAccessKey()
        );

        if (true === $soft) {
            $params += array(
                static::PARAM_SOFT_RESET => 1
            );
        }

        return \Includes\Utils\URLManager::getShopURL(
            \XLite\Core\Converter::buildURL(
                'main',
                '',
                $params,
                \XLite::ADMIN_SELF
            )
        );
    }

    /**
     * Clean up the safe mode indicator
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function cleanupIndicator()
    {
        \Includes\Utils\FileManager::delete(static::getIndicatorFileName());
    }

    /**
     * Initialization
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function initialize()
    {
        if (
            !\Includes\SafeMode::isSafeModeRequested()
            || \Includes\SafeMode::isSafeModeStarted()
        ) {
            return;
        }

        // Put safe mode indicator
        \Includes\Utils\FileManager::write(
            static::getIndicatorFileName(),
            static::getIndicatorFileContent()
        );

        // Clean cache indicators to force cache generation
        \Includes\Decorator\Utils\CacheManager::cleanupCacheIndicators();

        // Redirect to avoid loop
        \Includes\Utils\Operator::redirect('admin.php?target=main');
    }


    /**
     * Check Access Key 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function checkAccessKey()
    {
        return static::getAccessKey() === \Includes\Utils\ArrayManager::getIndex($_GET, static::PARAM_ACCESS_KEY);
    }

    /**
     * Get safe mode indicator file name 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getIndicatorFileName()
    {
        return LC_VAR_DIR . '.safeModeStarted';
    }

    /**
     * Get safe mode access key file name 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getAccessKeyFileName()
    {
        return LC_DATA_DIR . '.safeModeAccessKey';
    }

    /**
     * Generate Access Key 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function generateAccessKey()
    {
        return substr(md5(uniqid(rand(), true)), 1, 6);
    }

    /**
     * Data to write into the indicator file
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getIndicatorFileContent()
    {
        return date('r');
    }

}
