<?php

abstract class XLite_Base_Singleton extends XLite_Base_Abstract
{
	/**
     * Array of instances for all derived classes
     *
     * @var    array
     * @access private
     * @see    ____var_see____
     * @since  3.0
     */
    private static $instances = array();


    /**
     * It's not possible to call this function for the singleton object
     *
     * @return void
     * @access private
     * @see    ____func_see____
     * @since  3.0
     */
    private function __clone()
    {
    }

    /**
     * It's not possible to call this function for the singleton object
     *
     * @return void
     * @access private
     * @see    ____func_see____
     * @since  3.0
     */
    private function __sleep()
    {
    }

    /**
     * It's not possible to call this function for the singleton object
     *
     * @return void
     * @access private
     * @see    ____func_see____
     * @since  3.0
     */
    private function __wakeup()
    {
    }

	/**
     * Protected constructor.
     * It's not possible to instantiate this object using the "new" operator
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0
     */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if singleton of current type is already instantiated
     *
	 * @param string $className name of derived class
	 *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0
     */
    protected static function isInstantiated($className)
    {
        return isset(self::$instances[$className]);
    }

    /**
     * Return pointer to the single instance of current class 
     * 
     * @param string $className name of derived class
     *  
     * @return XLite_Base_Singleton
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public static function getInstance($className = __CLASS__)
    {
        // Create new instance of the object (if it is not already created)
        if (!self::isInstantiated($className)) {
            self::$instances[$className] = new $className();
        }

        return self::$instances[$className];
    }
}

