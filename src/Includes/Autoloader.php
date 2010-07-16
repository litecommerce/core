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

namespace Includes;

/**
 * Autoloader 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @see        ____class_see____
 * @since      3.0.0
 */
class Autoloader
{
    /**
     * List of registerd autoload functions 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $functions = array(
        '__lc_autoload',
        '__lc_autoload_includes',
    );


    /**
     * Register the autoload function for the Doctrine library
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function registerDoctrineAutoloader()
    {
        require_once (LC_LIB_DIR . 'Doctrine' . LC_DS . 'Common' . LC_DS . 'ClassLoader.php');

        $loader = new \Doctrine\Common\ClassLoader('Doctrine', LC_LIB_DIR);
        $loader->register();
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
     * @since  3.0.0
     */
    protected static function autoloadCommon($namespace, $class, $dir)
    {
        if (0 === strpos($class = ltrim($class, '\\'), $namespace)) {
            require_once ($dir . str_replace('\\', LC_DS, $class) . '.php');
        }
    }


    /**
     * Add an autoload function to the list
     * 
     * @param string $method function name
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function register($method)
    {
        if (false !== array_search($method, static::$functions[$method])) {
            throw new Exception('Autoload function "' . $method . '" is already registered');
        }

        static::$functions[] = $method;
    }

    /**
     * Main LC autoloader
     * 
     * @param string $class name of the class to load
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function __lc_autoload($class)
    {
        self::autoloadCommon('XLite', $class, LC_AUTOLOAD_DIR);
    }

    /**
     * Autoloader for the "includes"
     * 
     * @param string $class name of the class to load
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function __lc_autoload_includes($class)
    {
        self::autoloadCommon('Includes', $class, LC_INCLUDES_DIR);
    }

    /**
     * Register autoload functions
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function __constructStatic()
    {
        foreach (static::$functions as $method) {
            spl_autoload_register(array('static', $method));
        }

        static::registerDoctrineAutoloader();
    }
}

// Register functions
Autoloader::__constructStatic();

