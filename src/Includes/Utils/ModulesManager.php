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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace Includes\Utils;

/**
 * Some useful constants
 */
define('LC_DS_QUOTED', preg_quote(LC_DS, '/'));
define('LC_DS_OPTIONAL', '(' . LC_DS_QUOTED . '|$)');

/**
 * ModulesManager
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class ModulesManager extends \Includes\Utils\AUtils
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
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $activeModules;

    /**
     * Data for class tree walker
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $quotedPaths;


    // {{{ Name convertion routines

    /**
     * Get class name by module name
     *
     * @param string $moduleName module actual name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getModuleNameByClassName($className)
    {
        return preg_match(self::CLASS_NAME_PATTERN, $className, $matches) ? $matches[1] : null;
    }

    /**
     * Compose module actual name
     *
     * @param string $author Module author
     * @param string $name   Module name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getActualName($author, $name)
    {
        return $author . '\\' . $name;
    }

    /**
     * Compose module class name by module author and name
     *
     * @param string $author Module author
     * @param string $name   Module name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getClassNameByAuthorAndName($author, $name)
    {
        return static::getClassNameByModuleName(static::getActualName($author, $name));
    }

    // }}}

    // {{{ Methods to access installed module main class

     /**
     * Initialize active modules
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function initModules()
    {
        foreach (static::getActiveModules() as $module => $data) {
            static::callModuleMethod($module, 'init');
        }
    }

    /**
     * Check if module is installed
     *
     * @param string $module Module actual name
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function isModuleInstalled($module)
    {
        return \Includes\Utils\Operator::checkIfClassExists(static::getClassNameByModuleName($module));
    }

    /**
     * Method to access module main clas methods
     *
     * @param string $module Module actual name
     * @param string $method Method to call
     * @param array  $args   Call arguments OPTIONAL
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function callModuleMethod($module, $method, array $args = array())
    {
        $result = null;

        if (static::isModuleInstalled($module)) {
            $result = call_user_func_array(array(static::getClassNameByModuleName($module), $method), $args);
        }

        return $result;
    }

    /**
     * Get module info from it's main class
     *
     * @param string $author         Module author
     * @param string $name           Module name
     * @param array  $additionalData Data to add to result
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getModuleDataFromClass($author, $name, array $additionalData = array())
    {
        $module = static::getActualName($author, $name);

        $result = array(
            'name'          => $name,
            'author'        => $author,
            'enabled'       => intval(static::isActiveModule($module)),
            'installed'     => 1,
            'dataInstalled' => 1,
            'date'          => time(),
            'marketplaceID' => '',
            'majorVersion'  => static::callModuleMethod($module, 'getMajorVersion'),
            'minorVersion'  => static::callModuleMethod($module, 'getMinorVersion'),
            'moduleName'    => static::callModuleMethod($module, 'getModuleName'),
            'authorName'    => static::callModuleMethod($module, 'getAuthorName'),
            'description'   => static::callModuleMethod($module, 'getDescription'),
            'iconURL'       => static::callModuleMethod($module, 'getIconURL'),
            'pageURL'       => static::callModuleMethod($module, 'getPageURL'),
            'authorPageURL' => static::callModuleMethod($module, 'getAuthorPageURL'),
            'dependencies'  => serialize((array) static::callModuleMethod($module, 'getDependencies')),
            'rating'        => 0,
            'votes'         => 0,
            'downloads'     => 0,
            'price'         => 0.00,
            'currency'      => 'USD',
            'revisionDate'  => 0,
            'packSize'      => 0,
        );

        return array_replace_recursive($result, $additionalData);
    }

    // }}}

    // {{{ Active modules

    /**
     * Return list of active modules (or check a single module)
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getActiveModules()
    {
        if (!isset(static::$activeModules)) {

            // Fetch active modules from the common list
            static::$activeModules = \Includes\Utils\ArrayManager::searchAllInArraysArray(
                static::getModulesList(),
                'enabled',
                true
            );

            // Remove unsupported modules from list
            static::checkVersions();

            // Remove unsafe modules
            static::performSafeModeProtection();

            // Remove modules with corrupted dependencies
            static::correctDependencies();
        }

        return static::$activeModules;
    }

    /**
     * Check if module is active
     *
     * @param string|null $moduleName module name
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function isActiveModule($moduleName)
    {
        return (bool) \Includes\Utils\ArrayManager::getIndex(static::getActiveModules(), $moduleName, true);
    }

    /**
     * Disable modules with non-correct versions
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * Disable some (or all) modules in SafeMode
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function performSafeModeProtection()
    {
        if (\Includes\SafeMode::isSafeModeStarted()) {

            // Get unsafe modules list
            $modules = \Includes\SafeMode::isSoftResetRequested()
                ? \Includes\SafeMode::getUnsafeModulesList()
                : array_keys(static::$activeModules);

            // Disable modules
            array_walk_recursive($modules, array('static', 'disableModule'));
            \Includes\SafeMode::cleanupIndicator();
        }
    }

    /**
     * Disable modules with incorrect dependencies
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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

        $dependencies = array_diff_key($dependencies, static::$activeModules);
        array_walk_recursive($dependencies, array('static', 'disableModule'));
    }

    // }}}

    // {{{ Methods to manage module states (installed/enabled)

    /**
     * Set module enabled flag fo "false"
     *
     * @param string $key module actual name (key)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function disableModule($key)
    {
        if (isset(static::$activeModules[$key])) {

            // Short names
            $data = static::$activeModules[$key];
            $path = static::getModulesFilePath();

            // Check if "xlite_modules" table exists
            if (\Includes\Utils\FileManager::isFileReadable($path)) {

                // Set flag in .ini-file
                $pattern = '/(\[' . $data['author'] . '\][^\[]+\s*' . $data['name'] . '\s*=)\s*\S+/Ss';
                \Includes\Utils\FileManager::replace($path, '$1 0', $pattern);

            } else {

                // Set flag in DB
                $query = 'UPDATE ' . static::getTableName() . ' SET enabled = ? WHERE moduleID = ?';
                \Includes\Utils\Database::execute($query, array(0, $data['moduleID']));
            }

            // Remove from local cache
            unset(static::$activeModules[$key]);
        }
    }

    /**
     * Get modules list file path
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getModulesFilePath()
    {
        return LC_DIR_VAR . self::MODULES_FILE_NAME;
    }

    // }}}

    // {{{ DB-related routines

    /**
     * Fetch modules list from the database
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function fetchModulesListFromDB()
    {
        $field = static::getModuleNameField();
        $table = static::getTableName();

        return \Includes\Utils\Database::fetchAll(
            'SELECT ' . $field . $field . $table . '.* FROM ' . $table . ' WHERE installed = ? AND enabled = ?',
            array(1, 1),
            \PDO::FETCH_ASSOC | \PDO::FETCH_GROUP | \PDO::FETCH_UNIQUE
        );
    }

    /**
     * Return name of the table where the module info is stored
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getTableName()
    {
        return 'xlite_modules';
    }

    /**
     * Part of SQL query to fetch composed module name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getModuleNameField()
    {
        return 'CONCAT(author,\'\\\\\',name) AS actualName, ';
    }

    // {{{ List of all modules

    /**
     * Fetch list of active modules from DB
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getModulesList()
    {
        $list = array();
        $path = static::getModulesFilePath();

        if (\Includes\Utils\FileManager::isFileReadable($path)) {
            foreach (parse_ini_file($path, true) as $author => $data) {
                foreach ($data as $name => $enabled) {
                    if ($enabled) {
                        $list[$author . '\\' . $name] = array(
                            'actualName' => static::getActualName($author, $name),
                            'name'       => $name,
                            'author'     => $author,
                            'enabled'    => $enabled,
                            'moduleName' => $name,
                            'authorName' => $author,
                        );
                    }
                }
            }
        } else {
            $list = static::fetchModulesListFromDB();
        }

        return $list;
    }

    // }}}

    // {{{ Modules info manipulations

    /**
     * Remove file with active modules list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function removeFile()
    {
        \Includes\Utils\FileManager::deleteFile(static::getModulesFilePath());
    }

    /**
     * Save modules to file
     *
     * @param array $modules Modules array
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function saveModulesToFile(array $modules)
    {
        $string = '';

        foreach ($modules as $author => $data) {
            $string .= '[' . $author . ']' . PHP_EOL;
            foreach ($data as $name => $enabled) {
                $string .= $name . ' = ' . ((bool) $enabled) . PHP_EOL;
            }
        }

        if ($string) {
            \Includes\Utils\FileManager::write(
                static::getModulesFilePath(),
                '; <' . '?php /*' . PHP_EOL . $string . '; */ ?' . '>'
            );
        }
    }

    /**
     * Write module info to DB
     *
     * @param string $author Module author
     * @param string $name   Module name
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function switchModule($author, $name)
    {
        // Short names
        $condition = ' WHERE author = ? AND name = ?';
        $table     = static::getTableName();
        $module    = static::getActualName($author, $name);

        // Versions
        $majorVersion = static::callModuleMethod($module, 'getMajorVersion');
        $minorVersion = static::callModuleMethod($module, 'getMinorVersion');

        // Reset exisiting settings
        $query = 'UPDATE ' . $table . ' SET enabled = ?, installed = ?, dataInstalled = ?' . $condition;
        \Includes\Utils\Database::execute($query, array(0, 0, 0, $author, $name));

        // Search for module
        $query    = 'SELECT moduleID FROM ' . $table . $condition . ' AND majorVersion = ? AND minorVersion = ?';
        $moduleID = \Includes\Utils\Database::fetchColumn($query, array($author, $name, $majorVersion, $minorVersion));

        // If found in DB
        if ($moduleID) {
            $data  = array(intval(static::isActiveModule($module)), 1, 1, $moduleID);
            $query = 'UPDATE ' . $table . ' SET enabled = ?, installed = ?, dataInstalled = ? WHERE moduleID = ?';
        } else {
            $data  = static::getModuleDataFromClass($author, $name);
            $query = 'REPLACE INTO ' . $table . ' SET ' . implode(' = ?,', array_keys($data)) . ' = ?';
        }

        // Save changes in DB
        \Includes\Utils\Database::execute($query, array_values($data));
    }

    // }}}

    // {{{ Module paths

    /**
     * Return pattern to check PHP file paths
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getPathPatternForPHP()
    {
        $root = preg_quote(\Includes\Decorator\ADecorator::getClassesDir(), '/') . 'XLite';
        $modules = '(' . implode('|', static::getModuleQuotedPaths()) . ')';

        return '/^(?:'
            . $root . LC_DS_QUOTED . '((?!Module)[a-zA-Z0-9]+)' . LC_DS_QUOTED . '.+'
            . '|' . $root . LC_DS_QUOTED . 'Module' . LC_DS_QUOTED . $modules . LC_DS_QUOTED . '.+'
            . '|' . $root
            . '|' . $root . LC_DS_QUOTED . 'Module' . LC_DS_QUOTED . '[a-zA-Z0-9]+'
            . '|' . $root . LC_DS_QUOTED . '[a-zA-Z0-9]+'
            .')\.php$/Ss';
    }

    /**
     * Return pattern to check .tpl file paths
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getPathPatternForTemplates()
    {
        return static::getPathPattern(
            preg_quote(LC_DIR_SKINS, '/') . '\w+' . LC_DS_QUOTED . '\w+', 'modules', 'tpl'
        );
    }

    /**
     * Callback to collect module paths
     *
     * @param \Includes\Decorator\DataStructure\Graph\Modules $node Current module node
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getModuleQuotedPathsCallback(\Includes\Decorator\DataStructure\Graph\Modules $node)
    {
        static::$quotedPaths[$node->getActualName()] = str_replace('\\', LC_DS_QUOTED, $node->getActualName());
    }

    /**
     * Return list of relative module paths
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getModuleQuotedPaths()
    {
        if (!isset(static::$quotedPaths)) {
            static::$quotedPaths = array();
            \Includes\Decorator\ADecorator::getModulesGraph()->walkThrough(
                array(get_called_class(), 'getModuleQuotedPathsCallback')
            );
        }

        return static::$quotedPaths;
    }

    /**
     * Return pattern to file path againist active modules list
     *
     * @param string $rootPath  name of the root directory
     * @param string $dir       name of the directory with modules
     * @param string $extension file extension
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getPathPattern($rootPath, $dir, $extension)
    {
        $modulePattern = $dir . LC_DS_QUOTED . ($placeholder = '@') . LC_DS_OPTIONAL;

        return '/^' . $rootPath . '(.((?!' . str_replace($placeholder, '\w+', $modulePattern) . ')|'
            . str_replace($placeholder, '(' . implode('|', static::getModuleQuotedPaths()) . ')', $modulePattern)
            . '))*\.' . $extension . '$/i';
    }

    // }}}
}
