<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Abstract factory
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */


/**
 * Abstract factory 
 * 
 * @package    Lite Commerce
 * @subpackage Model
 * @since      3.0.0
 */
class XLite_Model_Factory extends XLite_Base implements XLite_Base_ISingleton
{
	/**
     * It's not possible to instantiate this class using the "new" operator
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
	protected function __construct()
	{
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
     * Create object instance 
     * 
     * @param string $name class name
     *  
     * @return XLite_Base
     * @access public
     * @since  3.0.0
     */
    public function __get($name)
    {
        return self::create($name);
    }

    /**
     * Create object instance 
     * 
     * @param string $name class name
     *  
     * @return XLite_Base
     * @access public
     * @since  3.0.0
     */
    public static function create($name)
    {
        return new $name();
    }

    /**
     * Create object instance and pass arguments to it contructor (if needed)
     * 
     * @param mixed $class class name
     * @param array $args  constructor arguments
     *  
     * @return XLite_Base
     * @access public
     * @since  3.0.0
     */
    public static function createObjectInstance($class, array $args = array())
    {
        $handler = new ReflectionClass($class);

        return $handler->hasMethod('__construct') ? $handler->newInstanceArgs($args) : $handler->newInstance();
    }
}

