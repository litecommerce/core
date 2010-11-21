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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Decorator\Utils;

/**
 * Plugins manager 
 *
 * Available hooks:
 * - run()
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class PluginManager extends \Includes\Decorator\Utils\AUtils
{
    /**
     * Config file name 
     */

    const FILE_INI = 'plugins.ini';

    /**
     * Available status values
     */

    const STATUS_ON  = 'On';
    const STATUS_OFF = 'Off';

    /**
     * Name of the plugins common class
     */

    const CLASS_BASE = '\Includes\Decorator\Plugin\APlugin';


    /**
     * List of registered plugins 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $plugins;


    /**
     * Return configuration file
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getConfigFile()
    {
        return LC_INCLUDES_DIR . 'Decorator' . LC_DS . self::FILE_INI;
    }

    /**
     * Return name of the plugin class
     *
     * @param string $plugin plugin name
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getPluginClass($plugin)
    {
        return '\Includes\Decorator\Plugin\\' . str_replace('_', '\\', $plugin) . '\Main';
    }

    /**
     * Parse the INI file
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function parseConfigFile()
    {
        return parse_ini_file(static::getConfigFile(), true);
    }

    /**
     * Check data from the config file
     * 
     * @param array $data data returned by the "parseConfigFile()" method
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function checkConfigData(array $data)
    {
        // TODO: add check (if needed)
    }

    /**
     * Return list of registered plugins
     * 
     * @param string $hook hook name (optional)
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getPlugins($hook = null)
    {
        if (!isset(static::$plugins)) {

            // Check config file
            if (\Includes\Utils\FileManager::isFileReadable(static::getConfigFile())) {

                // Read and check retrieved data
                $data = static::parseConfigFile();
                static::checkConfigData($data);

                // Iterate over all sections
                foreach ($data as $section => $plugins) {

                    // Set plugins order
                    $plugins = array_filter($plugins);
                    asort($plugins, SORT_NUMERIC);

                    // Save plugins list
                    static::$plugins[$section] = array_fill_keys(array_keys($plugins), null);
                }

            } else {

                throw new \Exception('Unable to read config file for the Decorator plugins');
            }
        }

        return empty($hook) ? static::$plugins : static::$plugins[$hook];
    }

    /**
     * Return instance of a plugin
     * 
     * @param string $name plugin name
     *  
     * @return \Includes\Decorator\Plugin\APlugin
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getPluginInstance($name)
    {
        if (!isset(static::$plugins[$name])) {

            if (!is_subclass_of($class = static::getPluginClass($name), self::CLASS_BASE)) {
                throw new \Exception('Plugin "' . $name . '" does not extend the "' . self::CLASS_BASE . '" class');
            }

            static::$plugins[$name] = \Includes\Pattern\Factory::create($class);
        }

        return static::$plugins[$name];
    }

    /**
     * Compose hook handler method name
     * 
     * @param string $hook hook name
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getHookHandlerName($hook)
    {
        return 'executeHookHandler' . ucfirst($hook);
    }

    /**
     * Run the corresponded hook handler
     *
     * @param string $plugin plugin name
     * @param string $hook   hook name
     * @param array  &$args  handler call arguments
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function executeHookHandler($plugin, $hook, array $args = array())
    {
        return call_user_func_array(array(static::getPluginInstance($plugin), static::getHookHandlerName($hook)), $args);
    }


    /**
     * Check and execute hook handlers
     * 
     * @param string $hook  hook name
     * @param array  &$args arguments of the hook handler call
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function invokeHook($hook, array $args = array())
    {
        foreach (static::getPlugins($hook) as $plugin => $instance) {
            static::executeHookHandler($plugin, $hook, $args);
        }
    }
}
