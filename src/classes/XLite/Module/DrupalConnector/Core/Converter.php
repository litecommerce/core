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

namespace XLite\Module\DrupalConnector\Core;

// FIXME - must be refactored

/**
 * Miscelaneous convertion routines
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Converter extends \XLite\Core\Converter implements \XLite\Base\IDecorator
{
    /**
     * It's the the root part of Drupal nodes which are the imported LiteCommerce widgets
     */
    const DRUPAL_ROOT_NODE = 'store';


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
            $target = \XLite::TARGET_DEFAULT;
        }

        if (!\XLite\Module\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {

            // Standalone URL
            $result = parent::buildURL($target, $action, $params, $interface);

        } elseif (\XLite\Module\DrupalConnector\Handler::getInstance()->isPortal($target)) {

            // Drupal URL (portal)
            $result = \XLite\Module\DrupalConnector\Handler::getInstance()->getPortalPrefix($target, $action, $params);

            if ($params) {
                $result .= '/' . \XLite\Core\Converter::buildQuery($params, '-', '/');
            }

            $result = self::normalizeDrupalURL($result);

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
            $url .= '/' . \XLite\Core\Converter::buildQuery($params, '-', '/');
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
        return self::normalizeDrupalURL(self::buildDrupalPath($target, $action, $params));
    }

    /**
     * Normalize Drupal URL to full path
     * 
     * @param string $url Short URL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function normalizeDrupalURL($url)
    {
        return preg_replace(
            '/(\/)\%252F([^\/])/iSs',
            '\1/\2',
            url($url)
        );
    }
}
