<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * PHP version 5.3.0
 * 
 * @author    ____author____ 
 * @copyright ____copyright____
 * @license   ____license____
 * @link      https://github.com/max-shamaev/swarm
 * @since     1.0.0
 */

namespace Swarm\Base;

/**
 * Singleton 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Singleton extends \Swarm\ASwarm
{
    /**
     * Instances collection
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $instances = array();

    /**
     * Get instance 
     * 
     * @return \ASwarm
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getInstance()
    {
        $className = get_called_class();

        // Create new instance of the object (if it is not already created)
        if (!isset(static::$instances[$className])) {
            static::$instances[$className] = new $className();
        }

        return static::$instances[$className];
    }

    /**
     * Constructor
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function __construct()
    {
    }
}

