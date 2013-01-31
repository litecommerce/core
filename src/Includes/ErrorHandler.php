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
 * ErrorHandler
 *
 * @package XLite
 */
abstract class ErrorHandler
{
    /**
     * Common error codes
     */
    const ERROR_UNKNOWN          = -1;
    const ERROR_FATAL_ERROR      = 2;
    const ERROR_MAINTENANCE_MODE = -9999;
    const ERROR_NOT_INSTALLED    = -8888;

    /**
     * Error page types
     */
    const ERROR_PAGE_TYPE_ERROR         = 'error';
    const ERROR_PAGE_TYPE_MAINTENANCE   = 'maintenance';
    const ERROR_PAGE_TYPE_NOT_INSTALLED = 'install';

    /**
     * Throw exception
     *
     * @param string  $message Error message
     * @param integer $code    Error code
     *
     * @return void
     */
    protected static function throwException($message, $code)
    {
        throw new \Exception($message, $code);
    }

    /**
     * Add info to a log file
     *
     * @param string  $message   Error message
     * @param integer $code      Error code
     * @param string  $backtrace Stack trace OPTIONAL
     *
     * @return void
     */
    protected static function logInfo($message, $code, $backtrace = null)
    {
        if (!isset($backtrace)) {
            ob_start();
            debug_print_backtrace();
            $backtrace = ob_get_contents();
            ob_end_clean();
        }

        $message = date('[d-M-Y H:i:s]') . ' Error (code: ' . $code . '): ' . $message . PHP_EOL;

        // Add additional info
        $parts = array(
            'Server API: ' . PHP_SAPI,
        );

        if (isset($_SERVER)) {
            if (isset($_SERVER['REQUEST_METHOD'])) {
                $parts[] = 'Request method: ' . $_SERVER['REQUEST_METHOD'];
            }

            if (isset($_SERVER['REQUEST_URI'])) {
                $parts[] = 'URI: ' . $_SERVER['REQUEST_URI'];
            }
        }

        $message .= implode(';' . PHP_EOL, $parts) . ';' . PHP_EOL;
        $message .= 'Backtrace: ' . PHP_EOL . $backtrace . PHP_EOL . PHP_EOL;

        \Includes\Utils\FileManager::write(static::getLogFile(), $message, FILE_APPEND);
    }

    /**
     * Return path to the log file
     *
     * @return string
     */
    protected static function getLogFile()
    {
        return LC_DIR_VAR . 'log' . LC_DS . 'php_errors.log.' . date('Y-m-d') . '.php';
    }

    /**
     * Return name of the error page file (.html)
     *
     * @return string
     */
    protected static function getErrorPageFileDefault()
    {
        return 'public' . LC_DS . 'error.html';
    }

    /**
     * Return name of the error page file (.html)
     *
     * @return string
     */
    protected static function getErrorPageFileFromConfig()
    {
        return \Includes\Utils\ConfigParser::getOptions(array('error_handling', 'page'));
    }

    /**
     * Return name of the maintenance page file (.html)
     *
     * @return string
     */
    protected static function getMaintenancePageFileDefault()
    {
        return 'public' . LC_DS . 'maintenance.html';
    }

    /**
     * Return name of the maintenance page file (.html)
     *
     * @return string
     */
    protected static function getMaintenancePageFileFromConfig()
    {
        return \Includes\Utils\ConfigParser::getOptions(array('error_handling', 'maintenance'));
    }

    /**
     * Return name of the error page file (.html)
     *
     * @return string
     */
    protected static function getNotInstalledPageFile()
    {
        return 'public' . LC_DS . 'install.html';
    }

