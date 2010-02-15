<?php

/* $Id$ */

/**
 * Miscelaneous convertion routines
 *
 * @package    Lite Commerce
 * @subpackage Core
 * @since      3.0
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
		return self::_getInstance(__CLASS__);
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
	 *  
	 * @return string
	 * @access public
	 * @since  3.0
	 */
	public static function buildQuery(array $params, $glue = '=', $separator = '&')
	{
		$result = array();

		foreach ($params as $name => $value) {
			$result[] = $name . $glue . $value;
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
			   . (XLite::getInstance()->adminZone ? 'Admin' : 'Customer') 
			   . (empty($target) ? '' : '_' . self::convertToCamelCase($target));
	}

	/**
     * Compose URL from target, action and additional params
     *
     * @param string $target page identifier
     * @param string $action action to perform
     * @param array  $params additional params
     *
     * @return string
     * @access public
     * @since  3.0
     */
    public static function buildURL($target, $action = '', array $params = array())
    {
        return XLite::getInstance()->getScript() 
			   . (empty($target) ? '' : '?target=' . $target)
               . (empty($action) ? '' : '&action=' . $action)
               . (empty($params) ? '' : '&' . http_build_query($params));
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
    public static function buildFullURL($target, $action = '', array $params = array())
    {
		return XLite::getInstance()->shopURL(self::buildURL($target, $action, $params));
	}
}
