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
 * @version    GIT: $Id$
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

    /**
     * Return some object property values
     *
     * @param array   $array    Array to use
     * @param string  $field    Field to return
     * @param boolean $isGetter Determines if the second param is a property name or a method
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getObjectsArrayFieldValues(array $array, $field, $isGetter = false)
    {
        return array_map(
            function ($var) use ($field, $isGetter) {
                return \Includes\Utils\Converter::getObjectField($var, $field, $isGetter);
            },
            $array
        );
    }

    /**
     * Search entities in array by a field value
     *
     * @param array   $array    Array to search
     * @param string  $field    Field to search by
     * @param mixed   $value    Value to use for comparison
     * @param boolean $isGetter Determines if the second param is a property name or a method
     *
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function searchAllInObjectsArray(array $array, $field, $value, $isGetter = false)
    {
        $list = array_filter(
            $array,
            function ($var) use ($field, $value, $isGetter) {
                return \Includes\Utils\Converter::getObjectField($var, $field, $isGetter) == $value;
            }
        );

        return $list ?: array();
    }

    /**
     * Search entity in array by a field value
     *
     * @param array   $array    Array to search
     * @param string  $field    Field to search by
     * @param mixed   $value    Value to use for comparison
     * @param boolean $isGetter Determines if the second param is a property name or a method
     *
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function searchInObjectsArray(array $array, $field, $value, $isGetter = false)
    {
        return ($list = static::searchAllInObjectsArray($array, $field, $value, $isGetter)) ? reset($list) : null;
    }

    /**
     * Sum some object property values
     *
     * @param array   $array    Array to use
     * @param string  $field    Field to sum by
     * @param boolean $isGetter Determines if the second param is a property name or a method
     *
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sumObjectsArrayFieldValues(array $array, $field, $isGetter = false)
    {
        return array_sum(static::getObjectsArrayFieldValues($array, $field, $isGetter));
    }
}