    /**
     * Return name of the error page file (.html)
     *
     * @return string
     */
    protected static function getErrorPageFile($type = self::ERROR_PAGE_TYPE_ERROR)
    {
        if (self::ERROR_PAGE_TYPE_MAINTENANCE == $type) {
            $file = LC_DIR_ROOT . (static::getMaintenancePageFileFromConfig() ?: static::getMaintenancePageFileDefault());

        } elseif (self::ERROR_PAGE_TYPE_NOT_INSTALLED == $type) {
            $file = LC_DIR_ROOT . static::getNotInstalledPageFile();

        } else {
            $file = LC_DIR_ROOT . (static::getErrorPageFileFromConfig() ?: static::getErrorPageFileDefault());
        }

        return $file;
    }

    /**
     * Return content of the error page file (.html)
     *
     * @return string
     */
    protected static function getErrorPageFileContent($type = self::ERROR_PAGE_TYPE_ERROR)
    {
        return \Includes\Utils\FileManager::read(static::getErrorPageFile($type)) ?: LC_ERROR_PAGE_MESSAGE;
    }

    /**
     * Return content of the error page file (.html)
     *
     * @return string
     */
    protected static function getErrorPage($type = self::ERROR_PAGE_TYPE_ERROR)
    {
        return str_replace('@URL@', \Includes\Utils\URLManager::getShopURL(), static::getErrorPageFileContent($type));
    }

    /**
     * Show error message (page)
     *
     * @param mixed  $code    Error code
     * @param string $message Error message
     * @param string $page    Error page or message template
     *
     * @return void
     */
    protected static function showErrorPage($code, $message, $page = null)
    {
        showErrorPage(
            $code,
            $message,
            $page
            ?: (
                LC_IS_CLI_MODE
                ? LC_ERROR_PAGE_MESSAGE
                : static::getErrorPage(static::getErrorPageType($code))
            )
        );
    }

    /**
     * Return content of the error page file (.html)
     *
     * @return string
     */
    protected static function getErrorPageType($code)
    {
        $result = self::ERROR_PAGE_TYPE_ERROR;

        if (self::ERROR_MAINTENANCE_MODE == $code) {
            $result = self::ERROR_PAGE_TYPE_MAINTENANCE;

        } elseif (self::ERROR_NOT_INSTALLED == $code) {
            $result = self::ERROR_PAGE_TYPE_NOT_INSTALLED;
        }

        return $result;
    }

    /**
     * Shutdown function
     *
     * @return void
     */
    public static function shutdown()
    {
        static::handleError(error_get_last() ?: array());
    }

    /**
     * Error handler
     *
     * @param array $error catched error
     *
     * @return void
     */
    public static function handleError(array $error)
    {
        \Includes\Decorator\Utils\CacheManager::checkRebuildIndicatorState();

        if (isset($error['type']) && E_ERROR == $error['type']) {
            static::logInfo($error['message'], $error['type']);
            static::showErrorPage(__CLASS__ . '::ERROR_FATAL_ERROR', $error['message']);
        }
    }

    /**
     * Exception handler
     *
     * @param \Exception $exception catched exception
     *
     * @return void
     */
    public static function handleException(\Exception $exception)
    {
        static::logInfo($exception->getMessage(), $exception->getCode(), $exception->getTraceAsString());
        static::showErrorPage($exception->getCode(), $exception->getMessage());
    }

    /**
     * Provoke an error
     *
     * @param string  $message Error message
     * @param integer $code    Error code
     *
     * @return void
     */
    public static function fireError($message, $code = self::ERROR_UNKNOWN)
    {
        static::throwException($message, $code);
    }

    /**
     * Method to display certain error
     *
     * @param string $method Name of an abstract method
     *
     * @return void
     */
    public static function fireErrorAbstractMethodCall($method)
    {
        static::fireError('Abstract method call: ' . $method);
    }

    /**
     * Check if LC is installed
     *
     * @return void
     */
    public static function checkIsLCInstalled()
    {
        if (!\Includes\Utils\ConfigParser::getOptions(array('database_details', 'database'))) {

            $link = \Includes\Utils\URLManager::getShopURL('install.php');
            $message = '<a href="' . $link . '">Click here</a> to run the installation wizard.';

            static::showErrorPage(self::ERROR_NOT_INSTALLED, $message);
        }
    }
}
