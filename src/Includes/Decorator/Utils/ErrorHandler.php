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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Decorator\Utils;

/**
 * ErrorHandler 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class ErrorHandler extends \Includes\Decorator\Utils\AUtils
{
    // ------------------------------ Ancillary methods to handle an error -


    /**
     * Throw exception
     *
     * @param string  $message Error message
     * @param integer $code    Error code
     *
     * @return null
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
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function logInfo($message, $code)
    {
        // TODO
    }


    // ------------------------------ Public methods -


    /**
     * Decoration error
     * 
     * @param string  $message Error message
     * @param integer $code    Error code
     *  
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function fireError($message, $code = null)
    {
        static::logInfo($message, $code);
        static::throwException($message, $code);
    }
}
