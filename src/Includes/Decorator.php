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

namespace Includes;

/**
 * Decorator - classes cache builder
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Decorator extends Decorator\ADecorator
{
    /**
     * Pattern to parse PHP files
     */
    const CLASS_PATTERN = '/\s*((?:abstract|final)\s+)?(class|interface)\s+([\w\\\]+)(\s+extends\s+([\w\\\]+))?(\s+implements\s+([\w\\\]+(?:\s*,\s*[\w\\\]+)*))?\s*(\/\*.*\*\/)?\s*{/USsi';

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
     * Messages
     */
    const CONTROLLER_ERR_MSG        = 'Module "%s" has defined controller class "%s" which does not decorate any other one and has an ambigous name';
    const UNDEFINED_CLASS_MSG       = 'Decorator: undefined class - "%s"';
    const CLASS_ALREADY_DEFINED_MSG = 'Class "%s" is already defined in file "%s"';

    /*
     * The name of file-indicator of successful cache building
     */
    const LC_CACHE_BUILD_INDICATOR = '.cache_done';

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
     * Multilanguages classes
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $multilangs = array();

    /**
     * Optional class annotations attributes 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $optionalClassAttributes = array('subpackage', 'mappedsuperclass');

    /**
     * Method name translation records
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $to = array(
        'Q', 'W', 'E', 'R', 'T',
        'Y', 'U', 'I', 'O', 'P',
        'A', 'S', 'D', 'F', 'G',
        'H', 'J', 'K', 'L', 'Z',
        'X', 'C', 'V', 'B', 'N',
        'M',
    );

    /**
     * Method name translation patterns
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $from = array(
        '_q', '_w', '_e', '_r', '_t',
        '_y', '_u', '_i', '_o', '_p',
        '_a', '_s', '_d', '_f', '_g',
        '_h', '_j', '_k', '_l', '_z',
        '_x', '_c', '_v', '_b', '_n',
        '_m',
    );

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
        return str_replace('\\', LC_DS, $class) . '.php';
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
    protected function parseClassFile(array $info, $savePath, $class)
    {
        $content = isset($info[self::INFO_FILE]) ? trim(file_get_contents(LC_CLASSES_DIR . $info[self::INFO_FILE])) : '';

        $namespace = explode(LC_DS, $savePath);
        array_pop($namespace);
        $namespace = implode('\\', $namespace);

        if (!empty($info[self::INFO_IS_ROOT_CLASS]) && preg_match(self::CLASS_PATTERN, $content, $matches)) {

            // Top level class in decorator chain has an empty body
            $content = '<?php' . "\n\n"
                . 'namespace ' . $namespace . ';' . "\n\n"
                . trim($info[self::INFO_CLASS_COMMENT]) . "\n"
                . $matches[1] . 'class ' 
                . (isset($info[self::INFO_CLASS]) ? preg_replace('/^.+\\\([^\\\]+)$/Ss', '$1', $info[self::INFO_CLASS]) : $matches[3])
                . (isset($info[self::INFO_EXTENDS]) && $info[self::INFO_EXTENDS] ? ' extends ' . $this->buildFullExtends($info[self::INFO_EXTENDS]) : '')
                . (isset($matches[6]) ? $matches[6] : '') . "\n" . '{' . "\n" . '}' . "\n";

        } else {

            // Replace class and name of class which extends the current one
            $replace = "\n" 
                . (isset($info[self::INFO_CLASS_TYPE]) ? $info[self::INFO_CLASS_TYPE] . ' ' : '$1') . '$2 ' 
                . (isset($info[self::INFO_CLASS]) ? preg_replace('/^.+\\\([^\\\]+)$/Ss', '$1', $info[self::INFO_CLASS]) : '$3') 
                . (isset($info[self::INFO_EXTENDS]) && $info[self::INFO_EXTENDS] ? ' extends ' . $this->buildFullExtends($info[self::INFO_EXTENDS]) : '$4') 
                . '$6' . "\n" . '{';
            $content = preg_replace(self::CLASS_PATTERN, $replace, $content);

            $content = preg_replace('/^namespace (.+);/Sm', 'namespace ' . $namespace . ';', $content);

            // Add MappedSuperclass attribute
            if ($this->isDecoratedEntity($info[self::INFO_CLASS_ORIG])) {
                $comment = $this->getClassComment($content);
                if ($comment) {
                    $newComment = $this->modifyParentEntityClassComment($comment);
                    $content = str_replace($comment, $newComment, $content);

                } elseif (preg_match(self::CLASS_PATTERN, $content, $matches)) {
                    $content = str_replace(
                        $matches[0],
                        '/**' . "\n" . ' * @MappedSuperclass' . "\n" . ' */' . "\n" . $matches[0],
                        $content
                    );
                }
            }

            // Prepare static members
            \Includes\Decorator\Utils\StaticRoutines::checkForStaticConstructor($info, $content);
        }

        // Change name of normalized classes in PHP code
        foreach ($this->normalizedControllers as $oldClass => $newClass) {
            $content = preg_replace('/' . preg_quote($oldClass, '/') . '/i', $newClass, $content);
        }

        return $content;
    }

    /**
     * Crop class name by uses namesapce
     * 
     * @param string $name      Class name
     * @param string $namespace Uses namespace
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function buildFullExtends($name)
    {
        return '\\' . $name;
    }

    /**
     * Check - is class decorated entity or not
     * 
     * @param string $class Class name
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isDecoratedEntity($class)
    {
        static $cache = null;

        if (!isset($cache)) {
            $cache = array();

            foreach ($this->classDecorators as $root => $list) {
                if (isset($this->classesInfo[$root]) && $this->classesInfo[$root][self::INFO_ENTITY]) {
                    $cache[] = $root;
                    $cache = array_merge($cache, array_keys($list));
                }
            }
        }

        return in_array($class, $cache);
    }

    /**
     * Modify class comment (insert MappedSuperclass attribute)
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
        $comment = preg_replace(
            '/^ \* @(?:' . implode('|', $this->doctrineClassAttributes) . ')(?:\s+\(?:.+\))?\s*$/UiSm',
            '',
            $comment
        );
        $comment = preg_replace(
            '/ \* @(?:' . implode('|', $this->doctrineClassAttributes) . ')\s+\(.+ \* \)/UiSs',
            '',
            $comment
        );

        $comment = preg_replace('/' . "\n" . '{2,999}/Ss', "\n", $comment);
        $comment = preg_replace('/ \*\//Ssi', ' * @MappedSuperclass' . "\n" . '$0', $comment);

        return $comment;
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
        return preg_match('/XLite\\\Module\\\[\w]+\\\Controller\\\[\w\\\]*/Ss', $class);
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
        return preg_replace('/XLite\\\(Module\\\[\w]+\\\)Controller(\\\[\w\\\]*)/Ss', 'XLite\\Controller$2', $class);
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
        return $this->isImplements('\XLite\Base\IDecorator', $implements);
    }

    /**
     * Check if current class implements the "XLite\Base\IViewChild" interface
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
     * Check if current class implements the "XLite\Base\IPatcher" interface
     * 
     * @param array $implements list of implemented inerfaces
     *  
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function isPatcher($implements)
    {
        return $this->isImplements('\XLite\Base\IPatcher', $implements);
    }

    /**
     * Check if current class is multilanguage owner
     * 
     * @param string $extends Extends directive
     *  
     * @return bool
     * @access protected
     * @since  3.0
     */
    protected function isMultilang($extends)
    {
        return 'XLite\Model\Base\I18n' == $extends;
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
        return (preg_match('/XLite\\\Module\\\(\w+)(\\\|$)/Ss', $className, $matches) && 'AModule' !== $matches[1])
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
        $result = array('', '', '', false, '', '');

        $data = file_get_contents($filePath);

        if (preg_match(self::CLASS_PATTERN, $data, $matches)) {

            // Class name, extends class name and the "implements A, B, C ..." part
            foreach (array(3, 5, 7) as $index => $key) {
                $result[$index] = isset($matches[$key]) ? $matches[$key] : '';
            }
            $result[4] = $this->getClassComment($data);
            $result[3] = (bool)preg_match(self::CLASS_ENTITY_PATTERN, $result[4]);

            // Namespace
            if (preg_match('/^namespace (\S+);/Sm', $data, $m)) {
                $result[5] = substr($m[1], 0, 1) == '\\'
                    ? substr($m[1], 1)
                    : $m[1];
            }
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

            $this->mutualModules = \Includes\Utils\Database::fetchAll(
                'SELECT name, mutual_modules FROM xlite_modules WHERE enabled = \'1\' AND mutual_modules != \'\'',
                \PDO::FETCH_ASSOC | \PDO::FETCH_GROUP | \PDO::FETCH_UNIQUE | \PDO::FETCH_COLUMN
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

            $this->activeModules = \Includes\Utils\Database::fetchAll(
                'SELECT name, \'1\' FROM xlite_modules WHERE enabled = \'1\'',
                \PDO::FETCH_ASSOC | \PDO::FETCH_GROUP | \PDO::FETCH_UNIQUE | \PDO::FETCH_COLUMN
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
                \Includes\Utils\Database::execute($query, $modulesToDisable);
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
                $dependencies = \Includes\Utils\Database::fetchColumn(
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
        foreach (\Includes\Utils\FileFilter::filterByExtension(LC_CLASSES_DIR, 'php') as $fileInfo) {

            $filePath = $fileInfo->getPathname();

            // Parse file and get class info
            list($class, $extends, $implements, $isEntity, $classComment, $namespace) = $this->getClassInfo($filePath);

            $key = $class;
            if ($namespace) {
                $key = $namespace . '\\' . $key;
            }

            // Check classes for active modules only
            // Do not include class into cache if parent defined in currently disabled module
            if (
                !empty($class)
                && $this->isActiveModule($this->getModuleNameByClassName($key))
                && (empty($extends) || $this->isActiveModule($this->getModuleNameByClassName($extends)))
            ) {

                // Get path related to the "LC_CLASSES_DIR" directory
                $relativePath = preg_replace('/^' . preg_quote(LC_CLASSES_DIR, '/') . '(.*)\.php$/i', '$1.php', $filePath);

                // Class defined in current PHP file has a wrong name (not corresponded to file name)
                if (isset($this->classesInfo[$key])) {
                    echo (sprintf(self::CLASS_ALREADY_DEFINED_MSG, $key, $relativePath));
                    die (4);
                }

                $e = '';

                if (0 === strpos($extends, '\Doctrine\\')) {
                    $e = $extends;

                } elseif (0 === strpos($extends, '\XLite\\')) {
                    $e = substr($extends, 1);

                } elseif ($extends) {
                    $e = ($namespace ? $namespace : 'XLite') . '\\' . $extends;
                }

                // Save data
                $this->classesInfo[$key] = array(
                    self::INFO_FILE          => $relativePath,
                    self::INFO_CLASS_ORIG    => $key,
                    self::INFO_EXTENDS       => $e,
                    self::INFO_EXTENDS_ORIG  => $e,
                    self::INFO_IS_DECORATOR  => $this->isDecorator($implements),
                    self::INFO_ENTITY        => $isEntity,
                    self::INFO_CLASS_COMMENT => $classComment,
                );

                if ($this->isViewChild($classComment)) {
                    $this->viewListChilds[$relativePath] = $key;
                }

                if ($this->isPatcher($implements)) {
                    $this->templatePatches[] = $key;
                }

                if ($this->isMultilang($e)) {
                    $this->multilangs[] = $key;
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
        $fn = $this->getFileByClass($class);
        $fileName = LC_CLASSES_CACHE_DIR . $fn;
        $dirName  = dirname($fileName);

        if (!file_exists($dirName) || !is_dir($dirName)) {
            \Includes\Utils\FileManager::mkdirRecursive($dirName, 0755);
        }

        file_put_contents($fileName, $this->parseClassFile($info, $fn, $class));
        chmod($fileName, 0644);
    }

    /**
     * Check and (if needed) rebuild cache
     * 
     * @return void
     * @access public
     * @since  3.0
     */
    public function buildCache()
    {
        // Prepare classes list
        $this->createClassTree();
        $this->normalizeModuleControllerNames();
        $this->createDecoratorTree();
        $this->mergeClassAndDecoratorTrees();

        // Write file to the cache directory
        foreach ($this->classesInfo as $class => $info) {
            $this->writeClassFile($class, $info);
        }

        // Clear all cache
        $this->clearDoctrineCache();

        // Postbuild multilanguage classes
        $this->buildMultilangs();

        // Generate models
        $this->generateModels();

        // Regenerate view lists
        $this->regenerateViewLists();

        // Collect patches to DB
        $this->collectPatches();

        // Store files in APC
        if (function_exists('apc_compile_file')) {
            apc_clear_cache();
            foreach ($this->classesInfo as $class => $info) {
                apc_compile_file(LC_CLASSES_CACHE_DIR . $this->getFileByClass($class));
            }
        }

        file_put_contents(LC_CLASSES_CACHE_DIR . self::LC_CACHE_BUILD_INDICATOR, date('r'));
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
        if (!isset($this->cacheDriver)) {
            $this->cacheDriver = \XLite\Core\Database::getCacheDriverByOptions(
                \Includes\Utils\ConfigParser::getOptions('cache')
            );
        }

        return $this->cacheDriver;
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
        $entityGenerator->setRegenerateEntityIfExists(false);
        $entityGenerator->setUpdateEntityIfExists(true);
        $entityGenerator->setNumSpaces(4);
        $entityGenerator->setClassToExtend('\XLite\Model\AEntity');

        $entityGenerator->generate(
            \Includes\Decorator\Utils\Doctrine\EntityManager::getAllMetadata(),
            LC_CLASSES_CACHE_DIR
        );

        $this->postGenerateModels();
    }

    /**
     * Additional models generation
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postGenerateModels()
    {
        foreach (\Includes\Decorator\Utils\Doctrine\EntityManager::getAllMetadata() as $metadata) {
            $path = LC_CLASSES_CACHE_DIR . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $metadata->name) . '.php';
            $data = file_get_contents($path);

            $additionalMethods = array();

            $relations = array();

            // Add set<RelationName>
            foreach ($metadata->associationMappings as $an => $av) {

                if (
                    $av instanceof \Doctrine\ORM\Mapping\OneToManyMapping
                    || $av instanceof \Doctrine\ORM\Mapping\ManyToManyMapping
                ) {
                    $relations[] = $an;

                    $varName = str_replace(self::$from, self::$to, $an);
                    $methodName = ucfirst($varName);
                    $additionalMethods[] = <<<PHP
    /**
     * Set $an
     *
     * @param \\Doctrine\\Common\\Collections\\Collection \$$varName
     *
     * @return void
     * @access public
     */
    public function set$methodName(\\Doctrine\\Common\\Collections\\Collection \$$varName)
    {
        \$this->$an = \$$varName;
    }
PHP;
                }
            }

            // Constructor update
            if ($relations) {
                $relationsInit = '        $this->'
                    . implode(' = new \Doctrine\Common\Collections\ArrayCollection();' . "\n" . '        $this->', $relations)
                    . ' = new \Doctrine\Common\Collections\ArrayCollection();' . "\n";

                $pos = strpos(' __construct(', $data);
                if (false !== $pos) {
                    $pos = strpos('    }' . "\n", $data, $pos);
                    if (false !== $pos) {
                        $data = substr($data, 0, $pos) . $relationsInit . substr($data, $pos);
                    }

                } else {
                    $additionalMethods[] = <<<PHP
    /**
     * Constructor
     *
     * @param array \$data entity properties
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array \$data = array())
    {
$relationsInit

        parent::__construct(\$data);
    }
PHP;

                }
            }

            if ($additionalMethods) {
                $data = str_replace("\n" . '}', "\n\n" . implode("\n\n", $additionalMethods) . "\n\n" . '}', $data);
            }

            file_put_contents($path, $data);
        }
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
            foreach ($this->optionalClassAttributes as $a) {
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
                \Includes\Utils\FileManager::mkdirRecursive(dirname($path));
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
     * Build multilanguages classes
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function buildMultilangs()
    {
        foreach ($this->multilangs as $class) {

            $decorated = isset($this->classDecorators[$class]);
            $fn = LC_CLASSES_CACHE_DIR . $this->classesInfo[$class]['file'];
            if (isset($this->classDecorators[$class])) {
                $fn = preg_replace('/\.php/S', 'Abstract$0', $fn);
            }

            $tclass = $class . 'Translation';
            if (!isset($this->classesInfo[$tclass])) {
                // TODO - add error logging
                continue;
            }

            $tfn = LC_CLASSES_CACHE_DIR . $this->classesInfo[$tclass]['file'];
            if (isset($this->classDecorators[$tclass])) {
                $tfn = preg_replace('/\.php/S', 'Abstract$0', $tfn);
            }

            $tfiles = array($tfn);
            if (isset($this->classDecorators[$tclass])) {
                foreach ($this->classDecorators[$tclass] as $f) {
                    $tfiles[] = LC_CLASSES_CACHE_DIR . $f['file'];
                }
            }

            $translationFields = array();

            foreach ($tfiles as $f) {
                $translationFields = array_merge(
                    $translationFields,
                    $this->collectModelFields($f)
                );
            }

            $data = file_get_contents($fn);
            $id = null;

            if (preg_match('/\s+\*\s@translationkey\s/Ssi', $data, $match)) {
                $pos = strpos($data, $match[0]);
                if (preg_match('/^\s+protected \$([^\s;]+)/Sm', substr($data, $pos), $match)) {
                    $id = $match[1];
                }

            } elseif (preg_match('/\s+\*\s@id\s/Ssi', $data, $match)) {
                $pos = strpos($data, $match[0]);
                if (preg_match('/^\s+protected \$([^\s;]+)/Sm', substr($data, $pos), $match)) {
                    $id = $match[1];
                }
            }

            $block = <<<DATA
    /**
     * Translations (relation)
     * AUTOGENERATED
     * 
     * @var    Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @OneToMany (targetEntity="$tclass", mappedBy="owner", cascade={"persist","remove"})
     */
    protected \$translations;
DATA;
            $this->addPropertyToRepository($fn, $block);

            foreach ($translationFields as $field) {

                $prefix = str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));

                $block = <<<DATA
    /**
     * Get $field
     * AUTOGENERATED
     * 
     * @return string
     * @access public
     */
    public function get$prefix()
    {
        return \$this->getSoftTranslation()->get$prefix();
    }

    /**
     * Set $field
     * AUTOGENERATED
     * 
     * @param string \$value Value
     *  
     * @return void
     * @access public
     */
    public function set$prefix(\$value)
    {
        \$this->getTranslation(\$this->editLanguage)->set$prefix(\$value);
    }

DATA;
                $this->addMethodToRepository($fn, $block);
            }
            

            $block = <<<DATA
    /**
     * Translation owner (relation)
     * AUTOGENERATED
     * 
     * @var    $class
     * @access protected
     * @ManyToOne (targetEntity="$class", inversedBy="translations")
     * @JoinColumn (name="id", referencedColumnName="$id")
     */
    protected \$owner;

DATA;
            $this->addPropertyToRepository($tfn, $block);
        }
    }

    /**
     * Collect model fields 
     * 
     * @param string $fn Class repository path
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function collectModelFields($fn)
    {
        $data = file_get_contents($fn);
        $result = array();

        if (preg_match_all('/\s+\*\/\s+protected \$([^;\s]+)/Ss', $data, $match)) {
            foreach ($match[1] as $k => $v) {
                $pos = strpos($data, $match[0][$k]);
                $begin = strrpos(substr($data, 0, $pos), '/**');
                if (preg_match('/\*\s@column/Ssi', substr($data, $begin, $pos - $begin))) {
                    $result[] = $v;
                }
            }
        }

        return $result;
    }

    /**
     * Add class property to class repository 
     * 
     * @param string $fn    Path
     * @param string $block Property block
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addPropertyToRepository($fn, $block)
    {
        $data = file_get_contents($fn);

        if (preg_match_all('/^\s+(?:protected|public|private)\s+\$\S+/Sm', $data, $match)) {
            $match = array_pop($match[0]);
            $pos = strpos($data, $match);
            $pos = strpos($data, ';', $pos) + 1;
            $data = substr($data, 0, $pos) . "\n\n" . $block . substr($data, $pos);

        } else {
            $data = preg_replace('/\s+class\s+[^{]+{\s+/Ss', '$0' . $block, $data);
        }

        file_put_contents($fn, $data);
    }

    /**
     * Add class method to class repository 
     * 
     * @param string $fn    Path
     * @param string $block Method block
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addMethodToRepository($fn, $block)
    {
        $data = file_get_contents($fn);

        $data = preg_replace('/^\}/Sm', "\n" . $block . "\n" . '$0', $data, 1);

        file_put_contents($fn, $data);
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
        $metadatas = \Includes\Decorator\Utils\Doctrine\EntityManager::getAllMetadata();

        // Truncate old
        foreach (\XLite\Core\Database::getRepo('\XLite\Model\ViewList')->findAll() as $l) {
            \XLite\Core\Database::getEM()->remove($l);
        }
        \XLite\Core\Database::getEM()->flush();

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

                    \XLite\Core\Database::getEM()->persist(
                        $this->createViewList($list, $class)
                    );
                }
            }
        }

        // Assemble anniotaions from templates
        $this->assembleTemplateLists();

        \XLite\Core\Database::getEM()->flush();

        // Global modules preprocessing
        foreach (array_keys($this->classesInfo) as $class) {
            if (preg_match('/^XLite\\\Module\\\[\w]+\\\Main$$/', $class) && method_exists($class, 'modifyViewLists')) {
                $class::modifyViewLists();
            }
        }

        // Static preprocessing
        foreach ($this->viewListPreprocessors as $class => $lists) {
            foreach ($lists as $list => $preprocessors) {
                $data = \XLite\Core\Database::getQB()
                    ->select('v')
                    ->from('ViewList', 'v')
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

        \XLite\Core\Database::getEM()->flush();

        $this->viewListPreprocessors = array();
    }

    /**
     * Create view list record
     * 
     * @param array  $list  List data
     * @param string $class Widget class name
     *  
     * @return XLite\Model\ViewList
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function createViewList(array $list, $class = null)
    {
        if (!isset($list['class'])) {
            $list['class'] = '';
        }

        $viewList = new \XLite\Model\ViewList();


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

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(LC_SKINS_DIR));
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
                ? \XLite\Model\ViewList::ADMIN_INTERFACE
                : \XLite\Model\ViewList::CUSTOMER_INTERFACE;

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

                    \XLite\Core\Database::getEM()->persist($viewList);
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
            $tmp = preg_split('/\W/Ss', $part, 2);

            $tmp[0] = strtolower($tmp[0]);

            if (!isset($attributes[$tmp[0]])) {
                $attributes[$tmp[0]] = array();
            }

            $attributes[$tmp[0]][] = isset($tmp[1]) ? trim($tmp[1]) : true;
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
        foreach (\XLite\Core\Database::getRepo('\XLite\Model\TemplatePatch')->findAll() as $r) {
            \XLite\Core\Database::getEM()->remove($r);
        }
        \XLite\Core\Database::getEM()->flush();

        // Create new
        foreach ($this->templatePatches as $class) {
            $patches = $class::getPatches();
            if (isset($patches[$class::PATCHER_CELL_TYPE])) {
                $patches = array($patches);
            }

            foreach ($patches as $patch) {

                $valid = true;

                $templatePatch = new \XLite\Model\TemplatePatch();

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
                    \XLite\Core\Database::getEM()->persist($templatePatch);
                }
            }

            \XLite\Core\Database::getEM()->flush();
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


    /**
     * Return self instance
     * 
     * @return Decorator
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getInstance()
    {
        return new static;
    }
}
