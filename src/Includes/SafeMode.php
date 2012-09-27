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

namespace Includes;

/**
 * Safe Mode
 *
 * :TODO: reduce numder of public methods
 *
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
     * Return true if software reset is enabled in config file
     * 
     * @return boolean
     */
    public static function isSoftwareResetEnabled()
    {
        return !(bool) \Includes\Utils\ConfigParser::getOptions(array('decorator', 'disable_software_reset'));
    }

    /**
     * Check request parameters
     *
     * @return void
     */
    public static function isSafeModeRequested()
    {
        return static::checkAccessKey() && isset($_GET[self::PARAM_SAFE_MODE]);
    }

    /**
     * Check if the safe mode requested in the "Soft reset" variant
     *
     * @return boolean
     */
    public static function isSoftResetRequested()
    {
        return 0 < strpos(\Includes\Utils\FileManager::read(static::getIndicatorFileName()), self::LABEL_SOFT_RESET);
    }

    /**
     * Check request parameters
     *
     * @return void
     */
    public static function isSafeModeStarted()
    {
        return \Includes\Utils\FileManager::isFile(static::getIndicatorFileName());
    }

    /**
     * Get Access Key
     *
     * @return string
     */
    public static function getAccessKey()
    {
        if (!\Includes\Utils\FileManager::isFile(static::getAccessKeyFileName())) {
            static::regenerateAccessKey();
        }

        return \Includes\Utils\FileManager::read(static::getAccessKeyFileName());
    }

    /**
     * Re-generate access key
     *
     * @return void
     */
    public static function regenerateAccessKey()
    {
        // Put access key file
        \Includes\Utils\FileManager::write(static::getAccessKeyFileName(), static::generateAccessKey());

        // Send email notification
        static::sendNotification();
    }

    /**
     * Send email notification to administrator about access key
     *
     * @return void
     */
    protected static function sendNotification()
    {
        if (!\Includes\Decorator\Utils\CacheManager::isRebuildNeeded(\Includes\Decorator\Utils\CacheManager::STEP_THIRD)) {
            // Send email notification
            \XLite\Core\Mailer::sendSafeModeAccessKeyNotification(
                \Includes\Utils\FileManager::read(static::getAccessKeyFileName())
            );
        }
    }

    /**
     * Get safe mode URL
     *
     * @param boolean $soft Soft reset flag OPTIONAL
     *
     * @return string
     */
    public static function getResetURL($soft = false)
    {
        $params = array(
            self::PARAM_SAFE_MODE  => true,
            self::PARAM_ACCESS_KEY => static::getAccessKey()
        );

        if ($soft) {
            $params[self::PARAM_SOFT_RESET] = true;
        }

        return \Includes\Utils\URLManager::getShopURL(
            \XLite\Core\Converter::buildURL('main', '', $params, \XLite::ADMIN_SELF)
        );
    }

    /**
     * Clean up the safe mode indicator
     *
     * @return void
     */
    public static function cleanupIndicator()
    {
        \Includes\Utils\FileManager::deleteFile(static::getIndicatorFileName());
    }

    /**
     * Initialization
     *
     * @return void
     */
    public static function initialize()
    {
        if (static::isSafeModeRequested() && !static::isSafeModeStarted()) {

            if (static::isSoftwareResetEnabled()) {

                // Put safe mode indicator
                \Includes\Utils\FileManager::write(static::getIndicatorFileName(), static::getIndicatorFileContent());

                // Clean cache indicators to force cache generation
                \Includes\Decorator\Utils\CacheManager::cleanupCacheIndicators();
            }

            // Redirect to avoid loop
            \Includes\Utils\Operator::redirect('admin.php?target=main');
        }
    }


    /**
     * Check Access Key
     *
     * @return boolean
     */
    protected static function checkAccessKey()
    {
        return !empty($_GET[self::PARAM_ACCESS_KEY]) && static::getAccessKey() === $_GET[self::PARAM_ACCESS_KEY];
    }

    /**
     * Get safe mode indicator file name
     *
     * @return string
     */
    protected static function getIndicatorFileName()
    {
        return LC_DIR_VAR . '.safeModeStarted';
    }

    /**
     * Get safe mode access key file name
     *
     * @return string
     */
    protected static function getAccessKeyFileName()
    {
        return LC_DIR_DATA . '.safeModeAccessKey';
    }

    /**
     * Generate Access Key
     *
     * @return string
     */
    protected static function generateAccessKey()
    {
        return uniqid();
    }

    /**
     * Data to write into the indicator file
     *
     * @return string
     */
    protected static function getIndicatorFileContent()
    {
        return date('r') . (isset($_GET[self::PARAM_SOFT_RESET]) ? ', ' . self::LABEL_SOFT_RESET : '');
    }


    // {{{ Unsafe modules methods

    /**
     * Remove file with active modules list
     *
     * @return void
     */
    public static function clearUnsafeModules()
    {
        \Includes\Utils\FileManager::deleteFile(static::getUnsafeModulesFilePath());
    }

    /**
     * Get unsafe modules list
     *
     * @param boolean $asPlainList Flag OPTIONAL
     *  
     * @return array
     */
    public static function getUnsafeModulesList($asPlainList = true)
    {
        $list = array();
        $path = static::getUnsafeModulesFilePath();

        if (\Includes\Utils\FileManager::isFileReadable($path)) {
            foreach (parse_ini_file($path, true) as $author => $names) {
                foreach (array_filter($names) as $name => $flag) {

                    if ($asPlainList) {
                        $list[] = $author . '\\' . $name;

                    } else {
                        if (!isset($list[$author])) {
                            $list[$author] = array();
                        }

                        $list[$author][$name] = 1;
                    }
                }
            }
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
     */
    public static function markModuleAsUnsafe($author, $name)
    {
        static::markModulesAsUnsafe(array($author => array($name => true)));
    }

    /**
     * Mark modules as unsafe
     *
     * @param array $modules Modules
     *
     * @return void
     */
    public static function markModulesAsUnsafe(array $modules)
    {
        $list = static::getUnsafeModulesList(false);

        foreach ($modules as $author => $names) {
            foreach ($names as $name => $key) {

                if (!isset($list[$author])) {
                    $list[$author] = array();
                }

                $list[$author][$name] = 1;
            }
        }

        static::saveUnsafeModulesToFile($list);
    }

    /**
     * Get modules list file path
     *
     * @return string
     */
    protected static function getUnsafeModulesFilePath()
    {
        return LC_DIR_VAR . self::UNSAFE_MODULES_FILE_NAME;
    }

    /**
     * Save modules to file
     *
     * @param array $modules Modules array
     *
     * @return integer|boolean
     */
    protected static function saveUnsafeModulesToFile(array $modules)
    {
        $path = static::getUnsafeModulesFilePath();

        $string = '; <' . '?php /*' . PHP_EOL;

        $i = 0;
        foreach ($modules as $author => $names) {
            $string .= '[' . $author . ']' . PHP_EOL;
            foreach ($names as $name => $enabled) {
                $string .= $name . ' = ' . $enabled . PHP_EOL;
                $i++;
            }
        }

        $string .= '; */ ?' . '>';

        return $i ? \Includes\Utils\FileManager::write($path, $string) : false;
    }

    // }}}
}
