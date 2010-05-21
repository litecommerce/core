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

// FIXME - must be refactored

/**
 * Miscelaneous convertion routines
 *
 * @package    XLite
 * @since      3.0
 */
class XLite_Module_DrupalConnector_Core_Converter extends XLite_Core_Converter implements XLite_Base_IDecorator, XLite_Base_ISingleton
{
    /**
     * It's the the root part of Drupal nodes which are the imported LiteCommerce widgets
     */
    const DRUPAL_ROOT_NODE = 'store';


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
     * Compose URL from target, action and additional params
     *
     * @param string $target    page identifier
     * @param string $action    action to perform
     * @param array  $params    additional params
     * @param string $interface Interface script
     *
     * @return string
     * @access public
     * @since  3.0
     */
    public static function buildURL($target = '', $action = '', array $params = array(), $interface = null)
    {
        if ($target == '') {
            $target = XLite::TARGET_DEFAULT;
        }

        if (!XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS()) {

            // Standalone URL
            $result = parent::buildURL($target, $action, $params, $interface);

        } elseif (XLite_Module_DrupalConnector_Handler::getInstance()->isPortal($target)) {

            // Drupal URL (portal)
            $result = XLite_Module_DrupalConnector_Handler::getInstance()->getPortalPrefix($target, $action, $params);

            if ($params) {
                $result .= '/' . XLite_Core_Converter::buildQuery($params, '-', '/');
            }

            $result = url($result);

        } else {

            // Drupal URL
    	    $result = self::buildDrupalURL($target, $action, $params);

        }

        return $result;
    }

    /**
     * Build Drupal path string
     * 
     * @param string $target Target
     * @param string $action Action
     * @param array  $params Parameters list
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function buildDrupalPath($target = '', $action = '', array $params = array())
    {
        $parts = array(self::DRUPAL_ROOT_NODE, $target, $action);

        if (isset($params['printable']) && $params['printable']) {
            array_unshift($parts, 'print');
            unset($params['printable']);
        }

        $url = implode('/', $parts);

        if ($params) {
            $url .= '/' . XLite_Core_Converter::buildQuery($params, '-', '/');
        }

        return $url;
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
    public static function buildDrupalURL($target = '', $action = '', array $params = array())
    {
        return url(self::buildDrupalPath($target, $action, $params));
    }
}
