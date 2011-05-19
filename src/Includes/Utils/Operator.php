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
 * @subpackage Includes_Utils
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace Includes\Utils;

/**
 * Operator
 *
 * @package    XLite
 * @see        ____class_see____
 * @since      1.0.0
 */
abstract class Operator extends \Includes\Utils\AUtils
{
    /**
     * Return length of the "dummy" buffer for flush
     *
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getDummyBufferLength()
    {
        return 4096;
    }

    /**
     * Perform the "flush" itself
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function flushBuffers()
    {
        @ob_flush();
        flush();
    }

    /**
     * Wrap message into some HTML tags (to fast output)
     *
     * @param string $message  Message to prepare
     * @param string $jsOutput JS output
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getJSMessage($message, $jsOutput)
    {
        return '<noscript>' . $message . '</noscript>'
             . '<script type="text/javascript">' . $jsOutput . '</script>';
    }


    /**
     * Redirect
     *
     * @param string $location URL
     * @param int    $code     operation code
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function redirect($location, $code = 302)
    {
        if ('cli' !== PHP_SAPI) {

            if (headers_sent()) {
                $message  = '<a href="' . $location . '">Click here to redirect</a>';
                $jsOutput = 'self.location = \'' . $location . '\';';

                static::flush($message, true, $jsOutput);

            } else {
                header('Location: ' . $location, true, $code);
            }
        }

        exit (0);
    }

    /**
     * Refresh current page
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function refresh()
    {
        static::redirect(\Includes\Utils\URLManager::getSelfURI());
    }

    /**
     * Echo message and flush output
     *
     * @param string  $message    Text to display
     * @param boolean $dummyFlush Output extra spaces or not OPTIONAL
     * @param string  $jsOutput   Flag to quick output OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function flush($message, $dummyFlush = false, $jsOutput = null)
    {
        if ('cli' !== PHP_SAPI) {

            // Send extra whitespace before flushing
            if ($dummyFlush) {
                echo (str_repeat(' ', static::getDummyBufferLength()));
            }

            // Wrap message into the "<script>" tag
            if (isset($jsOutput)) {
                $message = static::getJSMessage($message, $jsOutput);
            }
        }

        // Print message
        echo ($message);

        static::flushBuffers();
    }

    /**
     * Wrapper to message quick display
     *
     * @param string $message Message text
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function showMessage($message)
    {
        static::flush($message, true, 'document.write(\'<div class="service-message">' . $message . '</div>\');');
        static::flush(LC_EOL);
    }

    /**
     * Set custom value for the "max_execution_time" INI setting, and execute some function
     *
     * @param int   $time     time (in seconds) to set
     * @param mixed $callback function to execute
     * @param array $args     call arguments
     *
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function executeWithCustomMaxExecTime($time, $callback, array $args = array())
    {
        $savedValue = @ini_get('max_execution_time');
        @set_time_limit($time);

        $result = call_user_func_array($callback, $args);

        if (!empty($savedValue)) {
            @set_time_limit($savedValue);
        }

        return $result;
    }

    /**
     * Check if class is already declared.
     *
     * :NOTE: this function does not use autoloader
     *
     * @param string $name Class name
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function checkIfClassExists($name)
    {
        $file = \Includes\Autoloader::getLCAutoloadDir() . \Includes\Utils\Converter::getClassFile($name);

        return class_exists($name, false) || \Includes\Utils\FileManager::isFileReadable($file);
    }
}
