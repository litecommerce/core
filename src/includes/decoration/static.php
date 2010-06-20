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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Methods to preprocess static properties/functions
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class DecoratorStaticRoutines
{
    /**
     * Pattern to check if "static constructor" is defined
     */
    const PATTERN_STATIC_CONSTRUCTOR = '^\s*(?:final\s+)?\s*(?:public\s+)?static\s+function\s+__constructStatic\s*\(\s*\)';


    /**
     * Check and (if found) add the static constructor call 
     * 
     * @param string $class    class name
     * @param string &$content class file content
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkForStaticConstructor($class, &$content)
    {
        if (preg_match('/' . self::PATTERN_STATIC_CONSTRUCTOR . '/USism', $content)) {
            $content .= "\n\n" . '// Call static constructor' . "\n" . $class . '::__constructStatic();';
        }
    }
}
