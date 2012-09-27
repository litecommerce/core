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

namespace Includes\Utils;

/**
 * ConfigParser
 *
 * @package    XLite
 */
abstract class ConfigParser extends \Includes\Utils\AUtils
{
    /**
     * Options cache
     *
     * @var array
     */
    protected static $options;

    /**
     * List of function to modify options
     *
     * @var array
     */
    protected static $mutators = array(
        'setWebDirWOSlash'
    );

    /**
     * List of additional source files for options gathering
     *
     * @var array
     */
    protected static $configFiles = array(
        'config.php',
        'config.local.php',
    );

    /**
     * Return path to the main config file
     *
     * @return string
     */
    protected static function getMainFile()
    {
        return LC_DIR_CONFIG . 'config.default.php';
    }

    /**
     * Throw the exception if config file is not found
     *
     * @param string $file file which caused an error
     *
     * @return void
     */
    protected static function handleFileAbsenceError($file)
    {
        throw new \Exception('Config file "' . $file . '" does not exist or is not readable');
    }

    /**
     * Throw the exception if unable to parse config file
     *
     * @param string $file file which caused an error
     *
     * @return void
     */
    protected static function handleFileWrongFormatError($file)
    {
        throw new \Exception('Unable to parse config file "' . $file . '" (probably it has a wrong format)');
    }

    /**
     * Check if file exists and is readable
     *
     * @param string $file file to check
     *
     * @return bool
     */
    protected static function checkFile($file)
    {
        return \Includes\Utils\FileManager::isFileReadable($file);
    }

    /**
     * Common function to parse config files
     *
     * @param string $file         file to parse
     * @param string $errorHandler name of error handler (method)
     *
     * @return array
     */
    protected static function parseCommon($file, $errorHandler = null)
    {
        $options = array();

        if (static::checkFile($file)) {
            if (!is_array($options = parse_ini_file($file, true))) {
                static::handleFileWrongFormatError($file);
            }
        } elseif (isset($errorHandler)) {
            static::$errorHandler($file);
        }

        return $options;
    }

    /**
     * Parse main config file
     *
     * @return array
     */
    protected static function parseMainFile()
    {
        return static::parseCommon(static::getMainFile(), 'handleFileAbsenceError');
    }

    /**
     * Parse local config file
     *
     * @return array
     */
    protected static function parseLocalFile($fileName)
    {
        return static::parseCommon(LC_DIR_CONFIG . LC_DS . $fileName);
    }

    /**
     * Fetch options from array
     *
     * @param array $names   option names tree
     * @param array $options options list
     *
     * @return array|mixed
     */
    protected static function getOptionsByNames(array $names, $options)
    {
        $name = array_shift($names);
        $options = empty($name) ? $options : (empty($options[$name]) ? null : $options[$name]);

        return empty($names) ? $options : static::getOptionsByNames($names, $options);
    }

    /**
     * Exceute the mutators stack
     *
     * @return void
     */
    protected static function executeMutators()
    {
        foreach (static::$mutators as $method) {
            static::$method();
        }
    }

    /**
     * Create the "web_dir_wo_slash" option
     *
     * @return void
     */
    protected static function setWebDirWOSlash()
    {
        static::$options['host_details']['web_dir_wo_slash']
            = \Includes\Utils\URLManager::trimTrailingSlashes(static::$options['host_details']['web_dir']);
    }

    /**
     * Parse both config files
     *
     * @param array|string $names option names tree
     *
     * @return array|mixed
     */
    public static function getOptions($names = null)
    {
        if (!isset(static::$options)) {

            static::$options = static::parseMainFile();

            for ($i = 0; $i < count(static::$configFiles); $i++) {
                static::$options = array_replace_recursive(static::$options, static::parseLocalFile(static::$configFiles[$i]));
            }

            static::executeMutators();
        }

        return static::getOptionsByNames(is_array($names) ? $names : array($names), static::$options);
    }

    /**
     * Register additional config file
     *
     * @param string $fileName Config file name
     *
     * @return void
     */
    public static function registerConfigFile($fileName)
    {
        if (false === array_search($fileName, static::$configFiles)) {
            static::$configFiles[] = $fileName;
            static::$options = null;
        }
    }
}
