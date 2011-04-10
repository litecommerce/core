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
 * @since     1.0.0
 */

namespace Includes\Utils;

/**
 * Array manager 
 * 
 * @see   ____class_see____
 * @since 1.0.0
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
     * @since  1.0.0
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
     * Method to safely get array element (or a whole array)
     *
     * @param array          $data  Data array
     * @param integer|string $index  Array index
     * @param boolean        $strict Flag; return value or null in any case
     *
     * @return array|mixed|null
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getIndex(array $data, $index = null, $strict = false)
    {
        return isset($index) ? (isset($data[$index]) ? $data[$index] : null) : ($strict ? null : $data);
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
     * @since  1.0.0
     */
    public static function filterByKeys(array $data, array $keys, $invert = false)
    {
        return call_user_func_array(
            $invert ? 'array_diff_key' : 'array_intersect_key',
            array($data, array_fill_keys($keys, true))
        );
    }

    /**
     * Wrapper to return property from object
     *
     * @param object  $object   Object to get property from
     * @param string  $field    Field to get
     * @param boolean $isGetter Determines if the second param is a property name or a method
     *
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getObjectField($object, $field, $isGetter = false)
    {
        return $isGetter ? $object->$field() : $object->$field;
    }

    /**
     * Return some array index
     *
     * @param array  $array Array to use
     * @param string $field Field to return
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getArraysArrayFieldValues(array $array, $field)
    {
        foreach ($array as &$element) {
            $element = static::getIndex($element, $field, true);
        }

        return $array;
    }

    /**
     * Search entities in array by a field value
     *
     * @param array  $array Array to search
     * @param string $field Field to search by
     * @param mixed  $value Value to use for comparison
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function searchAllInArraysArray(array $array, $field, $value)
    {
        $result = array();

        foreach ($array as $key => $element) {
            $element = (array) $element;
            if (static::getIndex($element, $field, true) == $value) {
                $result[$key] = $element;
            }
        }

        return $result;
    }

    /**
     * Search entities in array by a field value
     *
     * @param array  $array Array to search
     * @param string $field Field to search by
     * @param mixed  $value Value to use for comparison
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function searchInArraysArray(array $array, $field, $value)
    {
        $list = static::searchAllInObjectsArray($array, $field, $value);

        return $list ? reset($list) : null;
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
     * @since  1.0.0
     */
    public static function getObjectsArrayFieldValues(array $array, $field, $isGetter = true)
    {
        foreach ($array as &$element) {
            $element = static::getObjectField($element, $field, $isGetter);
        }

        return $array;
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
     * @since  1.0.0
     */
    public static function searchAllInObjectsArray(array $array, $field, $value, $isGetter = true)
    {
        $result = array();

        foreach ($array as $key => $element) {
            if (static::getObjectField($element, $field, $isGetter) == $value) {
                $result[$key] = $element;
            }
        }

        return $result;
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
     * @since  1.0.0
     */
    public static function searchInObjectsArray(array $array, $field, $value, $isGetter = true)
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
     * @since  1.0.0
     */
    public static function sumObjectsArrayFieldValues(array $array, $field, $isGetter = true)
    {
        return array_sum(static::getObjectsArrayFieldValues($array, $field, $isGetter));
    }

    /**
     * Find item
     *
     * FIXME: parameters are passed incorrectly into "call_user_func"
     * FIXME: "userData" parameter is not used
     * 
     * @param mixed    &$data    Data
     * @param callback $callback Callback
     * @param mixed    $userData Additional data OPTIONAL
     *  
     * @return array|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function findValue(&$data, $callback, $userData = null)
    {
        $found = null;

        foreach ($data as $key => $value) {

            // Input argument
            if (call_user_func($callback, $value, $userData)) {
                $found = $value;
                break;
            }
        }

        return $found;
    }

    /**
     * Filter array
     *
     * FIXME: must use the "array_filter" function
     * FIXME: parameters are passed incorrectly into "call_user_func"
     * FIXME: "userData" parameter is not used
     * 
     * @param mixed    &$data    Data
     * @param callback $callback Callback
     * @param mixed    $userData Additional data OPTIONAL
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function filter(&$data, $callback, $userData = null)
    {
        $result = array();

        foreach ($data as $key => $value) {

            // Input argument
            if (call_user_func($callback, $value, $userData)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
