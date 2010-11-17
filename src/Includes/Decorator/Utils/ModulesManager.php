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

// FIXME - must be moved into the class
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
    const CLASS_NAME_PATTERN = '/\\\XLite\\\Module\\\(\w+)(\\\|$)/USs';


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
            'SELECT name, ' . static::getTableName() . '.* FROM ' . static::getTableName() . ' WHERE enabled = \'1\'',
            \PDO::FETCH_ASSOC | \PDO::FETCH_GROUP | \PDO::FETCH_UNIQUE
        );
    }

    /**
     * Return list of modules whitch are not allowed to be enbled at one time
     * FIXME: needs re-factoring, mutual modules records no longer stored in the DB
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getMutualModules()
    {
        $result = array();

        foreach (static::$activeModules as $module) {
            if (!empty($module['mutual_modules'])) {
                $result = array_merge($result, explode(',', $module['mutual_modules']));
            }
        }

        return array_unique($result);
    }

    /**
     * Check dependencies and disable so called "mutual" modules:
     * modules which are not be enabled simultaneously
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function disableMutualModules()
    {
        if ($modules = static::getMutualModules()) {
            static::$activeModules = \Includes\Utils\ArrayManager::filterArrayByKeys(static::$activeModules, $modules);

            \Includes\Utils\Database::execute(
                'UPDATE xlite_modules SET enabled = \'0\' WHERE name IN '
                . '(' . implode(',', array_fill(0, count($modules), '?')) . ')',
                $modules
            );
        }
    }

    /**
     * Return list of <module_name> => <dependend_module_1>, <dependend_module_2>, ..., <dependend_module_N>
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getModuleDependencies()
    {
        // TODO: check the top-level methods
    }

    /**
     * Return pattern to file path againist active modules list
     * 
     * @param string $rootPath name of the root directory
     * @param string $dir      name of the directory with modules
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getPathPattern($rootPath, $dir)
    {
        $modulePattern = $dir . LC_DS_QUOTED . ($placeholder = '@') . LC_DS_OPTIONAL;

        return '/^' . $rootPath . '(.((?!' . str_replace($placeholder, '\w+', $modulePattern) . ')|'
            . str_replace($placeholder, '(' . implode('|', array_keys(static::getActiveModules())) . ')', $modulePattern)
            . '))*$/i';
    }


    /**
     * Get class name by module name
     *
     * @param string $moduleName module name to use
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
        return (preg_match(self::CLASS_NAME_PATTERN, $className, $matches) && 'AModule' !== $matches[1]) ? $matches[1] : null;
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
            static::disableMutualModules();
        }

        return isset($moduleName) ? isset(static::$activeModules[$moduleName]) : static::$activeModules;
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
        return !isset($moduleName) || static::getActiveModules($moduleName);
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
        return static::getPathPattern(preg_quote(LC_CLASSES_DIR, '/') . '\w+', 'Module');
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
        return static::getPathPattern(preg_quote(LC_SKINS_DIR, '/') . '\w+' . LC_DS_QUOTED . '\w+', 'modules');
    }
}
