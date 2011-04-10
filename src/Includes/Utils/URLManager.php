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
 * URLManager 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class URLManager extends \Includes\Utils\AUtils
{
    /**
     * URL output type codes
     */

    const URL_OUTPUT_SHORT = 'short';
    const URL_OUTPUT_FULL  = 'full';


    /**
     * Remove trailing slashes from URL 
     * 
     * @param string $url URL to prepare
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function trimTrailingSlashes($url)
    {
        return \Includes\Utils\Converter::trimTrailingChars($url, '/');
    }

    /**
     * Return full URL for the resource
     * 
     * @param string $url      URL part to add OPTIONAL
     * @param bool   $isSecure Use HTTP or HTTPS OPTIONAL
     * @param array  $params   URL parameters OPTIONAL
     * @param string $output   URL output type OPTIONAL
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getShopURL(
        $url = '',
        $isSecure = false,
        array $params = array(),
        $output = self::URL_OUTPUT_FULL
    ) {
        $hostDetails = \Includes\Utils\ConfigParser::getOptions('host_details');

        $host = $hostDetails['http' . ($isSecure ? 's' : '') . '_host'];
        if ($host) {

            $proto = ($isSecure ? 'https' : 'http') . '://';

            if ('/' != substr($url, 0, 1)) {
                $url = $hostDetails['web_dir_wo_slash'] . '/' . $url;
            }

            if ($isSecure) {
                $session = \XLite\Core\Session::getInstance();
                $url .= (false !== strpos($url, '?') ? '&' : '?') . $session->getName() . '=' . $session->getID();
            }

            foreach ($params as $name => $value) {
                $url .= (false !== strpos($url, '?') ? '&' : '?') . $name . '=' . $value;
            }

            if (self::URL_OUTPUT_FULL == $output) {
                $url = $proto . $host . $url;
            }
        }

        return $url;
    }

    /**
     * Return current URI
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getSelfURI()
    {
        return isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
    }

    /**
     * Return current URL
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getCurrentURL()
    {
        return (\XLite\Core\Request::getInstance()->isHTTPS() ? 'https' : 'http')
            . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
}
