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
 * @version    GIT: $Id$
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
 * :FIXME: must be completely refactored
 * :TODO:  move it into the Includes/Utils
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
abstract class ModulesManager extends AUtils
{
    /**
     * Pattern to get module name by class name
     */
    const CLASS_NAME_PATTERN = '/(?:\\\)?XLite\\\Module\\\(\w+\\\\\w+)(\\\|$)/USs';

    /**
     * Modules list file name
     */
    const MODULES_FILE_NAME = '.decorator.modules.ini.php';


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
     * Modules list file name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $modulesINIFile;

    /**
     * Data for class tree walker
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $quotedPaths;


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
        return 'CONCAT(author,\'\\\\\',name) AS actualName, ';
    }

    /**
     * Get modules list file path 
     * 
     * @return string|void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getModulesFilePath()
    {
        if (!isset(static::$modulesINIFile)) {

            static::$modulesINIFile = LC_VAR_DIR . static::MODULES_FILE_NAME;

            if (!\Includes\Utils\FileManager::isFileReadable(static::$modulesINIFile)) {
                static::$modulesINIFile = false;
            }
        }

        return static::$modulesINIFile;
    }

    /**
     * Fetch list of active modules from DB
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getModulesList()
    {
        $list = array();

        if (\Includes\SafeMode::isSafeModeStarted()) {

            $condition = '';

            // Get unsafe modules condition string
            if (\Includes\SafeMode::isSoftResetRequested()) {
                $condition .= ' WHERE ' . \Includes\SafeMode::getUnsafeModulesSQLConditionString();
            }

            // Auto-disable modules in the database
            \Includes\Utils\Database::execute(
                'UPDATE ' . static::getTableName() . ' SET enabled = 0' . $condition
            );
            \Includes\SafeMode::cleanupIndicator();

        } 
        
        if (
            !\Includes\SafeMode::isSafeModeStarted()
            && $path = static::getModulesFilePath()
        ) {

            $list = array();

            foreach (parse_ini_file($path, true) as $author => $authors) {
                foreach ($authors as $name => $enabled) {
                    if ($enabled) {
                        $list[$author . '\\' . $name] = array(
                            'actualName' => $author . '\\' . $name,
                            'moduleID'   => null,
                            'name'       => $name,
                            'author'     => $author,
                            'enabled'    => 1,
                            'status'     => 0,
                            'moduleName' => $name,
                            'authorName' => $author,
                        );
                    }
                }
            }

        } else {

            $list = \Includes\Utils\Database::fetchAll(
                'SELECT ' . static::getModuleNameField() . static::getModuleNameField()
                . static::getTableName() . '.* FROM ' . static::getTableName() 
                . ' WHERE enabled = ? AND installed = ?',
                array(true, true),
                \PDO::FETCH_ASSOC | \PDO::FETCH_GROUP | \PDO::FETCH_UNIQUE
            );
        }

        return $list;
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
        if (!isset(static::$quotedPaths)) {
            static::$quotedPaths = array();
            static::getModulesGraph()->walkThrough(array(get_called_class(), 'getModuleQuotedPathsCallback'));
        }

        return static::$quotedPaths;
    }

    /**
     * Callback to collect module paths
     * 
     * @param \Includes\Decorator\DataStructure\Graph\Modules $node Current module node
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getModuleQuotedPathsCallback(\Includes\Decorator\DataStructure\Graph\Modules $node)
    {
        static::$quotedPaths[$node->getActualName()] = str_replace('\\', LC_DS_QUOTED, $node->getActualName());
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
     * Disable modules with incorrect dependencies
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function correctDependencies()
    {
        $dependencies = array();

        foreach (static::$activeModules as $module => $data) {
            $dependencies = array_merge_recursive(
                $dependencies,
                array_fill_keys(static::callModuleMethod($module, 'getDependencies'), $module)
            );
        }

        foreach (array_diff_key($dependencies, array_keys(static::$activeModules)) as $module) {
            static::disableModule($module);
        }
    }

    /**
     * Disable modules with non-correct versions
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function checkVersions()
    {
        foreach (static::$activeModules as $module => $data) {
            if (\XLite::getInstance()->checkVersion(static::callModuleMethod($module, 'getMajorVersion'), '!=')) {
                static::disableModule($module);
            }
        }
    }

    /**
     * Method to access module main clas methods
     * 
     * @param string $module Module actual name
     * @param string $method Method to call
     * @param array  $args   Call arguments OPTIONAL
     *  
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function callModuleMethod($module, $method, array $args = array())
    {
        return call_user_func_array(array(static::getClassNameByModuleName($module), $method), $args);
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
            static::checkVersions();
            static::correctDependencies();
        }

        return \Includes\Utils\ArrayManager::getIndex(static::$activeModules, $moduleName);
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
     * Remove file with active modules list
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function removeFile()
    {
        \Includes\Utils\FileManager::delete(static::getModulesFilePath());
    }

    /**
     * Save modules to file 
     * 
     * @param array $modules Modules array
     *  
     * @return integer|boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function saveModulesToFile(array $modules)
    {
        $path = LC_VAR_DIR . static::MODULES_FILE_NAME;

        $string = '; <' . '?php /*' . PHP_EOL;

        $i = 0;
        foreach ($modules as $author => $names) {
            $string .= '[' . $author. ']' . PHP_EOL;
            foreach ($names as $name => $enabled) {
                $string .= $name . ' = ' . $enabled . PHP_EOL;
                $i++;
            }
        }

        $string .= '; */ ?' . '>';

