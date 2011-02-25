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

namespace Includes\Utils;

/**
 * Array manager 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class ArrayManager extends AUtils
{
    /**
     * Check if passed has no duplicate elements (except of the "skip" ones)
     * TODO:  to improve
     * 
     * @param array  $array       Array to check
     * @param string &$firstValue First duplicated value
     * @param array  $skip        Values to skip OPTIONAL
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isUnique(array $array, &$firstValue, array $skip = null)
    {
        $result = true;

        foreach (array_count_values($array) as $key => $value) {
            if (!isset($skip) || !in_array($key, $skip)) {
                if (1 < $value) {
                    $result = false;
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
     * @param array   $data   Array to filter
     * @param array   $keys   Keys (filter rule)
     * @param boolean $invert Flag; determines which function to use: "diff" or "intersect" OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function filterByKeys(array $data, array $keys, $invert = false)
    {
        return call_user_func_array(
            $invert ? 'array_diff_key' : 'array_intersect_key',
            array($data, array_fill_keys($keys, true))
        );
    }

    /**
     * Return some object property values
     *
     * @param array   $array    Array to use
     * @param string  $field    Field to return
     * @param boolean $isGetter Determines if the second param is a property name or a method OPTIONAL
     *
     * @return array
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
     * @param boolean $isGetter Determines if the second param is a property name or a method OPTIONAL
     *
     * @return mixed
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
     * @param boolean $isGetter Determines if the second param is a property name or a method OPTIONAL
     *
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function searchInObjectsArray(array $array, $field, $value, $isGetter = false)
    {
        $list = static::searchAllInObjectsArray($array, $field, $value, $isGetter);

        return $list ? reset($list) : null;
    }

    /**
     * Sum some object property values
     *
     * @param array   $array    Array to use
     * @param string  $field    Field to sum by
     * @param boolean $isGetter Determines if the second param is a property name or a method OPTIONAL
     *
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function sumObjectsArrayFieldValues(array $array, $field, $isGetter = false)
    {
        return array_sum(static::getObjectsArrayFieldValues($array, $field, $isGetter));
    }

    /**
     * Find item
     * 
     * @param mixed    &$data    Data
     * @param callback $callback Callback
     * @param mixed    $userData Additional data OPTIONAL
     *  
     * @return array|void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function findValue(&$data, $callback, $userData = null)
    {
        $found = null;

        if (is_callable($callback) && (is_array($data) || $data instanceof \IteratorAggregate)) {
            foreach ($data as $key => $value) {
                // Input argument
                if (call_user_func($callback, $value, $userData)) {
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
     * @param callback $callback Callback
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
                if (call_user_func($callback, $value, $userData)) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

}
