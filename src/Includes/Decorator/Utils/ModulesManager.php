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
 * Some useful constants 
 */
define('LC_DS_QUOTED', preg_quote(LC_DS, '/'));
define('LC_DS_OPTIONAL', '(' . LC_DS_QUOTED . '|$)');

/**
 * ModulesManager 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class ModulesManager extends AUtils
{
    /**
     * Pattern to get module name by class name
     */
    const CLASS_NAME_PATTERN = '/\\\XLite\\\Module\\\(\w+\\\\\w+)(\\\|$)/USs';


    /**
     * List of active modules
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $activeModules;


    /**
     * Return name of the table where the module info is stored 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getTableName()
    {
        return 'xlite_modules';
    }

    /**
     * Part of SQL query to fetch composed module name
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getModuleNameField()
    {
        return 'CONCAT(author,\'\\\\\',name) AS ' . \Includes\Decorator\DataStructure\Node\Module::ACTUAL_NAME . ', ';
    }

    /**
     * Fetch list of active modules from DB
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getModulesList(array $fields = array(), array $conditions = array())
    {
        return \Includes\Utils\Database::fetchAll(
            'SELECT ' . static::getModuleNameField() . static::getModuleNameField()
            . static::getTableName() . '.* FROM ' . static::getTableName() . ' WHERE enabled = \'1\'',
            \PDO::FETCH_ASSOC | \PDO::FETCH_GROUP | \PDO::FETCH_UNIQUE
        );
    }

    /**
     * Return list of relative module paths
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getModuleQuotedPaths()
    {
        return array_map(
            function ($name) {
                return str_replace('\\', LC_DS_QUOTED, $name);
            },
            array_keys(static::getModulesGraph()->getIndex())
        );
    }

    /**
     * Return pattern to file path againist active modules list
     * 
     * @param string $rootPath  name of the root directory
     * @param string $dir       name of the directory with modules
     * @param string $extension file extension
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getPathPattern($rootPath, $dir, $extension)
    {
        $modulePattern = $dir . LC_DS_QUOTED . ($placeholder = '@') . LC_DS_OPTIONAL;

        return '/^' . $rootPath . '(.((?!' . str_replace($placeholder, '\w+', $modulePattern) . ')|'
            . str_replace($placeholder, '(' . implode('|', static::getModuleQuotedPaths()) . ')', $modulePattern) 
            . '))*\.' . $extension . '$/i';
    }


    /**
     * Get class name by module name
     *
     * @param string $moduleName module actual name
     *
     * @return string
     * @access public
     * @since  3.0
     */
    public static function getClassNameByModuleName($moduleName)
    {
        return '\XLite\Module\\' . $moduleName . '\Main';
    }

    /**
     * Retrieve module name from class name
     *
     * @param string $className class name to parse
     *
     * @return string|null
     * @access public
     * @since  3.0
     */
    public static function getModuleNameByClassName($className)
    {
        return preg_match(self::CLASS_NAME_PATTERN, $className, $matches) ? $matches[1] : null;
    }

    /**
     * Return list of active modules (or check a single module)
     * 
     * @param string|null $moduleName module name
     *  
     * @return array|bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getActiveModules($moduleName = null)
    {
        if (!isset(static::$activeModules)) {
            static::$activeModules = static::getModulesList();
        }

        return isset($moduleName) ? @static::$activeModules[$moduleName] : static::$activeModules;
    }

    /**
     * Check if module is active
     * 
     * @param string|null $moduleName module name
     *  
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isActiveModule($moduleName)
    {
        return !isset($moduleName) || (bool) static::getActiveModules($moduleName);
    }

    /**
     * Set module enabled fleg fo "false"
     *
     * @param string $key module actual name (key)
     * 
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function disableModule($key)
    {
        // Check if module exists and enabled
        if ($module = static::getActiveModules($key)) {

            // Set flag in DB
            \Includes\Utils\Database::execute(
                'UPDATE ' . static::getTableName() . ' SET enabled = ? WHERE module_id = ?',
                array(0, $module['module_id'])
            );

            // Remove from local cache
            static::$activeModules[$key];
        }
    }

    /**
     * Return pattern to check PHP file paths
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getPathPatternForPHP()
    {
        return static::getPathPattern(preg_quote(LC_CLASSES_DIR, '/') . '\w+', 'Module', 'php');
    }

    /**
     * Return pattern to check .tpl file paths
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getPathPatternForTemplates()
    {
        return static::getPathPattern(preg_quote(LC_SKINS_DIR, '/') . '\w+' . LC_DS_QUOTED . '\w+', 'modules', 'tpl');
    }

    /**
     * Compose module actual name
     * 
     * @param string $author module author
     * @param string $name   module name
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getActualName($author, $name)
    {
        return $author . '\\' . $name;
    }

    /**
     * Get name of dependent module by author and name
     * 
     * @param array $dependency dependency description
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function composeDependency(array $dependency)
    {
        return call_user_func_array(array('static', 'getActualName'), $dependency);
    }

    /**
     * Return list of actually enabled modules
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getActuallyEnabledModules()
    {
        return array_keys(static::getModulesGraph()->getIndex());
    }
}
