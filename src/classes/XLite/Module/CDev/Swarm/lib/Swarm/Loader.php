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

namespace Swarm;

require_once __DIR__ . '/ASwarm.php';

/**
 * Library loader 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Loader extends \Swarm\ASwarm
{
    /**
     * Register autoloader 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function registerAutoloader()
    {
        return spl_autoload_register(
            array(get_called_class(), 'load')
        );
    }

    /**
     * Load class
     * 
     * @param string $name Class name
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function load($name)
    {
        if ('Swarm' == substr($name, 0, 5)) {
            require_once __DIR__ . str_replace('\\', DIRECTORY_SEPARATOR, substr($name, 5)) . '.php';
        }
    }
}

Loader::registerAutoloader();
