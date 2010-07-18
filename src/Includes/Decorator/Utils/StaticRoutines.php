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
 * @subpackage Includes_Decorator_Utils
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
 * StaticRoutines 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class StaticRoutines extends AUtils
{
    /**
     * Name of the function which is used as the static constructor 
     */
    const STATIC_CONSTRUCTOR_NAME = '__constructStatic';


    /**
     * Return name of the function which is used as the static constructor
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getConstructorName()
    {
        return static::STATIC_CONSTRUCTOR_NAME;
    }

    /**
     * Return pattern to search static constructor in PHP code 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getConstructorPattern()
    {
        return '/^\s*(?:final\s+)?\s*(?:public\s+)?static\s+function\s+' . static::getConstructorName() . '\s*\(\s*\)/USism';
    }


    /**
     * Check and (if found) add the static constructor call
     *
     * @param array  &$info    class info
     * @param string &$content class file content
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function checkForStaticConstructor(array &$info, &$content)
    {
        if (preg_match(static::getConstructorPattern(), $content)) {
            $content .= "\n\n" . '// Call static constructor' . "\n" . '\\' 
                . (isset($info[self::INFO_CLASS]) ? $info[self::INFO_CLASS] : $info[self::INFO_CLASS_ORIG]) 
                . '::' . static::getConstructorName() . '();';
        }
    }
}
