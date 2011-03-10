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
 * @subpackage Includes
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes;

/**
 * Safe Mode
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class SafeMode
{
    /**
     * Common params
     */

    const PARAM_SAFE_MODE  = 'safe_mode';
    const PARAM_ACCESS_KEY = 'access_key';


    /**
     * Check request parameters
     * 
     * @return void
     * @see    ____func_see____
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
     */
    public static function isSafeModeStarted()
    {
        return \Includes\Utils\FileManager::isExists(static::getIndicatorFileName());
    }

    /**
     * Check Access Key 
     * 
     * @return boolean
     * @see    ____func_see____
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
     */
    protected static function generateAccessKey()
    {
        return md5(uniqid(rand(), true));
    }

    /**
     * Get Access Key 
     * 
     * @return string
     * @see    ____func_see____
     */
    protected static function getAccessKey()
    {
        if (!\Includes\Utils\FileManager::isExists(static::getAccessKeyFileName())) {

            // Put access key file
            \Includes\Utils\FileManager::write(
                static::getAccessKeyFileName(),
                static::generateAccessKey()
            );
        }

        return \Includes\Utils\FileManager::read(static::getAccessKeyFileName());
    }

    /**
     * Data to write into the indicator file
     * 
     * @return string
     * @see    ____func_see____
     */
    protected static function getIndicatorFileContent()
    {
        return date('r');
    }

    /**
     * Clean up the safe mode indicator
     *
     * @return void
     * @see    ____func_see____
     */
    public static function cleanupIndicator()
    {
        \Includes\Utils\FileManager::delete(static::getIndicatorFileName());
    }

    /**
     * Prepare
     * 
     * @return void
     * @see    ____func_see____
     */
    public static function prepare()
    {
        // Safe mode indicator
        \Includes\Utils\FileManager::write(
            static::getIndicatorFileName(),
            static::getIndicatorFileContent()
        );
        
        \XLite::setCleanUpCacheFlag(true);
        \Includes\Utils\Operator::redirect('admin.php?target=main');
    }
}