        return $i ? file_put_contents($path, $string) : false;
    }

    /**
     * Set module enabled fleg fo "false"
     *
     * :TODO: simplify
     *
     * @param string $key module actual name (key)
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function disableModule($key)
    {
        if (isset(static::$activeModules[$key])) {

            // Short name
            $data = static::$activeModules[$key];

            // Set flag in .ini-file
            if ($path = static::getModulesFilePath()) {
                $pattern = '/(\[' . $data['author'] . '\][^\[]+\s*' . $data['name'] . '\s*=)\s*\S+/Ss';
                \Includes\Utils\FileManager::replace($path, '$1 0', $pattern);

            }

            // Set flag in DB
            $query = 'UPDATE ' . static::getTableName() . ' SET enabled = ? WHERE moduleID = ?';
            \Includes\Utils\Database::execute($query, array(0, $data['moduleID']));

            // Remove from local cache
            unset(static::$activeModules[$key]);
        }
    }

    /**
     * Switch on all active modules
     *
     * :TODO: try to find a more convinient way to do that; merge with the "disableModule()"
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function switchModules()
    {
        foreach (static::getActiveModules() as $module => $data) {

            // Search for modules
            $moduleID = \Includes\Utils\Database::fetchColumn(
                'SELECT moduleID FROM ' . static::getTableName() . ' WHERE author = ? AND name = ? AND installed = ?',
                array($data['author'], $data['name'], true)
            );

            // If found in DB
            if ($moduleID) {
                $query  = 'UPDATE ' . static::getTableName() 
                        . ' SET enabled = ?, dataInstalled = ? WHERE moduleID = ?';
                $params = array(true, true, $moduleID);

            } else {
                $params = array(
                    'name'          => $data['name'],
                    'author'        => $data['author'],
                    'enabled'       => true,
                    'installed'     => true,
                    'dataInstalled' => true,
                    'date'          => time(),
                    'majorVersion'  => static::callModuleMethod($module, 'getMajorVersion'),
                    'minorVersion'  => static::callModuleMethod($module, 'getMinorVersion'),
                    'moduleName'    => static::callModuleMethod($module, 'getModuleName'),
                    'authorName'    => static::callModuleMethod($module, 'getAuthorName'),
                    'description'   => static::callModuleMethod($module, 'getDescription'),
                    'iconURL'       => static::callModuleMethod($module, 'getIconURL'),
                    'pageURL'       => static::callModuleMethod($module, 'getPageURL'),
                    'authorPageURL' => static::callModuleMethod($module, 'getAuthorPageURL'),
                    'dependencies'  => serialize(static::callModuleMethod($module, 'getDependencies')),
                );
                $query = 'REPLACE INTO ' . static::getTableName() 
                        . ' SET ' . implode(' = ?,', array_keys($params)) . ' = ?';
            }

            // Enable module
            \Includes\Utils\Database::execute($query, array_values($params));
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
        return static::getPathPattern(preg_quote(static::getClassesDir(), '/') . '\w+', 'Module', 'php');
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
     * Initialize active modules
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function initModules()
    {
        foreach (static::getActiveModules() as $module => $data) {
            $class = static::getClassNameByModuleName($module);
            $class::init();
        }
    }
}
