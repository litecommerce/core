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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Decorator - classes cache builder
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Decorator
{
    /**
     * Indexes in "classesInfo" array
     */
    
    const INFO_FILE          = 'file';
    const INFO_CLASS         = 'class';
    const INFO_CLASS_ORIG    = 'clas_orig';
    const INFO_EXTENDS       = 'extends';
    const INFO_EXTENDS_ORIG  = 'extends_orig';
    const INFO_IS_DECORATOR  = 'is_decorator';
    const INFO_IS_SINGLETON  = 'is_singleton';
    const INFO_IS_ROOT_CLASS = 'is_top_class';
    const INFO_CLASS_TYPE    = 'class_type';

    /**
     * Pattern to parse PHP files
     */
    const CLASS_PATTERN = '/\s*((?:abstract|final)\s+)?(class|interface)\s+([\w_]+)(\s+extends\s+([\w_]+))?(\s+implements\s+([\w_]+(?:\s*,\s*[\w_]+)*))?\s*(\/\*.*\*\/)?\s*{/USsi';

    /**
     * Suffix for the so called "root" decorator class names
     */
    const ROOT_CLASS_SUFFIX = 'Abstract';

    /**
     * Identifier to insert into the decorator comments
     */
    const DECORATOR_IDENTIFIER = '____DECORATOR____';


    /**
     * Current value of the "max_execution_time" INI setting
     * 
     * @var    int
     * @access protected
     * @since  3.0
     */
    protected $maxExecutionTime = null;

    /**
     * Tags in decorator comments 
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $commentFields = array(
        self::INFO_FILE         => 'file   ',
        self::INFO_CLASS_ORIG   => 'class  ',
        self::INFO_EXTENDS_ORIG => 'extends',
    );

    /**
     * Settings retrieved from config files
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $configOptions = null;

    /**
     * PDO connection handler 
     * 
     * @var    PDO
     * @access protected
     * @since  3.0
     */
    protected $dbHandler = null;

    /**
     * Classes info
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $classesInfo = array();

    /**
     * Class decorators info
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $classDecorators = array();

    /**
     * List of active modules 
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $activeModules = null;

    /**
     * List of module dependencies 
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $moduleDependencies = null;

    /**
     * List of active modules and their priority values 
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $modulePriorities = null;

    /**
     * Modules whitch are not allowed to be enbled at one time
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $mutualModules = null;

    /**
     * List of module controllers which names are needed to be normalized
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $normalizedControllers = array();


    /**
     * Return current value of the "max_execution_time" INI setting 
     * 
     * @return int|string
     * @access protected
     * @since  3.0
     */
    protected function getMaxExecutionTime()
    {
        if (!isset($this->maxExecutionTime)) {
            $this->maxExecutionTime = @ini_get('max_execution_time');
        }

        return $this->maxExecutionTime;
    }

    /**
     * Set value for the "max_execution_time" INI setting
     *
     * @return void
     * @access protected
     * @since  3.0
     */
    protected function setMaxExecutionTime()
    {
        // Save original value
        $this->getMaxExecutionTime();

        @set_time_limit(180);
    }

    /**
     * Restore original value of the "max_execution_time" INI setting
     *
     * @return void
     * @access protected
     * @since  3.0
     */
    protected function restoreMaxExecutionTime()
    {
        $time = $this->getMaxExecutionTime();

        if (!empty($time)) {
            @set_time_limit($time);
        }
    }

    /**
     * Show javascript notice block 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0 EE
     */
    protected function showJavaScriptBlock($redirectUrl = null)
    {
        if (is_null($redirectUrl)) {
            $code = '<table id="rebuild_cache_block"><tr>'
                . '<td><img src="skins/progress_indicator.gif" /></td>'
                . '<td>Re-building cache, please wait...</td>'
                . '</tr></table>';
            func_flush('<script type="text/javascript">document.write(\'' . $code . '\');</script>' . "\n");

        } else {
            $code = '<script type="text/javascript">self.location=\'' . $redirectUrl . '\';</script>'
                . '<noscript><a href="' . $redirectUrl . '">Click here to redirect</a></noscript>';
            func_flush($code);
        }
    }

    /**
     * Show plain text notice block 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0 EE
     */
    protected function showPlainTextBlock()
    {
        func_flush('Re-building cache, please wait...' . "\n");
    }

    /**
     * Return class name by class file path 
     * 
     * @param string $path PHP file path
     *  
     * @return string
     * @access protected
     * @since  3.0
     */
    protected function getClassByPath($path)
    {
        return str_replace(LC_DS, '_', $path);
    }

    /**
     * Return file path by class name
     * 
     * @param string $class class name
     *  
     * @return string
     * @access protected
     * @since  3.0
     */
    protected function getFileByClass($class)
    {
        return str_replace('_', LC_DS, $class) . '.php';
    }

    /**
     * Return text for unresolved dependencies error
     * 
     * @param array $dependencies list of unresolved dependencies
     *  
     * @return string
     * @access protected
     * @since  3.0
     */
    protected function getDependenciesErrorText(array $dependencies)
    {
        $text = 'Class decorator is unable to resolve the following dependencies:<br /><br />' . "\n\n";

        foreach ($dependencies as $module => $dependedModules) {
            $text .= '<strong>' . $module . '</strong>: ' . implode (', ', $dependedModules) . '<br />' . "\n";
        }

        return $text;
    }

    /**
     * Return comment text to insert into decorated class files 
     * 
     * @param array $info class info
     *  
     * @return string
     * @access protected
     * @since  3.0
     */
    protected function getClassComment(array $info)
    {
        // TODO - check if comment is needed
        return '';

        $comment = array(self::DECORATOR_IDENTIFIER);

        foreach ($this->commentFields as $field => $tag) {
            if (isset($info[$field])) {
                $comment[] = '@' . $tag . ' ' . $info[$field];
            }
        }

        return "\n\n" . '/**' . "\n" . ' * ' . implode("\n" . ' * ', $comment) . "\n" . ' */';
    }

    /**
     * Parse class file content 
     * 
     * @param array $info class info
     *  
     * @return string
     * @access protected
     * @since  3.0
     */
    protected function parseClassFile(array $info)
    {
        $content = isset($info[self::INFO_FILE]) ? file_get_contents(LC_CLASSES_DIR . $info[self::INFO_FILE]) : '';

        if (!empty($info[self::INFO_IS_ROOT_CLASS]) && preg_match(self::CLASS_PATTERN, $content, $matches)) {

            $body = "\n";
            if (!empty($info[self::INFO_IS_SINGLETON])) {
                $body .= "\t" . 'public static function getInstance()' . "\n"
                         . "\t" . '{' . "\n\t\t" . 'return self::getInternalInstance(__CLASS__);' . "\n\t" . '}' . "\n";
            }

            // Top level class in decorator chain - has an empty body
            $content = '<?php' . $this->getClassComment($info) . "\n" . $matches[1] . 'class ' 
                       . (isset($info[self::INFO_CLASS]) ? $info[self::INFO_CLASS] : $matches[3])
                       . (isset($info[self::INFO_EXTENDS]) ? ' extends ' . $info[self::INFO_EXTENDS] : '')
                       . (isset($matches[6]) ? $matches[6] : '') . "\n" . '{' . $body . '}' . "\n";
        } else {

            // Replace class and name of class which extends the current one
            $replace = $this->getClassComment($info) . "\n" 
                       . (isset($info[self::INFO_CLASS_TYPE]) ? $info[self::INFO_CLASS_TYPE] . ' ' : '$1') . '$2 ' 
                       . (isset($info[self::INFO_CLASS]) ? $info[self::INFO_CLASS] : '$3') 
                       . (isset($info[self::INFO_EXTENDS]) ? ' extends ' . $info[self::INFO_EXTENDS] : '$4') 
                       . '$6' . "\n" . '{';
            $content = preg_replace(self::CLASS_PATTERN, $replace, $content);
        }

        // Change name of normalized classes in PHP code
        foreach ($this->normalizedControllers as $oldClass => $newClass) {
            $content = preg_replace('/' . $oldClass . '/i', $newClass, $content);
        }

        return $content;
    }

    /**
     * Check if current class is a controller defined by module 
     * 
     * @param class $class class name
     *  
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function isModuleController($class)
    {
        return preg_match('/XLite_Module_\w+_Controller_?[\w_]*/', $class);
    }
    
    /**
     * Remove the module-related part from module controller class
     * 
     * @param string $class class name
     *  
     * @return string
     * @access protected
     * @since  3.0
     */
    protected function prepareModuleController($class)
    {
        return preg_replace('/XLite_(Module_\w+_)Controller(_?[\w_]*)/', 'XLite_Controller$2', $class);
    }

    /**
     * Check if class implements some interface
     * 
     * @param string $interfaceName interface to check
     * @param string $implements    string from class declaration (the "implements ..." part)
     *  
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function isImplements($interfaceName, $implements)
    {
        return in_array($interfaceName, explode(',', str_replace(' ', '', trim($implements))));
    }

    /**
     * Check if current class implements the "IDecorator" interface
     * 
     * @param array $implements list of implemented inerfaces
     *  
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function isDecorator($implements)
    {
        return $this->isImplements('XLite_Base_IDecorator', $implements);
    }

    /**
     * Check if current class implements the "ISingleton" interface
     *
     * @param array $implements list of implemented inerfaces
     *
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function isSingleton($implements)
    {
        return $this->isImplements('XLite_Base_ISingleton', $implements);
    }

    /**
     * Return setting from config.ini file 
     * 
     * @param string $section name of section in config file
     *  
     * @return void
     * @access protected
     * @since  3.0
     */
    protected function getConfigOptions($section = '')
    {
        if (!isset($this->configOptions)) {
            $this->configOptions = funcParseConfgFile();
        }

        return empty($section) ? $this->configOptions : (isset($this->configOptions[$section]) ? $this->configOptions[$section] : null);
    }

    /**
     * Prepare MySQL connection string
     *
     * @param array $options MySQL credentials
     *
     * @return string
     * @access protected
     * @since  3.0
     */
    protected function getConnectionString(array $options)
    {
        $dsnFields = array(
            'host'        => 'hostspec',
            'port'        => 'port',
            'unix_socket' => 'socket',
            'dbname'      => 'database',
        );
        $dsnString = array();

        foreach ($dsnFields as $pdoOption => $lcOption) {

            if (!empty($options[$lcOption])) {
                $dsnString[] = $pdoOption . '=' . $options[$lcOption];
            }
        }

        return 'mysql:' . implode(';', $dsnString);
    }

    /**
     * Connect to database 
     * 
     * @return PDO
     * @access protected
     * @since  3.0
     */
    protected function connectToDb()
    {
        $options = $this->getConfigOptions('database_details');

        $user     = isset($options['username']) ? $options['username'] : '';
        $password = isset($options['password']) ? $options['password'] : '';

        // PDO flags using for connection
        $connectionParams = array(
            PDO::ATTR_AUTOCOMMIT               => true,
            PDO::ATTR_ERRMODE                  => PDO::ERRMODE_SILENT,
            PDO::ATTR_PERSISTENT               => false,
        );

        return new PDO($this->getConnectionString($options), $user, $password, $connectionParams);
    }

    /**
     * Return PDO database handler
     * 
     * @return PDO
     * @access protected
     * @since  3.0
     */
    protected function getDbHandler()
    {
        if (!isset($this->dbHandler)) {
            $this->dbHandler = $this->connectToDb();
        }

        return $this->dbHandler;
    }

    /**
     * Perform SQL query (return araay of records) 
     * 
     * @param string $sql SQL query to execute
     *  
     * @return array
     * @access protected
     * @since  3.0
     */
    protected function fetchAll($sql, $flags = PDO::FETCH_ASSOC)
    {
        return $this->getDbHandler()->query($sql)->fetchAll($flags);
    }

    /**
     * Perform SQL query (single value)
     *
     * @param string $sql SQL query to execute
     *
     * @return string
     * @access protected
     * @since  3.0
     */
    protected function fetchColumn($sql)
    {
        return $this->getDbHandler()->query($sql)->fetchColumn();
    }

    /**
     * Check if directory with cached PHP files is exists 
     * 
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function isCacheDirExists()
    {
        return file_exists(LC_CLASSES_CACHE_DIR) && is_dir(LC_CLASSES_CACHE_DIR) && is_readable(LC_CLASSES_CACHE_DIR);
    }

    /**
     * Check if LiteCommerce is in so called "developer mode" (forced to rebuild cache)
     *
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function isDeveloperMode()
    {
        $query = 'SELECT value FROM xlite_config WHERE category = \'General\' AND name = \'developer_mode\'';

        return ('Y' === $this->fetchColumn($query)) && empty($_REQUEST['action']);
    }

    /**
     * Check if cache rebuild is required
     * 
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function isNeedRebuild()
    {
        return !$this->isCacheDirExists();
    }

    /**
     * Check for PHP files 
     * 
     * @param string $filePath file name and path
     *  
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function checkFile($filePath)
    {
        $pathInfo = pathinfo($filePath);

        return !empty($pathInfo['extension']) && 'php' === strtolower($pathInfo['extension']); 
    }

    /**
     * Retrieve module name from class name 
     * 
     * @param string $className class name to parse
     *  
     * @return string|null
     * @access protected
     * @since  3.0
     */
    protected function getModuleNameByClassName($className)
    {
        return preg_match('/XLite_Module_(\w+)(_|$)/U', $className, $matches) ?
                        (('Abstract' === $matches[1]) ? null : $matches[1]) : null;
    }

    /**
     * Parse class file and return class info 
     * 
     * @param string $filePath file name and path
     *  
     * @return array
     * @access protected
     * @since  3.0
     */
    protected function getClassInfo($filePath)
    {
        $result = array('', '', '');

        if (preg_match(self::CLASS_PATTERN, file_get_contents($filePath), $matches)) {

            // Class name, extends clas name and the "implements A, B, C ..." part
            foreach (array(3, 5, 7) as $index => $key) {
                $result[$index] = isset($matches[$key]) ? $matches[$key] : '';
            }
        }

        return $result;
    }

    /**
     * Return list of modules whitch are not allowed to be enbled at one time
     * 
     * @return array
     * @access protected
     * @since  3.0
     */
    protected function getMutualModules()
    {
        if (!isset($this->mutualModules)) {

            $this->mutualModules = $this->fetchAll(
                'SELECT name, mutual_modules FROM xlite_modules WHERE enabled = \'1\' AND mutual_modules != \'\'',
                PDO::FETCH_ASSOC | PDO::FETCH_GROUP | PDO::FETCH_UNIQUE | PDO::FETCH_COLUMN
            );

            foreach ($this->mutualModules as &$module) {
                $module = explode(',', $module);
            }
        }

        return $this->mutualModules;
    }

    /**
     * Return list of active modules 
     * 
     * @return array
     * @access protected
     * @since  3.0
     */
    protected function getActiveModules($moduleName = null)
    {
        if (!isset($this->activeModules)) {

            $this->activeModules = $this->fetchAll(
                'SELECT name, \'1\' FROM xlite_modules WHERE enabled = \'1\'',
                PDO::FETCH_ASSOC | PDO::FETCH_GROUP | PDO::FETCH_UNIQUE | PDO::FETCH_COLUMN
            );

            $modulesToDisable = array();

            foreach ($this->getMutualModules() as $module => $dependencies) {
                if (isset($this->activeModules[$module])) {
                    foreach ($dependencies as $dependendModule) {
                        unset($this->activeModules[$dependendModule]);
                        $modulesToDisable[] = $dependendModule;
                    }
                }
            }

            if (!empty($modulesToDisable)) {
                $modulesToDisable = array_unique($modulesToDisable);
                $query = 'UPDATE xlite_modules SET enabled = \'0\' WHERE name IN '
                         . '(' . implode(',', array_fill(0, count($modulesToDisable), '?')) . ')';
                $this->getDbHandler()->prepare($query)->execute($modulesToDisable);
            }
        }

        return isset($moduleName) ? isset($this->activeModules[$moduleName]) : $this->activeModules;
    }

    /**
     * Check if module is active 
     * 
     * @param string $moduleName module to check
     *  
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function isActiveModule($moduleName)
    {
        return !isset($moduleName) || $this->getActiveModules($moduleName);
    }

    /**
     * Return list of <module_name> => <dependend_module_1>, <dependend_module_2>, ..., <dependend_module_N>
     * 
     * @return array
     * @access protected
     * @since  3.0
     */
    protected function getModuleDependencies()
    {
        if (!isset($this->moduleDependencies)) {

            $this->moduleDependencies = array();

            foreach ($this->getActiveModules() as $module) {

                // Fetch dependencies from db
                $dependencies = $this->fetchColumn(
                    'SELECT dependencies FROM xlite_modules WHERE name = \'' . addslashes($module) . '\''
                );
                $this->moduleDependencies[$module] = empty($dependencies) ? array() : explode(',', $dependencies);
            }
        }

        return $this->moduleDependencies;
    }

    /**
     * Recursive function to build modules chain base on their dependencies 
     * 
     * @param array $dependencies      dependencies for all modules
     * @param array $levelDependencies available modules for current recursion level
     * @param int   $level             recursion level
     *  
     * @return array
     * @access protected
     * @since  3.0
     */
    protected function calculateModulePriorities(array $dependencies, array $levelDependencies = array(), $level = 0)
    {
        $priorities = array();
        $subLevelDependencies = $levelDependencies;

        // This flag determines if there were any changes on current recursion level
        $isChanged = empty($dependencies);

        foreach ($dependencies as $module => $dependendModules) {

            // Module priority is equals to current level if all module dependencies are already checked
            if (array() === array_diff($dependendModules, $levelDependencies)) {

                // Set priority
                $priorities[$module] = $level;

                // Exclude module from calculation
                unset($dependencies[$module]);

                // Add it to next-level dependencies
                $subLevelDependencies[] = $module;

                // Set flag
                $isChanged = true;
            }
        }

        // There are unresolved dependencies
        $isChanged || die ($this->getDependenciesErrorText($dependencies));

        // Recursive call
        return array_merge(
            $priorities,
            empty($dependencies) ? array() : $this->calculateModulePriorities($dependencies, $subLevelDependencies, ++$level)
        );
    }

    /**
     * Return priority for certain module 
     * 
     * @param string $moduleName module name
     *  
     * @return int
     * @access protected
     * @since  3.0
     */
    protected function getModulePriority($moduleName)
    {
        if (!isset($this->modulePriorities)) {
            $this->modulePriorities = $this->calculateModulePriorities($this->getModuleDependencies());
        }

        return isset($this->modulePriorities[$moduleName]) ? $this->modulePriorities[$moduleName] : 0;
    }

    /**
     * Walk through the PHP files tree and collect classes info 
     * 
     * @return void
     * @access protected
     * @since  3.0
     */
    protected function createClassTree()
    {
        // Only check PHP files
        $fileNamePattern = '/^' . preg_quote(LC_CLASSES_DIR, '/') . '(.*)\.php$/i';

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(LC_CLASSES_DIR)) as $fileInfo) {

            if ($fileInfo->isFile()) {
                $filePath = $fileInfo->getPathname();

                if ($this->checkFile($filePath)) {

                    // Parse file and get class info
                    list($class, $extends, $implements) = $this->getClassInfo($filePath);

                    // Check classes for active modules only
                    if (!empty($class) && $this->isActiveModule($this->getModuleNameByClassName($class))) {

                        // Get path related to the "LC_CLASSES_DIR" directory
                        $relativePath = preg_replace($fileNamePattern, '$1.php', $filePath);

                        // Class defined in current PHP file has a wrong name (not corresponded to file name)
                        if (isset($this->classesInfo[$class])) {
                            die ('Class "' . $class . '" is already defined in file "' . $relativePath . '"');
                        }

                        // Do not include class into cache if parent defined in currently disabled module
                        if (empty($extends) || $this->isActiveModule($this->getModuleNameByClassName($extends))) {

                            // Save data
                            $this->classesInfo[$class] = array(
                                self::INFO_FILE         => $relativePath,
                                self::INFO_CLASS_ORIG   => $class,
                                self::INFO_EXTENDS      => $extends,
                                self::INFO_EXTENDS_ORIG => $extends,
                                self::INFO_IS_DECORATOR => $this->isDecorator($implements),
                                self::INFO_IS_SINGLETON => $this->isSingleton($implements),
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     * Module can define their own controllers.
     * To use them we need to place this classes into the Controlle/{Admin|Customer} directory and change class name 
     * 
     * @return void
     * @access protected
     * @since  3.0
     */
    protected function normalizeModuleControllerNames()
    {
        foreach ($this->classesInfo as $class => $info) {

            // Only rename classes which are not decorates controllers
            if (!empty($class) && $this->isModuleController($class) && !$info[self::INFO_IS_DECORATOR]) {

                // Cut module-related part from class name
                $newClass = $this->prepareModuleController($class);

                // Error - such controller is already defined in LC core or in other module
                if (isset($this->classesInfo[$newClass])) {
                    die (
                        'Module "' . $this->getModuleNameByClassName($class) 
                        . '" has defined controller class "' . $class 
                        . '" which does not decorate any other one and has an ambigous name'
                    );
                }

                // Rename and save data
                $this->classesInfo[$newClass] = array_merge($info, array(self::INFO_CLASS => $newClass));
                unset($this->classesInfo[$class]);
                $this->normalizedControllers[$class] = $newClass;
            }
        }

        // Rename classes in the "INFO_EXTENDS" field
        foreach ($this->classesInfo as $class => $info) {

            if (isset($this->normalizedControllers[$info[self::INFO_EXTENDS]])) {
                $this->classesInfo[$class][self::INFO_EXTENDS] = $this->normalizedControllers[$info[self::INFO_EXTENDS]];
            }
        }
    }

    /**
     * Find all classes which implement interface "IDecorator" and save them as the tree
     * 
     * @return void
     * @access protected
     * @since  3.0
     */
    protected function createDecoratorTree()
    {
        foreach ($this->classesInfo as $class => $info) {

            if ($info[self::INFO_IS_DECORATOR]) {

                // Create new node
                if (!isset($this->classDecorators[$info[self::INFO_EXTENDS]])) {
                    $this->classDecorators[$info[self::INFO_EXTENDS]] = array();
                }

                // Save class name and its priority (equals to module priority)
                $this->classDecorators[$info[self::INFO_EXTENDS]][$class] = 
                    $this->getModulePriority($this->getModuleNameByClassName($class));
            }

            // These fields are not needed
            if (empty($info[self::INFO_EXTENDS])) {
                unset($this->classesInfo[$class][self::INFO_EXTENDS]);
            }
            unset($this->classesInfo[$class][self::INFO_IS_DECORATOR]);
        }
    }

    /**
     * Modify classes tree according to the decorators tree 
     * 
     * @return void
     * @access protected
     * @since  3.0
     */
    protected function mergeClassAndDecoratorTrees()
    {
        foreach ($this->classDecorators as $class => $decorators) {

            // Sort decorated classes by module priority and invert decorator chain
            arsort($decorators, SORT_NUMERIC);
            $decorators = array_keys($decorators);

            $currentClass = $class;

            // Each decorator class extends a next one in decorator chain
            foreach ($decorators as $decorator) {
                $this->classesInfo[$currentClass][self::INFO_EXTENDS] = $decorator;
                $currentClass = $decorator;
            }

            // So called "root" class - class extended by decorators
            $rootClass = $class . self::ROOT_CLASS_SUFFIX;

            $this->classesInfo[$currentClass][self::INFO_EXTENDS] = $rootClass;
            $this->classesInfo[$class][self::INFO_IS_ROOT_CLASS] = true;

            // Wrong class name
            if (!isset($this->classesInfo[$class][self::INFO_FILE])) {
                die ('Decorator: undefined class - "' . $class . '"');
            }

            // Assign new (reserved) name to root class and save other info
            $this->classesInfo[$rootClass] = array(
                self::INFO_FILE         => $this->classesInfo[$class][self::INFO_FILE],
                self::INFO_CLASS        => $rootClass,
                self::INFO_CLASS_ORIG   => $class,
                self::INFO_EXTENDS_ORIG => $this->classesInfo[$class][self::INFO_EXTENDS_ORIG],
                self::INFO_CLASS_TYPE   => 'abstract',
            );
        }
    }

    /**
     * Write PHP file into the cache directory
     * 
     * @param string $class class name (uses to get file name)
     * @param string $info  additional class info
     *  
     * @return void
     * @access protected
     * @since  3.0
     */
    protected function writeClassFile($class, $info)
    {
        $fileName = LC_CLASSES_CACHE_DIR . $this->getFileByClass($class);
        $dirName  = dirname($fileName);

        if (!file_exists($dirName) || !is_dir($dirName)) {
            mkdirRecursive($dirName, 0755);
        }

        file_put_contents($fileName, $this->parseClassFile($info));
        chmod($fileName, 0644);
    }

    /**
     * Delete the directory with compiled classes 
     * 
     * @return void
     * @access public
     * @since  3.0
     */
    public function cleanUpCache()
    {
        unlinkRecursive(LC_CLASSES_CACHE_DIR);
        unlinkRecursive(LC_SKINS_CACHE_DIR);
    }

    /**
     * Check and (if needed) rebuild cache
     * 
     * @param bool $force flag to force rebuild
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public function rebuildCache($force = false)
    {
        if ($this->isNeedRebuild() || $this->isDeveloperMode() || $force) {

            if (!defined('SILENT_CACHE_REBUILD')) {
                if ('cli' == php_sapi_name()) {
                    $this->showPlainTextBlock();
                } elseif (empty($_REQUEST['action'])) {
                    $this->showJavaScriptBlock();
                }
            }

            $this->setMaxExecutionTime();

            // Prepare classes list
            $this->createClassTree();
            $this->normalizeModuleControllerNames();
            $this->createDecoratorTree();
            $this->mergeClassAndDecoratorTrees();

            // Remove old files
            if ($this->isCacheDirExists()) {
                $this->cleanUpCache();
            }

            // Write file to the cache directory
            foreach ($this->classesInfo as $class => $info) {
                $this->writeClassFile($class, $info);
            }

            $this->restoreMaxExecutionTime();

            if (
                !defined('SILENT_CACHE_REBUILD')
                && 'cli' != php_sapi_name()
                && isset($_SERVER['HTTP_HOST'])
                && isset($_SERVER['REQUEST_URI'])
            ) {

                $isHttps = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS'] == 'on') || $_SERVER['HTTPS'] == '1'))
                            || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443');
                $redirectUrl = ($isHttps ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                if (@parse_url($redirectUrl) && empty($_REQUEST['action'])) {
                    $this->showJavaScriptBlock($redirectUrl);
                    die();
                }
            }
        }
    }

    // This db connection is not needed for other classes
    public function __destruct()
    {
        $this->dbHandler = null;
    }
}

$decorator = new Decorator();
$decorator->rebuildCache();
$decorator = null;

