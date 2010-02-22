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
class XLite_Model_CachingFactory extends XLite_Base implements XLite_Base_ISingleton
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
     * It's not possible to instantiate this class using the "new" operator
     *
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
	protected function __construct()
	{
	}

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

        list($isCached, $key) = self::checkIfObjectCached($class, $args);

        if (!$isCached) {

            if (!isset(self::$cache[$class])) { 
                self::$cache[$class] = array();
            }

            $reflection = new ReflectionClass($class);
            self::$cache[$class][$key] = $reflection->hasMethod('__construct') ?
                    $reflection->newInstanceArgs($args) : $reflection->newInstance();
        }

        return self::$cache[$class][$key];
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

