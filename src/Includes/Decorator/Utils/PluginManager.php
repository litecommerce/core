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
 *
 * Available hooks:
 *
 * - required:
 * --- void run()
 *
 * - optional:
 * --- array prepareTags(array &$result, array $matches)
 *
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
     * Sections in the ".ini" file 
     */

    const SECTION_PLUGINS = 'plugins_list';

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
     * Cache of the plugin instances
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $instancesCache = array();


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
        return '\Includes\Decorator\Plugin\\' . $plugin . '\Main';
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
        if (empty($data[self::SECTION_PLUGINS])) {
            throw new \Exception('There is no section "' . self::SECTION_PLUGINS . '" in the "' . self::FILE_INI . '" file');
        }
    }

    /**
     * Return list of registered plugins
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getPlugins()
    {
        if (!isset(static::$plugins)) {

            // Check config file
            if (\Includes\Utils\FileManager::isFileReadable(static::getConfigFile())) {

                // Read and check retrieved data
                $data = static::parseConfigFile();
                static::checkConfigData($data);

                // Save plugins list
                static::$plugins = array_keys($data[self::SECTION_PLUGINS], self::STATUS_ON);

            } else {

                throw new \Exception('Unable to read config file for the Decorator plugins');
            }
        }

        return static::$plugins;
    }

    /**
     * Return instance of a plugin
     * 
     * @param string $plugin plugin name
     *  
     * @return \Includes\Decorator\Plugin\APlugin
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getPluginInstance($plugin)
    {
        if (!isset(static::$instancesCache[$plugin])) {

            if (!is_subclass_of(static::getPluginClass($plugin), self::CLASS_BASE)) {
                throw new \Exception('Plugin "' . $plugin . '" does not extend the "' . self::CLASS_BASE . '" class');
            }

            static::$instancesCache[$plugin] = call_user_func(array(static::getPluginClass($plugin), 'getInstance'));
        }

        return static::$instancesCache[$plugin];
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
     * Check if plugin has a certain hook handler
     * 
     * @param string $plugin plugin name
     * @param string $hook   hook name
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function checkPluginHook($plugin, $hook)
    {
        return method_exists($this->getPluginInstance($plugin), $this->getHookHandlerName($hook));
    }

    /**
     * Run the corresponded hook handler
     *
     * @param string $plugin plugin name
     * @param string $hook   hook name
     * @param array  $args   handler call arguments  
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function executeHookHandler($plugin, $hook, array $args = array())
    {
        return call_user_func_array(array($this->getPluginInstance($plugin), $this->getHookHandlerName($hook)), $args);
    }


    /**
     * Check and execute hook handlers
     * 
     * @param string $hook hook name
     * @param array  $args arguments of the hook handler call
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function invokeHook($hook, array $args = array())
    {
        foreach (static::getPlugins() as $plugin) {
            !static::checkPluginHook($plugin, $hook) ?: static::executeHookHandler($plugin, $hook, $args);
        }
    }
}
