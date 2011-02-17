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
 * ErrorHandler 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class ErrorHandler
{
    /**
     * Common error codes
     */

    const ERROR_UNKNOWN     = -1;
    const ERROR_FATAL_ERROR = 2;


    /**
     * Throw exception
     *
     * @param string  $message Error message
     * @param integer $code    Error code
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function throwException($message, $code)
    {
        throw new \Exception($message, $code);
    }

    /**
     * Add info to a log file
     *
     * @param string  $message Error message
     * @param integer $code    Error code
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function logInfo($message, $code)
    {
        // TODO
    }

    /**
     * Return name of the error page file (.html)
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getErrorPageFileDefault()
    {
        return 'public' . LC_DS . 'error.html';
    }

    /**
     * Return name of the error page file (.html)
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getErrorPageFileFromConfig()
    {
        return \Includes\Utils\ConfigParser::getOptions(array('error_handling', 'page'));
    }

    /**
     * Return name of the error page file (.html)
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getErrorPageFile()
    {
        return LC_ROOT_DIR . (static::getErrorPageFileFromConfig() ?: static::getErrorPageFileDefault());
    }

    /**
     * Return content of the error page file (.html)
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getErrorPageFileContent()
    {
        return \Includes\Utils\FileManager::read(static::getErrorPageFile()) ?: LC_ERROR_PAGE_MESSAGE;
    }

    /**
     * Return content of the error page file (.html)
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getErrorPage()
    {
        return str_replace('@URL@', \Includes\Utils\URLManager::getShopURL(), static::getErrorPageFileContent());
    }

    /**
     * Show error message (page)
     * 
     * @param mixed  $code    Error code
     * @param string $message Error message
     * @param string $page    Error page or message template
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function showErrorPage($code, $message, $page = null)
    {
        showErrorPage($code, $message, $page ?: static::getErrorPage());
    }


    /**
     * Shutdown function
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function handleError(array $error)
    {
        !LC_DEVELOPER_MODE ?: \Includes\Decorator\Utils\CacheManager::checkRebuildIndicatorState();

        if (isset($error['type']) && E_ERROR == $error['type']) {
            static::showErrorPage(__CLASS__ . '::ERROR_FATAL_ERROR', $error['message']);
        }
    }

    /**
     * Exception handler
     * 
     * @param \Exception $exception catched exception
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function handleException(\Exception $exception)
    {
        static::showErrorPage($exception->getCode(), $exception->getMessage());
    }

    /**
     * Provoke an error
     *
     * @param string  $message Error message
     * @param integer $code    Error code
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function fireError($message, $code = self::ERROR_UNKNOWN)
    {
        static::logInfo($message, $code);
        static::throwException($message, $code);
    }

    /**
     * Check if LC is installed
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function checkIsLCInstalled()
    {
        if (!\Includes\Utils\ConfigParser::getOptions(array('database_details', 'database'))) {

            $message = 'Probably LC is not installed. Try to run ';
            $url = '<strong>install.php</strong>';

            if (@file_exists($link = \Includes\Utils\URLManager::getShopURL('install.php'))) {
                $url = '<a href="' . $link . '">' . $url . '</a>';
            }

            static::showErrorPage('ERROR_LC_NOT_INTSTALLED', $message . $url, LC_ERROR_PAGE_MESSAGE);
        }
    }
}
