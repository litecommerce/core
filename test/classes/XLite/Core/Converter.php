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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Miscelaneous convertion routines
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Core_Converter extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * Singleton access method
     * 
     * @return XLite_Core_Converter
     * @access public
     * @since  3.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
     * Convert a string like "test_foo_bar" into the camel case (like "TestFooBar")
     * 
     * @param string $string string to convert
     *  
     * @return string
     * @access public
     * @since  3.0
     */
    public static function convertToCamelCase($string)
    {
        return strval(preg_replace('/((?:\A|_)([a-zA-Z]))/ie', 'strtoupper(\'\\2\')', $string));
    }

    /**
     * Compose string from array 
     * 
     * @param array  $params    params list
     * @param string $glue      char to agglutinate "name" and "value"
     * @param string $separator char to agglutinate <"name", "value"> pairs
     * @param string $quotes    char to quote the "value" param
     *  
     * @return string
     * @access public
     * @since  3.0
     */
    public static function buildQuery(array $params, $glue = '=', $separator = '&', $quotes = '')
    {
        $result = array();

        foreach ($params as $name => $value) {
            $result[] = $name . $glue . $quotes . $value . $quotes;
        }

        return implode($separator, $result);
    }

    /**
     * Compose controller class name using target
     * 
     * @param string $target current target
     *  
     * @return string
     * @access public
     * @since  1.0.0
     */
    public static function getControllerClass($target)
    {
        return 'XLite_Controller_' 
               . (XLite::isAdminZone() ? 'Admin' : 'Customer') 
               . (empty($target) ? '' : '_' . self::convertToCamelCase($target));
    }

    /**
     * Compose URL from target, action and additional params
     *
     * @param string $target    Page identifier
     * @param string $action    Action to perform
     * @param array  $params    Additional params
     * @param string $interface Interface script
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public static function buildURL($target = '', $action = '', array $params = array(), $interface = null)
    {
        $url = is_null($interface)
            ? XLite::getInstance()->getScript()
            : $interface;

        $parts = array();

        if ($target) {
            $parts[] = 'target=' . $target;
        }

        if ($action) {
            $parts[] = 'action=' . $action;
        }

        if ($params) {
            $parts[] = http_build_query($params);
        }

        if ($parts) {
            $url .= '?' . implode('&', $parts);
        }

        return $url;
    }

    /**
     * Compose full URL from target, action and additional params
     *
     * @param string $target page identifier
     * @param string $action action to perform
     * @param array  $params additional params
     *
     * @return string
     * @access public
     * @since  3.0
     */
    public static function buildFullURL($target = '', $action = '', array $params = array())
    {
        return XLite::getInstance()->getShopUrl(self::buildURL($target, $action, $params));
    }

    /**
     * Return array schema 
     * 
     * @param array $keys   keys list
     * @param array $values values list
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public static function getArraySchema(array $keys = array(), array $values = array())
    {
        return array_combine($keys, $values);
    }

    /**
     * Convert to one-dimensional array 
     * 
     * @param array  $data    array to flat
     * @param string $currKey parameter for recursive calls
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public static function flatArray(array $data, $currKey = '')
    {
        $result = array();

        foreach ($data as $key => $value) {
            $key = $currKey . (empty($currKey) ? $key : '[' . $key . ']');
            $result += is_array($value) ? self::flatArray($value, $key) : array($key => $value);
        }

        return $result;
    }

    /**
     * Return array elements having the corresponded keys 
     * 
     * @param array $data array to filter
     * @param array $keys keys (filter rule)
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public static function filterArrayByKeys(array $data, array $keys)
    {
        return array_intersect_key($data, array_fill_keys($keys, true));
    }
}
