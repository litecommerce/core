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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Abstract caching factory 
 * 
 * @package    Lite Commerce
 * @subpackage Model
 * @since      3.0.0
 */
class XLite_Model_CachingFactory extends XLite_Model_Factory implements XLite_Base_ISingleton
{
    /**
     * Objects cache 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected static $cache = array();

    
    /**
     * Get handler object (or pseudo-constant)
     * 
     * @param mixed $handler variable to prepare
     *  
     * @return mixed
     * @access protected
     * @since  3.0.0
     */
    protected static function prepareHandler($handler)
    {
       return is_object($handler) ? $handler : (in_array($handler, array('self', 'parent')) ? $handler : new $handler());
    }

    /**
     * Return object instance
     *
     * @return XLite_Model_Session
     * @access public
     * @since  3.0.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
     * Cache and return a result of object method call 
     * 
     * @param string  $signature result key in cache
     * @param mixed   $handler   callback object
     * @param string  $method    method to call
     * @param array   $args      callback arguments
     * @param boolean $earCache  Clear cache flag
     *  
     * @return mixed
     * @access public
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
     * @param string $signature result key in cache
     * @param string $class     object class name
     * @param array  $args      constructor arguments
     *  
     * @return XLite_Base
     * @access public
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function clearCacheCell($signature)
    {
        if (isset(self::$cache[$signature])) {
            unset(self::$cache[$signature]);
        }
    }

    /**
     * Clean up cache
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __destruct()
    {
        self::$objectsCache = null;
    }
}

