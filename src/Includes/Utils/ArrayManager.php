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
 * ArrayManager 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class ArrayManager extends AUtils
{
    /**
     * Check if passed has no duplicate elements (except of the "skip" ones)
     * TODO:  to improve
     * 
     * @param array  $array       array to check
     * @param string &$firstValue first duplicated value
     * @param array  $skip        values to skip
     *  
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isArrayUnique(array $array, &$firstValue, array $skip = null)
    {
        $result = true;

        foreach (array_count_values($array) as $key => $value) {
            if (!isset($skip) || !in_array($key, $skip)) {
                $result = (1 >= $value);
                if (!$result) {
                    $firstValue = $key;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Return array elements having the corresponded keys
     *
     * @param array $data   array to filter
     * @param array $keys   keys (filter rule)
     * @param bool  $invert flag; determines which function to use: "diff" or "intersect"
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function filterArrayByKeys(array $data, array $keys, $invert = false)
    {
        return call_user_func_array(
            'array_' . ($invert ? 'diff' : 'intersect') . '_key',
            array($data, array_fill_keys($keys, true))
        );
    }
}
