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
 * @subpackage Includes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace Includes;

/**
 * Autoloader
 * NOTE - this class is abstract due to prevent its instantiation
 *
 * @package    XLite
 * @see        ____class_see____
 * @since      1.0.0
 */
abstract class Autoloader
{
    /**
     * List of registered autoload functions
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected static $functions = array(
        '__lc_autoload',
        '__lc_autoload_includes',
    );


    /**
     * The directory where LC classes are located
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected static $lcAutoloadDir = LC_DIR_CACHE_CLASSES;

    /**
     * Register the autoload function for the Doctrine library
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function registerDoctrineAutoloader()
    {
        require_once (LC_DIR_LIB . 'Doctrine' . LC_DS . 'Common' . LC_DS . 'ClassLoader.php');

        $loader = new \Doctrine\Common\ClassLoader('Doctrine', rtrim(LC_DIR_LIB, LC_DS));
        $loader->register();

        $loader = new \Doctrine\Common\ClassLoader('Symfony', rtrim(LC_DIR_LIB, LC_DS));
        $loader->register();
    }

    /**
     * Autoloader for PEAR2
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function registerPEARAutolader()
    {
        require_once (LC_DIR_LIB . 'PEAR2' . LC_DS . 'Autoload.php');
    }

    /**
     * Common autoloader
     *
     * @param string $namespace namespace to check
     * @param string $class     class to load
     * @param string $dir       path to the PHP files
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function autoloadCommon($namespace, $class, $dir)
    {
        /**
         * NOTE: it's the PHP bug: in some cases it adds or removes the leading slash. Examples:
         *
         * 1. For static call "\Includes\Decorator\Utils\CacheManager::rebuildCache()" it will remove
         * the leading slash, and class name passed in this function will be "Includes\Decorator\Utils\CacheManager".
         *
         * 2. Pass class name as a string into the functions, e.g.
         * "is_subclass_of($object, '\Includes\Decorator\Utils\CacheManager')". Then the class
         * name will be passed into the autoloader with the leading slash - "\Includes\Decorator\Utils\CacheManager"
         *
         * Remove the "ltrim()" call when this issue will be resolved
         *
         * May be that issue is related: http://bugs.php.net/50731
         */
        if (0 === strpos($class = ltrim($class, '\\'), $namespace)) {
            include_once ($dir . str_replace('\\', LC_DS, $class) . '.php');
        }
    }


    /**
     * Main LC autoloader
     *
     * @param string $class name of the class to load
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function __lc_autoload($class)
    {
        self::autoloadCommon(LC_NAMESPACE, $class, static::$lcAutoloadDir);
    }

    /**
     * Autoloader for the "includes"
     *
     * @param string $class name of the class to load
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function __lc_autoload_includes($class)
    {
        self::autoloadCommon(LC_NAMESPACE_INCLUDES, $class, LC_DIR_ROOT);
    }

    /**
     * Add an autoload function to the list
     *
     * @param string $method function name
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function addFunction($method)
    {
        if (false !== array_search($method, static::$functions)) {
            throw new \Exception('Autoload function "' . $method . '" is already registered');
        }

        static::$functions[] = $method;

        spl_autoload_register(array('static', $method));
    }

    /**
     * Register autoload functions
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function registerAll()
    {
        foreach (static::$functions as $method) {
            spl_autoload_register(array('static', $method));
        }

        // Doctrine
        static::registerDoctrineAutoloader();

        // PEAR2
        static::registerPEARAutolader();
    }

    /**
     * Return path ot the autoloader current dir
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getLCAutoloadDir()
    {
        return static::$lcAutoloadDir;
    }

    /**
     * Switch autoload directory from var/run/classes/ to classes/
     *
     * @param string $dir New autoload directory
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function switchLCAutoloadDir()
    {
        static::$lcAutoloadDir = LC_DIR_CLASSES;
    }
}
