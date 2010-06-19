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
    const INFO_CLASS_ORIG    = 'class_orig';
    const INFO_EXTENDS       = 'extends';
    const INFO_EXTENDS_ORIG  = 'extends_orig';
    const INFO_IS_DECORATOR  = 'is_decorator';
    const INFO_IS_SINGLETON  = 'is_singleton';
    const INFO_IS_ROOT_CLASS = 'is_top_class';
    const INFO_CLASS_TYPE    = 'class_type';
    const INFO_ENTITY        = 'entity';
    const INFO_CLASS_COMMENT = 'class_comment';


    /**
     * Pattern to parse PHP files
     */
    const CLASS_PATTERN = '/\s*((?:abstract|final)\s+)?(class|interface)\s+([\w_]+)(\s+extends\s+([\w_]+))?(\s+implements\s+([\w_]+(?:\s*,\s*[\w_]+)*))?\s*(\/\*.*\*\/)?\s*{/USsi';

    /**
     * Pattern to get class DOC block
     */
    const CLASS_COMMENT_PATTERN = '/(\s+\*\/\s+)(?:abstract +)?class /USsi';

    /**
     * Pattern to get interface DOC block
     */
    const INTERFACE_COMMENT_PATTERN = '/(\s+\*\/\s+)interface /USsi';

    /**
     * Pattern to detect entity-based class
     */
    const CLASS_ENTITY_PATTERN = '/@entity/USsi';

    /**
     * Pattern to parse DOC block
     */
    const DOCBLOCK_PATTERN = '/^\s+\*\s+@(\w+)(.*)$/Smi';

    /**
     * Suffix for the so called "root" decorator class names
     */
    const ROOT_CLASS_SUFFIX = 'Abstract';

    /**
     * Identifier to insert into the decorator comments
     */
    const DECORATOR_IDENTIFIER = '____DECORATOR____';

    /**
     *  Messages
     */
    const CONTROLLER_ERR_MSG        = 'Module "%s" has defined controller class "%s" which does not decorate any other one and has an ambigous name';
    const UNDEFINED_CLASS_MSG       = 'Decorator: undefined class - "%s"';
    const CLASS_ALREADY_DEFINED_MSG = 'Class "%s" is already defined in file "%s"';


    /**
     * Error log filename pattern
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $errorLogFilename = 'decoration.log.%s.php';

    /**
     * Class comment attribute error message
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $errorMsgAttribute = 'The class comment contains no %s attribute or this attribute is empty';

    /**
     * Doctrine class attributes 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $doctrineClassAttributes = array(
        'Entity',
        'Table',
        'HasLifecycleCallbacks',
        'InheritanceType',
        'DiscriminatorColumn',
        'DiscriminatorMap',
        'ChangeTrackingPolicy',
    );

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
     * Cache driver (cache)
     * 
     * @var    \Doctrine\Common\Cache\AbstractCache
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $cacheDriver = null;

    /**
     * Entity manager (cache) 
     * 
     * @var    \Doctrine\ORM\EntityManager
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $em = null;

    /**
     * View list childs
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $viewListChilds = array();

    /**
     * Template patches
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $templatePatches = array();

    /**
     * View lists preprocessors 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $viewListPreprocessors = array();

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
                . '<td><img src="skins/progress_indicator.gif" alt="" /></td>'
                . '<td>Re-building cache, please wait...</td>'
                . '</tr></table>';
            $code = '<script type="text/javascript">document.write(\'' . $code . '\');</script>' . "\n";

        } else {
            $code = '<script type="text/javascript">self.location=\'' . $redirectUrl . '\';</script>'
                . '<noscript><a href="' . $redirectUrl . '">Click here to redirect</a></noscript>';
        }

        func_flush($code);
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
            $text .= '<strong>' . $module . '</strong>: ' . implode(', ', $dependedModules) . '<br />' . "\n";
        }

        return $text;
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

            // Top level class in decorator chain has an empty body
            $content = '<?php' . "\n" . trim($info[self::INFO_CLASS_COMMENT]) . "\n" . $matches[1] . 'class ' 
                . (isset($info[self::INFO_CLASS]) ? $info[self::INFO_CLASS] : $matches[3])
                . (isset($info[self::INFO_EXTENDS]) ? ' extends ' . $info[self::INFO_EXTENDS] : '')
                . (isset($matches[6]) ? $matches[6] : '') . "\n" . '{' . $body . '}' . "\n";

        } else {

            // Replace class and name of class which extends the current one
            $replace = "\n" 
                . (isset($info[self::INFO_CLASS_TYPE]) ? $info[self::INFO_CLASS_TYPE] . ' ' : '$1') . '$2 ' 
                . (isset($info[self::INFO_CLASS]) ? $info[self::INFO_CLASS] : '$3') 
                . (isset($info[self::INFO_EXTENDS]) ? ' extends ' . $info[self::INFO_EXTENDS] : '$4') 
                . '$6' . "\n" . '{';
            $content = preg_replace(self::CLASS_PATTERN, $replace, $content);

            // Add MappedSuperclass attribute
            $parent = null;
            foreach (array(self::INFO_EXTENDS_ORIG, self::INFO_CLASS_ORIG) as $index) {
                if (!empty($info[$index])) {
                    $parent = $info[$index];
                    break;
                }
            }

            if ($parent && $this->classesInfo[$parent][self::INFO_ENTITY]) {
                $comment = $this->getClassComment($content);
                if ($comment) {
                    $newComment = $this->modifyParentEntityClassComment($comment);
                    $content = str_replace($comment, $newComment, $content);

                } elseif (preg_match(self::CLASS_PATTERN, $content, $matches)) {
                    $content = str_replace($matches[0], '/** @MappedSuperclass */' . "\n" . $matches[0], $content);
                }
            }

        }

        // Change name of normalized classes in PHP code
        foreach ($this->normalizedControllers as $oldClass => $newClass) {
            $content = preg_replace('/' . $oldClass . '/i', $newClass, $content);
        }

        return $content;
    }

    /**
     * Modify class comment (if class - entity parent)
     * 
     * @param string $comment Comment
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function modifyParentEntityClassComment($comment)
    {
        $newComment = preg_replace(
            '/^ \* @(?:' . implode('|', $this->doctrineClassAttributes) . ').*$/UiSm',
            '',
            $comment
        );
        $newComment = preg_replace('/' . "\n" . '{2,999}/Ss', "\n", $newComment);
        $newComment = preg_replace('/ \*\//Ssi', ' * @MappedSuperclass' . "\n" . '$0', $newComment);

        return $newComment;
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
     * Check if current class implements the "XLite_Base_IViewChild" interface
     * 
     * @param string $comment Class comment
     *  
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function isViewChild($comment)
    {
        return (bool)preg_match('/@ListChild\W/Ssi', $comment);
    }

    /**
     * Check if current class implements the "XLite_Base_IPatcher" interface
     * 
     * @param array $implements list of implemented inerfaces
     *  
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function isPatcher($implements)
    {
        return $this->isImplements('XLite_Base_IPatcher', $implements);
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

        $options = $this->configOptions;
        if ($section) {
            $options = isset($options[$section]) ? $options[$section] : null;
            
        }

        return $options;
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
            PDO::ATTR_AUTOCOMMIT => true,
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_SILENT,
            PDO::ATTR_PERSISTENT => false,
        );

        return new PDO(
            $this->getConnectionString($options),
            $user,
            $password,
            $connectionParams
        );
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
     * @param string  $sql   SQL query to execute
     * @param integer $flags PDO fetch option
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
        return file_exists(LC_CLASSES_CACHE_DIR)
            && is_dir(LC_CLASSES_CACHE_DIR)
            && is_readable(LC_CLASSES_CACHE_DIR);
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

        return ('Y' === $this->fetchColumn($query))
            && empty($_REQUEST['action']);
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
        return (preg_match('/XLite_Module_(\w+)(_|$)/U', $className, $matches) && 'Abstract' !== $matches[1])
            ? $matches[1]
            : null;
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
        $result = array('', '', '', false, '');

        $data = file_get_contents($filePath);

        if (preg_match(self::CLASS_PATTERN, $data, $matches)) {

            // Class name, extends clas name and the "implements A, B, C ..." part
            foreach (array(3, 5, 7) as $index => $key) {
                $result[$index] = isset($matches[$key]) ? $matches[$key] : '';
            }
            $result[4] = $this->getClassComment($data);
            $result[3] = (bool)preg_match(self::CLASS_ENTITY_PATTERN, $result[4]);
        }

        return $result;
    }

    /**
     * Get class comment 
     * 
     * @param string $data File content
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getClassComment($data)
    {
        $comment = null;

        if (preg_match(self::CLASS_COMMENT_PATTERN, $data, $matches)) {
            $pos = strrpos($matches[1], '*/');
            $tail = str_replace(array("\t", ' '), array('', ''), substr($matches[1], $pos + 2));
            if ("\n" == $tail) {
                $end = strpos($data, $matches[0]);
                $begin = strrpos(substr($data, 0, $end), '/**');
                $comment = substr($data, $begin, $end - $begin + strlen($matches[1]));
            }
        }

        return $comment;
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
                    $this->activeModules = array_diff_key($this->activeModules, array_flip($dependencies));
                    $modulesToDisable = array_merge($modulesToDisable, array_values($dependencies));
                }
            }

            if (!empty($modulesToDisable)) {
                $modulesToDisable = array_unique($modulesToDisable);
                $query = 'UPDATE xlite_modules SET enabled = \'0\' WHERE name IN '
                    . '(' . implode(',', array_fill(0, count($modulesToDisable), '?')) . ')';
                $this->getDbHandler()->prepare($query)->execute($modulesToDisable);
            }
        }

        return isset($moduleName)
            ? isset($this->activeModules[$moduleName])
            : $this->activeModules;
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
                $this->moduleDependencies[$module] = empty($dependencies)
                    ? array()
                    : explode(',', $dependencies);
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
        if (!$isChanged) {
            echo ($this->getDependenciesErrorText($dependencies));
            die (3);
        }

        $added = empty($dependencies)
            ? array()
            : $this->calculateModulePriorities($dependencies, $subLevelDependencies, $level + 1);

        // Recursive call
        return array_merge($priorities, $added);
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

        return isset($this->modulePriorities[$moduleName])
            ? $this->modulePriorities[$moduleName]
            : 0;
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

        require_once __DIR__ . LC_DS . 'decoration.filter.php';

        $iterator = new DecoratorFilesFilter(
            new RecursiveIteratorIterator(new RecursiveDirectoryIterator(LC_CLASSES_DIR))
        );

        foreach ($iterator as $fileInfo) {

            $filePath = $fileInfo->getPathname();

            // Parse file and get class info
            list($class, $extends, $implements, $isEntity, $classComment) = $this->getClassInfo($filePath);

            // Check classes for active modules only
            // Do not include class into cache if parent defined in currently disabled module
            if (
                !empty($class)
                && $this->isActiveModule($this->getModuleNameByClassName($class))
                && (empty($extends) || $this->isActiveModule($this->getModuleNameByClassName($extends)))
            ) {

                // Get path related to the "LC_CLASSES_DIR" directory
                $relativePath = preg_replace($fileNamePattern, '$1.php', $filePath);

                // Class defined in current PHP file has a wrong name (not corresponded to file name)
                if (isset($this->classesInfo[$class])) {
                    echo (sprintf(self::CLASS_ALREADY_DEFINED_MSG, $class, $relativePath));
                    die (4);
                }

                // Save data
                $this->classesInfo[$class] = array(
                    self::INFO_FILE          => $relativePath,
                    self::INFO_CLASS_ORIG    => $class,
                    self::INFO_EXTENDS       => $extends,
                    self::INFO_EXTENDS_ORIG  => $extends,
                    self::INFO_IS_DECORATOR  => $this->isDecorator($implements),
                    self::INFO_IS_SINGLETON  => $this->isSingleton($implements),
                    self::INFO_ENTITY        => $isEntity,
                    self::INFO_CLASS_COMMENT => $classComment,
                );

                if ($this->isViewChild($classComment)) {
                    $this->viewListChilds[$relativePath] = $class;
                }

                if ($this->isPatcher($implements)) {
                    $this->templatePatches[] = $class;
                }

                if ($classComment || !preg_match(self::INTERFACE_COMMENT_PATTERN, file_get_contents($filePath))) {
                    $this->checkClassCommentAttributes($classComment, $filePath);
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
            if (
                !empty($class)
                && $this->isModuleController($class)
                && !$info[self::INFO_IS_DECORATOR]
            ) {

                // Cut module-related part from class name
                $newClass = $this->prepareModuleController($class);

                // Error - such controller is already defined in LC core or in other module
                if (isset($this->classesInfo[$newClass])) {
                    echo (sprintf(self::CONTROLLER_ERR_MSG, $this->getModuleNameByClassName($class), $class));
                    die (1);
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
                $this->classesInfo[$class][self::INFO_EXTENDS]
                    = $this->normalizedControllers[$info[self::INFO_EXTENDS]];
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
                $this->classDecorators[$info[self::INFO_EXTENDS]][$class] = $this->getModulePriority(
                    $this->getModuleNameByClassName($class)
                );
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
                echo (sprintf(self::UNDEFINED_CLASS_MSG, $class));
                die (2);
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
                if ('cli' == PHP_SAPI) {
                    $this->showPlainTextBlock();

                } elseif (isset($_REQUEST) && (!isset($_REQUEST['action']) || empty($_REQUEST['action']))) {
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

            spl_autoload_register('__lc_autoload');

            // Clear all cache
            $this->clearDoctrineCache();

            // Create model proxies directory
            mkdirRecursive(LC_PROXY_CACHE_DIR);

            // Generate models
            // TODO - rework
            //$this->generateModels();

            // Generate model proxies
            // TODO - rework
            //$this->generateModelProxies();

            // Regenerate view lists
            $this->regenerateViewLists();

            // Collect patches to DB
            $this->collectPatches();

            if (
                !defined('SILENT_CACHE_REBUILD')
                && 'cli' != PHP_SAPI
                && isset($_SERVER['HTTP_HOST'])
                && isset($_SERVER['REQUEST_URI'])
            ) {

                $isHttps = (isset($_SERVER['HTTPS']) && in_array(strtolower($_SERVER['HTTPS']), array('on', '1')))
                    || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443');
                $redirectUrl = ($isHttps ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                if (@parse_url($redirectUrl) && empty($_REQUEST['action'])) {
                    $this->showJavaScriptBlock($redirectUrl);
                    die (0);
                }
            }
        }
    }

    /**
     * Clear cache 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function clearDoctrineCache()
    {
        $driver = $this->getDoctrineCacheDriver();
        if ($driver) {
            $driver->deleteAll();
        }
    }

    /**
     * Get cache driver 
     * 
     * @return \Doctrine\Common\Cache\AbstractCache
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDoctrineCacheDriver()
    {
        if (is_null($this->cacheDriver)) {

            $options = $this->getConfigOptions('cache');
            if (!$options || !is_array($options)) {
                $options = array('type' => false);
            }
            
            $this->cacheDriver = XLite_Core_Database::getCacheDriverByOptions($options);
        }

        return $this->cacheDriver;
    }

    /**
     * Get entity manager 
     * 
     * @return Doctrine\ORM\EntityManager
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getEntityManager()
    {
        if (is_null($this->em)) {
            $config = new \Doctrine\ORM\Configuration;

            $config->setMetadataDriverImpl(
                $config->newDefaultAnnotationDriver(LC_MODEL_CACHE_DIR)
            );

            // Set proxy settings
            $config->setProxyDir(LC_PROXY_CACHE_DIR);
            $config->setProxyNamespace(LC_MODEL_PROXY_NS);

            $cache = new \Doctrine\Common\Cache\ArrayCache;
            $config->setMetadataCacheImpl($cache);
            $config->setQueryCacheImpl($cache);

            $this->em = \Doctrine\ORM\EntityManager::create($this->getDSN(), $config);
        }

        return $this->em;
    }

    /**
     * Get Doctrine style DSN 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDSN()
    {
        $options = $this->getConfigOptions('database_details');

        $dsnFields = array(
            'host'        => 'hostspec',
            'port'        => 'port',
            'unix_socket' => 'socket',
            'dbname'      => 'database',
        );
        $dsnList = array(
            'driver' => 'pdo_mysql',
        );

        foreach ($dsnFields as $pdoOption => $lcOption) {

            if (!empty($options[$lcOption])) {
                $dsnList[$pdoOption] = $options[$lcOption];
            }
        }

        $dsnList['path'] = $this->getConnectionString($options);
        $dsnList['user'] = $options['username'];
        $dsnList['password'] = $options['password'];

        return $dsnList;
    }
    
    /**
     * Get metadata list
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMetadatas()
    {
        $em = $this->getEntityManager();

        /*
        $cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory($em);
        $metadatas = $cmf->getAllMetadata();
        */

        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        // TODO - check - need or not?
        //$metadatas = MetadataFilter::filter($metadatas, $input->getOption('filter'));

        return $metadatas;
    }

    /**
     * Generate model proxies 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function generateModelProxies()
    {
        mkdirRecursive(LC_PROXY_CACHE_DIR, 0755);
        $this->getEntityManager()
            ->getProxyFactory()
            ->generateProxyClasses($this->getMetadatas(), LC_PROXY_CACHE_DIR);
    }

    /**
     * Generate models 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function generateModels()
    {
        $entityGenerator = new \Doctrine\ORM\Tools\EntityGenerator();

        $entityGenerator->setGenerateAnnotations(true);
        $entityGenerator->setGenerateStubMethods(true);
        $entityGenerator->setRegenerateEntityIfExists(true);
        $entityGenerator->setUpdateEntityIfExists(true);
        $entityGenerator->setNumSpaces(4);
        $entityGenerator->setClassToExtend('XLite_Model_Doctrine_AbstractEntity');

        $entityGenerator->generate($this->getMetadatas(), LC_MODEL_CACHE_DIR);
    }

    /**
     * Check class comment attributes 
     * 
     * @param string $comment  Class comment
     * @param string $filePath File path
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkClassCommentAttributes($comment, $filePath)
    {
        $errors = array();

        if (!is_string($comment)) {
            $errors[] = 'The class is not commented';

        } else {
            $attributes = $this->parseComment(substr(trim($comment), 0, -2));

            // Check required attributes
            foreach (array('package', 'see', 'since') as $a) {
                if (!isset($attributes[$a]) || 0 == strlen($attributes[$a][0])) {
                    $errors[] = sprintf($this->errorMsgAttribute, '@' . $a);
                }
                unset($attributes[$a]);
            }

            // Check @ListChild
            if (isset($attributes['listchild'])) {
                $lists = array();
                foreach ($attributes['listchild'] as $value) {
                    $lists[] = $this->parseCommentAttribute($value);
                }

                foreach ($lists as $list) {
                    if (!isset($list['list'])) {
                        $errors[] = '@ListChild attribute has not "list" parameter';

                    } elseif (isset($list['zone']) && !in_array($list['zone'], array('', true, 'customer', 'admin'))) {
                        $errors[] = '@ListChild attribute has "zone" parameter with wrong value';
                    }
                }

                unset($attributes['listchild']);
            }

            $hasEntity = false;
            $hasDoctrineAttribute = false;
            foreach ($this->doctrineClassAttributes as $a) {
                $key = strtolower($a);
                if (isset($attributes[$key])) {
                    if ('entity' == $key) {
                        $hasEntity = true;
                    }
                    $hasDoctrineAttribute = true;
                    unset($attributes[$key]);
                }
            }

            if ($hasDoctrineAttribute && !$hasEntity) {
                $errors[] = 'Class has not @Entity attribute, but has some Doctrine class attributes';
            }

            // Remove optional attributes
            foreach (array('subpackage') as $a) {
                if (isset($attributes[$a])) {
                    unset($attributes[$a]);
                }
            }

            // Unknown attributes
            foreach ($attributes as $a => $attr) {
                $errors[] = 'Class has unknown attribute @' . $a;
            }

        }

        if ($errors) {
            $this->addDecorationError($filePath, $errors);
        }
    }

    /**
     * Add error to special log
     * 
     * @param string $filePath Path of invalid file
     * @param string $error    Error message
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addDecorationError($filePath, $error)
    {
        static $path = false;

        if (!$path) {
            $path = LC_VAR_DIR . 'log' . LC_DS . sprintf($this->errorLogFilename, date('Y-m-d'));
            if (!file_exists(dirname($path))) {
                mkdirRecursive(dirname($path));
            }

            if (!file_exists($path) || 16 > filesize($path)) {
                file_put_contents($path, '<' . '?php die(1); ?' . '>' . "\n");
            }
        }

        if (is_array($error)) {
            $error = implode("\n\t", $error);
        }

        $msg = date('[H:i:s]') . ' ';

        $info = pathinfo($filePath);

        switch ($info['extension']) {
            case 'php':
                $msg .= 'Repository file';
                break;

            case 'tpl':
                $msg .= 'Template';
                break;

            case 'js':
                $msg .= 'Javascript repository';
                break;

            case 'css':
                $msg .= 'CSS styles repository';
                break;

            default:
                $msg .= 'File';
        }

        $msg .= ': ' . $filePath . '; Errors:' . "\n\t" . $error . "\n\n";

        file_put_contents($path, $msg, FILE_APPEND);
    }
    
    /**
     * Regenerate view lists 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function regenerateViewLists()
    {
        // Truncate old
        foreach (XLite_Core_Database::getRepo('XLite_Model_ViewList')->findAll() as $l) {
            XLite_Core_Database::getEM()->remove($l);
        }
        XLite_Core_Database::getEM()->flush();

        $this->viewListPreprocessors = array();

        // Create new
        foreach ($this->viewListChilds as $relativePath => $class) {

            $class = $this->getFinalClass($class);

            if (!$class) {
                continue;
            }

            $comment = $this->getClassComment(file_get_contents(LC_CLASSES_CACHE_DIR . $relativePath));

            foreach ($this->getListChildsByComment(substr(trim($comment), 0, -2)) as $list) {

                if (isset($list['class']) && !isset($this->classesInfo[$list['class']])) {
                    $this->addDecorationError(
                        LC_CLASSES_CACHE_DIR . $relativePath,
                        'Class ' . $list['class'] . ' is not found (specified in @ListChild comment attribute)'
                    );

                } else {

                    XLite_Core_Database::getEM()->persist(
                        $this->createViewList($list, $class)
                    );
                }
            }
        }

        // Assemble anniotaions from templates
        $this->assembleTemplateLists();

        XLite_Core_Database::getEM()->flush();

        // Global modules preprocessing
        foreach (array_keys($this->classesInfo) as $class) {
            if (preg_match('/^XLite_Module_\w+_Main$$/', $class) && method_exists($class, 'modifyViewLists')) {
                $class::modifyViewLists();
            }
        }

        // Static preprocessing
        foreach ($this->viewListPreprocessors as $class => $lists) {
            foreach ($lists as $list => $preprocessors) {
                $data = XLite_Core_Database::getQB()
                    ->select('v')
                    ->from('XLite_Model_ViewList', 'v')
                    ->where('v.class = :class AND v.list = :list')
                    ->setParameters(array('class' => $class, 'list' => $list))
                    ->getQuery()
                    ->getResult();

                if ($data) {
                    foreach ($preprocessors as $preprocessor) {
                        $preprocessor[0]::$preprocessor[1]($data);
                    }
                }
            }
        }

        XLite_Core_Database::getEM()->flush();

        $this->viewListPreprocessors = array();
    }

    /**
     * Create view list record
     * 
     * @param array  $list  List data
     * @param string $class Widget class name
     *  
     * @return XLite_Model_ViewList
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function createViewList(array $list, $class = null)
    {
        if (!isset($list['class'])) {
            $list['class'] = '';
        }

        $viewList = new XLite_Model_ViewList();


        $viewList->class = $list['class'];
        $viewList->list = $list['list'];

        if (isset($list['zone'])) {
            $viewList->zone = $list['zone'];

        }

        if (isset($list['first'])) {
            $viewList->weight = $viewList::FIRST_POSITION;

        } elseif (isset($list['last']) || !isset($list['weight'])) {
            $viewList->weight = $viewList::LAST_POSITION;

        } else {
            $viewList->weight = min(
                $viewList::LAST_POSITION,
                max(
                    $viewList::FIRST_POSITION,
                    intval($list['weight'])
                )
            );
        }

        if ($class) {
            $viewList->child = $class;
        }

        if (isset($list['controller']) && $list['controller']) {
            $tmp = explode('::', $list['controller'], 2);

            $preprocessor = false;

            if ($tmp[0] && !isset($tmp[1]) && $class) {
                $preprocessor = array($class, $list['controller']);

            } elseif ($tmp[0] && isset($tmp[1]) && $tmp[1] && isset($this->classesInfo[$tmp[0]])) {
                $preprocessor = $tmp;
            }

            if ($preprocessor) {
                if (!isset($this->viewListPreprocessors[$list['class']])) {
                    $this->viewListPreprocessors[$list['class']] = array();
                }

                if (!isset($this->viewListPreprocessors[$list['class']][$list['list']])) {
                    $this->viewListPreprocessors[$list['class']][$list['list']] = array();
                }

                $this->viewListPreprocessors[$list['class']][$list['list']][] = $preprocessor;
            }
        }

        return $viewList;
    }

    /**
     * Get list childs by comment 
     * 
     * @param string $comment Class or template comment
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getListChildsByComment($comment)
    {
        $attributes = $this->parseComment($comment);
        $lists = array();

        if (isset($attributes['listchild'])) {
            foreach ($attributes['listchild'] as $value) {
                $list = $this->parseCommentAttribute($value);
                if (isset($list['list']) && $list['list']) {
                    $lists[] = $list;
                }
            }
        }

        return $lists;
    }

    /**
     * Assemble templates list childs
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function assembleTemplateLists()
    {
        $sep = preg_quote(LC_DS, '/');
        $pattern = '/^'
            . preg_quote(LC_SKINS_DIR, '/')
            . '\w+' . $sep
            . '\w+' . $sep
            . 'modules' . $sep
            . '(\w+)' . $sep
            . '/Ss';

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(LC_SKINS_DIR));
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                $pathInfo = pathinfo($fileInfo->getPathname());

                if (
                    !empty($pathInfo['extension'])
                    && 'tpl' === strtolower($pathInfo['extension'])
                    && (!preg_match($pattern, $fileInfo->getPathname(), $match) || $this->getActiveModules($match[1]))
                ) {
                    $this->collectTemplateLists($fileInfo->getPathname());
                }
            }
        }
    }

    /**
     * Collect template list childs
     * 
     * @param string $path Template path
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function collectTemplateLists($path)
    {
        if (preg_match('/\{\*\*(.+)\*\}/USs', file_get_contents($path), $match)) {

            $path = substr($path, strlen(LC_SKINS_DIR));
            $tmp = explode(LC_DS, $path);
            $zone = 'admin' == $tmp[0]
                ? XLite_Model_ViewList::ADMIN_INTERFACE
                : XLite_Model_ViewList::CUSTOMER_INTERFACE;

            foreach ($this->getListChildsByComment(trim($match[1])) as $list) {

                if (isset($list['class']) && !isset($this->classesInfo[$list['class']])) {

                    $this->addDecorationError(
                        $path,
                        'Class ' . $list['class'] . ' is not found (specified in @ListChild comment attribute)'
                    );

                } else {

                    if (!isset($list['zone'])) {
                        $list['zone'] = $zone;
                    }

                    $viewList = $this->createViewList($list);
                    $viewList->tpl = $path;

                    XLite_Core_Database::getEM()->persist($viewList);
                }
            }
        }
    }

    /**
     * Parse comment attributes
     * 
     * @param string $comment Comment
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseComment($comment)
    {
        $comment = preg_replace('/^\s+\*\s/Sm', '', $comment);
        $parts = preg_split('/^\@/Sm', $comment);

        array_shift($parts);

        $attributes = array();
        foreach ($parts as $part) {
            $part = trim(str_replace("\n", ' ', $part));
            list($key, $value) = preg_split('/\W/Ss', $part, 2);

            $key = strtolower($key);

            if (!isset($attributes[$key])) {
                $attributes[$key] = array();
            }

            $attributes[$key][] = trim($value);
        }

        return $attributes;
    }

    /**
     * Parse comment attribute 
     * 
     * @param string $value Comment attribute
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseCommentAttribute($value)
    {
        $parameters = array();

        $parts = preg_split('/(?:\(|\s|,)([\w_]+)/USs', substr($value, 0, -1), -1, PREG_SPLIT_DELIM_CAPTURE);
        array_shift($parts);

        for ($i = 0; $i < count($parts); $i += 2) {
            $tmp = explode('=', trim($parts[$i] . $parts[$i + 1]), 2);
            $parameters[$tmp[0]] = isset($tmp[1])
                ? preg_replace('/^[^"]*"(.+)"[^"]*$/Ss', '$1', $tmp[1])
                : true;
        }

        return $parameters;
    }

    /**
     * Get final class by class-decorator
     * 
     * @param string $class Class-decorator
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFinalClass($class)
    {
        if (!isset($this->classesInfo[$class])) {

            // Class not found
            $result = null;

        } elseif (isset($this->classDecorators[$class])) {

            // Class already final
            $result = $class;

        } elseif (
            $this->classesInfo[$class][self::INFO_EXTENDS_ORIG] != $this->classesInfo[$class][self::INFO_EXTENDS]
            && isset($this->classDecorators[$this->classesInfo[$class][self::INFO_EXTENDS_ORIG]])
        ) {

            // Class is decorator
            $result = $this->classesInfo[$class][self::INFO_EXTENDS_ORIG];

        } else {
            $result = $class;
        }

        return $result;
    }

    /**
     * Collect template patches
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function collectPatches()
    {
        // Truncate old
        foreach (XLite_Core_Database::getRepo('XLite_Model_TemplatePatch')->findAll() as $r) {
            XLite_Core_Database::getEM()->remove($r);
        }
        XLite_Core_Database::getEM()->flush();

        // Create new
        foreach ($this->templatePatches as $class) {
            $patches = $class::getPatches();
            if (isset($patches[$class::PATCHER_CELL_TYPE])) {
                $patches = array($patches);
            }

            foreach ($patches as $patch) {

                $valid = true;

                $templatePatch = new XLite_Model_TemplatePatch();

                $templatePatch->patch_type = isset($patch[$class::PATCHER_CELL_TYPE])
                    ? $patch[$class::PATCHER_CELL_TYPE]
                    : $class::CUSTOM_PATCH_TYPE;
                list($templatePatch->zone, $templatePatch->lang, $templatePatch->tpl) = explode(
                    ':',
                    $patch[$class::PATCHER_CELL_TPL],
                    3
                );

                if (!$templatePatch->tpl) {
                    continue;
                }

                if (
                    $class::XPATH_PATCH_TYPE == $patch[$class::PATCHER_CELL_TYPE]
                    && isset($patch[$class::XPATH_CELL_QUERY])
                    && $patch[$class::XPATH_CELL_QUERY]
                    && isset($patch[$class::XPATH_CELL_BLOCK])
                ) {
                    $templatePatch->xpath_query = $patch[$class::XPATH_CELL_QUERY];
                    $templatePatch->xpath_insert_type = isset($patch[$class::XPATH_CELL_QUERY_INSERT_TYPE])
                        ? $patch[$class::XPATH_CELL_QUERY_INSERT_TYPE]
                        : $class::XPATH_INSERT_BEFORE;
                    $templatePatch->xpath_block = $patch[$class::XPATH_CELL_BLOCK];

                } elseif (
                    $class::REGEXP_PATCH_TYPE == $patch[$class::PATCHER_CELL_TYPE]
                    && isset($patch[$class::REGEXP_CELL_PATTERN])
                    && $patch[$class::REGEXP_CELL_PATTERN]
                    && isset($patch[$class::REGEXP_CELL_REPLACE])
                ) {
                    $templatePatch->regexp_pattern = $patch[$class::REGEXP_CELL_PATTERN];
                    $templatePatch->regexp_replace = $patch[$class::REGEXP_CELL_REPLACE];

                } elseif (
                    $class::CUSTOM_PATCH_TYPE == $patch[$class::PATCHER_CELL_TYPE]
                    && isset($patch[$class::CUSTOM_CELL_CALLBACK])
                    && $patch[$class::CUSTOM_CELL_CALLBACK]
                    && method_exists($class, $class::CUSTOM_CELL_CALLBACK)
                ) {
                    $templatePatch->custom_callback = $class . '::' . $patch[$class::CUSTOM_CELL_CALLBACK];

                } else {
                    $valid = false;
                    // TODO - add decoration error logging
                }

                if ($valid) {
                    XLite_Core_Database::getEM()->persist($templatePatch);
                }
            }

            XLite_Core_Database::getEM()->flush();
        }
    }

    /**
     * Destructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __destruct()
    {
        // This db connection is not needed for other classes
        $this->dbHandler = null;
        if ($this->em) {
            $this->em->getConnection()->close();
            $this->em = null;
        }
    }
}
