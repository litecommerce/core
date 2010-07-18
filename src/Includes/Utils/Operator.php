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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Utils;

/**
 * Operator 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class Operator extends AUtils
{
    /**
     * Flush output 
     * 
     * @param string $message text to display
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function flush($message = null)
    {
        if (isset($message)) {
            echo $message;
        }
    
        if (preg_match('/Apache(.*)Win/Ss', getenv('SERVER_SOFTWARE'))) {
            echo str_repeat(' ', 2500);

        } elseif (preg_match('/(.*)MSIE(.*)\)$/S', getenv('HTTP_USER_AGENT'))) {
            echo str_repeat(' ', 256);
        }

        ob_flush();
        flush();
    }
}
