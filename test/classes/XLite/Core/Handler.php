<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */


/**
 * XLite_Core_Handler 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Core_Handler extends XLite_Base
{
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
    public function buildURL($target = '', $action = '', array $params = array())
    {
        return XLite_Core_Converter::buildURL($target, $action, $params);
    }

    /**
     * Compose URL path from target, action and additional params
     * FIXME - this method must be removed
     *
     * @param string $target page identifier
     * @param string $action action to perform
     * @param array  $params additional params
     *
     * @return string
     * @access public
     * @since  3.0
     */
    public function buildURLPath($target, $action = '', array $params = array())
    {
        $url = $this->buildURL($target, $action, $params);
        $parts = parse_url($url);

        return (!isset($parts['path']) || strlen($parts['path'])) ? './' : $parts['path'];
    }

    /**
     * Compose URL query arguments from target, action and additional params
     * FIXME - this method must be removed
     *
     * @param string $target page identifier
     * @param string $action action to perform
     * @param array  $params additional params
     *
     * @return array
     * @access public
     * @since  3.0
     */
    public function buildURLArguments($target, $action = '', array $params = array())
    {
        $url = $this->buildURL($target, $action, $params);
        $parts = parse_url($url);

        $args = array();
        if (isset($parts['query'])) {
            parse_str($parts['query'], $args);
        }

        return $args;
    }
}

