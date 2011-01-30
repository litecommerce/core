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
class StaticRoutines extends \Includes\Decorator\Utils\AUtils
{
    /**
     * Name of the function which is used as the static constructor 
     */
    const CONSTRUCTOR_NAME = '__constructStatic';


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
        return '/^\s*(?:final\s+)?\s*(?:public\s+)?static\s+function\s+' . self::CONSTRUCTOR_NAME . '\s*\(\s*\)/USism';
    }


    /**
     * Check and (if found) add the static constructor call
     *
     * @param \Includes\Decorator\DataStructure\Node\ClassInfo $info     Class info
     * @param string                                           &$content Class file content
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function checkForStaticConstructor(\Includes\Decorator\DataStructure\Node\ClassInfo $info, &$content)
    {
        if (preg_match(static::getConstructorPattern(), $content)) {
            $content .= "\n\n" . '// Call static constructor' . "\n";
            $content .= $info->getClass() . '::' . self::CONSTRUCTOR_NAME . '();';
        }
    }
}
