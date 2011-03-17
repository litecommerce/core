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
     * Request params
     */

    const PARAM_SAFE_MODE  = 'safe_mode';
    const PARAM_ACCESS_KEY = 'access_key';
    const PARAM_SOFT_RESET = 'soft_reset';

    /**
     * Soft reset label 
     */
    const LABEL_SOFT_RESET = 'Soft reset';

    /**
     * Modules list file name
     */
    const UNSAFE_MODULES_FILE_NAME = '.decorator.unsafe_modules.ini.php';


    /**
     * Unsafe modules list file name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $unsafeModulesINIFile;


    /**
     * Check request parameters
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isSafeModeRequested()
    {
        return static::checkAccessKey()
            && \Includes\Utils\ArrayManager::getIndex($_GET, static::PARAM_SAFE_MODE);
    }

    /**
     * Check if the safe mode requested in the "Soft reset" variant
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isSoftResetRequested()
    {
        return strpos(\Includes\Utils\FileManager::read(static::getIndicatorFileName()), static::LABEL_SOFT_RESET) > 0;
    }

    /**
     * Check request parameters
     * 
     * @return void
     * @access public
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAccessKey()
    {
        if (!\Includes\Utils\FileManager::isExists(static::getAccessKeyFileName())) {
            static::regenerateAccessKey();
        }

        return \Includes\Utils\FileManager::read(static::getAccessKeyFileName());
    }

    /**
     * Re-generate access key
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function regenerateAccessKey()
    {
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

    /**
     * Get safe mode URL
     *
     * @param boolean $soft Soft reset flag
     * 
     * @return string
     * @access public
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
     * @access public
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
     * @access public
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
     * @access protected
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
     * @access protected
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
     * @access protected
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function generateAccessKey()
    {
        return uniqid();
    }

    /**
     * Data to write into the indicator file
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getIndicatorFileContent()
    {
        $softResetMark = \Includes\Utils\ArrayManager::getIndex($_GET, static::PARAM_SOFT_RESET)
            ? ', ' . static::LABEL_SOFT_RESET
            : '';
            
        return date('r') . $softResetMark;
    }

    // ------------------------------ Unsafe modules -

    /**
     * Remove file with active modules list
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function clearUnsafeModules()
    {
        \Includes\Utils\FileManager::delete(static::getUnsafeModulesFilePath());
    }

    /**
     * Save modules to file 
     * 
     * @param array $modules Modules array
     *  
     * @return integer|boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function saveUnsafeModulesToFile(array $modules)
    {
        $path = static::getUnsafeModulesFilePath(); 

        $string = '; <' . '?php /*' . PHP_EOL;

        $i = 0;
        foreach ($modules as $author => $names) {
            $string .= '[' . $author. ']' . PHP_EOL;
            foreach ($names as $name => $enabled) {
                $string .= $name . ' = ' . $enabled . PHP_EOL;
                $i++;
            }
        }

        $string .= '; */ ?' . '>';

        return $i ? file_put_contents($path, $string) : false;
    }

    /**
     * Get modules list file path 
     * 
     * @return string|void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getUnsafeModulesFilePath()
    {
        if (!isset(static::$unsafeModulesINIFile)) {
            static::$unsafeModulesINIFile = LC_VAR_DIR . static::UNSAFE_MODULES_FILE_NAME;
        }

        return static::$unsafeModulesINIFile;
    }

    /**
     * Get Unsafe Modules List 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     */
    public static function getUnsafeModulesList()
    {
        $list = array();
        $path = static::getUnsafeModulesFilePath();

        if (\Includes\Utils\FileManager::isFileReadable($path)) {
            $list = parse_ini_file($path, true);
        }

        return $list;
    }

    /**
     * Mark module as unsafe
     * 
     * @param string $author Module author 
     * @param string $name   Module name 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     */
    public static function markModuleAsUnsafe($author, $name)
    {
        $list = static::getUnsafeModulesList();

        if (!\Includes\Utils\ArrayManager::getIndex($list, $author)) {
            $list[$author] = array();
        }

        $list[$author] += array(
            $name => 1
        );

        static::saveUnsafeModulesToFile($list);
    }

    /**
     * Mark modules as unsafe
     * 
     * @param array $modules Modules 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     */
    public static function markModulesAsUnsafe(array $modules)
    {
        $list = static::getUnsafeModulesList();

        foreach ($modules as $author => $names) {

            foreach ($names as $name => $key) {

                if (!\Includes\Utils\ArrayManager::getIndex($list, $author)) {
                    $list[$author] = array();
                }

                $list[$author] += array(
                    $name => 1
                );

            }
        }

        static::saveUnsafeModulesToFile($list);
    }

    /**
     * SQL string condition for unsafe modules
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     */
    public static function getUnsafeModulesSQLConditionString()
    {
        $cnd = '';

        if ($unsafeModules = static::getUnsafeModulesList()) {

            foreach ($unsafeModules as $author => $names) {
                $disableCondition[] = 'author = \'' . $author 
                    . '\' AND name IN (\'' . implode('\',\'', array_keys($names)) . '\')';
            }

            if ($disableCondition) {
                $cnd = '(' . implode(') OR (', $disableCondition) . ')';
            }

        }

        return $cnd;
    }

}
