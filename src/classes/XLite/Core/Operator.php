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

namespace XLite\Core;

/**
 * Common operations repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Operator extends \XLite\Base implements \XLite\Base\ISingleton
{
    /**
     * Check if we need to perform a redirect or not 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected static function checkRedirectStatus()
    {
        return !\XLite\Core\CMSConnector::isCMSStarted() 
            || !\XLite\Core\Request::getInstance()->__get(\XLite\Core\CMSConnector::NO_REDIRECT);
    }

    /**
     * setHeaderLocation
     * 
     * @param string $location URL
     * @param int    $code     operation code
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected static function setHeaderLocation($location, $code = 302)
    {
        header('Location: ' . $location, true, $code);
    }

    /**
     * finish 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected static function finish()
    {
        exit (0);
    }


    /**
     * Redirect 
     * 
     * @param string $location URL
     * @param bool   $force    check or not redirect conditions
     * @param int    $code     operation code
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function redirect($location, $force = false, $code = 302)
    {
        if (static::checkRedirectStatus() || $force) {
            static::setHeaderLocation($location, $code);
            static::finish();
        }
    }

    /**
     * Check if class exists 
     * 
     * @param string $name name of class to check
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isClassExists($name)
    {
        return class_exists($name, false) || file_exists(LC_CLASSES_CACHE_DIR . str_replace('\\', LC_DS, $name) . '.php');
    }

    /**
     * Get URL content 
     * 
     * @param string $url URL
     *  
     * @return string or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getURLContent($url)
    {
        $result = null;

        if (ini_get('allow_url_fopen')) {
            $result = file_get_contents($url);

        } else {
            $bouncer = new \XLite\Model\HTTPS();
            $bouncer->url = $url;
            $bouncer->method = 'GET';
            if (\XLite\Model\HTTPS::HTTPS_SUCCESS == $bouncer->request()) {
                $result = $bouncer->response;
            }
        }

        return $result;
    }

    /**
     * Calculate pagination info
     * 
     * @param integer $count Items count
     * @param integer $page  Current page index
     * @param integer $limit Page length limit
     *  
     * @return array (pages count + current page number)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function calculatePagination($count, $page = 1, $limit = 20)
    {
        $count = max(0, intval($count));
        $limit = max(0, intval($limit));

        if (0 == $limit && $count) {
            $pages = 1;

        } else {
            $pages = 0 == $count ? 0 : ceil($count / $limit);
        }

        $page = min($pages, max(1, intval($page)));

        return array($pages, $page);
    }
}

