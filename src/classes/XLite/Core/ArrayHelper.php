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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Core;

/**
 * Array-based operations helper
 *
 * @see   ____class_see____
 * @since 3.0.0
 */
class ArrayHelper extends \XLite\Base\Singleton
{
    /**
     * Find item
     * 
     * @param mixed    &$data    Data
     * @param callable $callback Callback
     * @param mixed    $userData Additional data OPTIONAL
     *  
     * @return array|void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function find(&$data, $callback, $userData = null)
    {
        $found = null;

        if (is_callable($callback) && (is_array($data) || $data instanceof \IteratorAggregate)) {
            foreach ($data as $key => $value) {
                // Input argument
                if (call_user_func($callback, $key, $value, $userData)) {
                    $found = $value;
                    break;
                }
            }
        }

        return $found;
    }

    /**
     * Filter array
     * 
     * @param mixed    &$data    Data
     * @param callable $callback Callback
     * @param mixed    $userData Additional data OPTIONAL
     *  
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function filter(&$data, $callback, $userData = null)
    {
        $result = array();

        if (is_callable($callback) && (is_array($data) || $data instanceof \IteratorAggregate)) {
            foreach ($data as $key => $value) {
                // Input argument
                if (call_user_func($callback, $key, $value, $userData)) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }
}
