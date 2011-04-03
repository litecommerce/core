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

namespace XLite\Model;

/**
 * Abstract caching factory 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class CachingFactory extends \XLite\Model\Factory
{
    /**
     * Objects cache 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected static $cache = array();

    
    /**
     * Cache and return a result of object method call 
     * 
     * @param string  $signature  Result key in cache
     * @param mixed   $handler    Callback object
     * @param string  $method     Method to call
     * @param array   $args       Callback arguments OPTIONAL
     * @param boolean $clearCache Clear cache flag OPTIONAL
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getObjectFromCallback($signature, $handler, $method, array $args = array(), $clearCache = false)
    {
        if (!isset(self::$cache[$signature]) || $clearCache) {
            self::$cache[$signature] = call_user_func_array(array(self::prepareHandler($handler), $method), $args);
        }

        return self::$cache[$signature];
    }

    /**
     * cache and return object instance 
     * 
     * @param string $signature Result key in cache
     * @param string $class     Object class name
     * @param array  $args      Constructor arguments OPTIONAL
     *  
     * @return \XLite\Base
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getObject($signature, $class, array $args = array())
    {
        return self::getObjectFromCallback($signature, 'self', 'create', array($class, $args));
    }

    /**
     * Clear cache cell 
     * 
     * @param string $signature Cache cell key
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function clearCacheCell($signature)
    {
        unset(self::$cache[$signature]);
    }

    /**
     * Clear cache 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function clearCache()
    {
        self::$cache = null;
    }


    /**
     * Get handler object (or pseudo-constant)
     * 
     * @param mixed $handler Variable to prepare
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function prepareHandler($handler)
    {
        return (!is_object($handler) && !in_array($handler, array('self', 'parent', 'static')))
            ? new $handler()
            : $handler;
    }


    /**
     * Clean up cache
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __destruct()
    {
        self::clearCache();
    }
}
