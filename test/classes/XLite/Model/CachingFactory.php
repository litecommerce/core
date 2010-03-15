<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Abstract caching factory 
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0 EE
 */


/**
 * Abstract caching factory 
 * 
 * @package    Lite Commerce
 * @subpackage Model
 * @since      3.0.0 EE
 */
class XLite_Model_CachingFactory extends XLite_Model_Factory implements XLite_Base_ISingleton
{
    /**
     * Objects cache 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected static $cache = array();


    /**
     * Return unique key for the <class,primary_keys> pair
     * 
     * @param array $args class constructor arguments
     *  
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected static function generateCacheEntryKey(array $args)
    {
        return md5(serialize($args));
    }

    /**
     * Check if object is already objectsCached 
     * 
     * @param string $class class name
     * @param array  $args  constructor arguments
     *  
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    protected static function checkIfObjectCached($class, array $args)
    {
        return array(isset(self::$cache[$class][$key = self::generateCacheEntryKey($args)]), $key);
    }

    /**
     * Create object or fetch if from the cache
     * 
     * @param string $class   object clas name
     * @param mixed  $handler class name or object to use in callback
     * @param string $method  callback method
     * @param array  $args    callback params
     *  
     * @return XLite_Base
     * @access protected
     * @since  3.0.0
     */
    protected static function handleObject($class, $handler, $method, array $args = array())
    {
        list($isCached, $key) = self::checkIfObjectCached($class, array($method, $args));

        if (!$isCached) {

            if (!isset(self::$cache[$class])) {
                self::$cache[$class] = array();
            }

            self::$cache[$class][$key] = call_user_func_array(array($handler, $method), $args);
        }

        return self::$cache[$class][$key];
    }

    /**
     * Return object instance
     *
     * @return XLite_Model_Session
     * @access public
     * @since  3.0.0 EE
     */
    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }

    /**
     * Create object instance or fetch it from the cache
     * 
     * @param string $class class name
     *  
     * @return XLite_Base
     * @access public
     * @since  3.0.0 EE
     */
    public static function getObject($class)
    {
        $args = func_get_args();
        array_shift($args);

        return self::handleObject($class, 'self', 'createObjectInstance', array($class, $args));
    }

    /**
     * Get object instance using the callback and save the result in the cache 
     * 
     * @param string     $class  class name
     * @param XLite_Base $object object to use in callback
     * @param string     $method callback method
     *  
     * @return XLite_Base
     * @access public
     * @since  3.0.0
     */
    public static function getObjectFromCallback($class, $object, $method)
    {
        $args = func_get_args();
        $args = array_slice($args, 3);

        return self::handleObject($class, $object, $method, $args);
    }

    /**
     * Clean up cache
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function __destruct()
    {
        self::$objectsCache = null;
    }
}

