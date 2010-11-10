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
    /////////////////////////////// TO REWORK ///////////////////////////////

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
     * Classes info
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected static $classesInfo = array();

    /**
     * Class decorators info
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected static $classDecorators = array();

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
     * Template patches
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $templatePatches = array();

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
        return str_replace('\\', LC_DS, ltrim($class, '\\')) . '.php';
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
     * FIXME - to remove
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
        return /*'\\' . */$name;
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

            foreach (static::$classDecorators as $root => $list) {
                if (isset(static::$classesInfo[$root]) && static::$classesInfo[$root][self::INFO_ENTITY]) {
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
            '/^ \* @(?:' . implode('|', $this->doctrineClassAttributes) . ')(?:\s+\(.+\))?\s*$/UiSm',
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
        return preg_match('/\\\XLite\\\Module\\\[\w]+\\\Controller\\\[\w\\\]*/Ss', $class);
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
        return preg_replace('/\\\XLite\\\(Module\\\[\w]+\\\)Controller(\\\[\w\\\]*)/Ss', '\\\\XLite\\\\Controller$2', $class);
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
        return '\XLite\Model\Base\I18n' == $extends;
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

            foreach (\Includes\Decorator\Utils\ModulesManager::getActiveModules() as $module) {

                $module = $module['name'];

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
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function createClassTreeFull()
    {
        foreach (static::getClassesTree()->getIndex() as $node) {

            // Save data
            static::$classesInfo[$node->__get(self::N_CLASS)] = array(
                self::INFO_FILE          => $node->__get(self::N_FILE_PATH),
                self::INFO_CLASS_ORIG    => $node->__get(self::N_CLASS),
                self::INFO_EXTENDS       => $node->__get(self::N_PARENT_CLASS),
                self::INFO_EXTENDS_ORIG  => $node->__get(self::N_PARENT_CLASS),
                self::INFO_IS_DECORATOR  => in_array('\XLite\Base\IDecorator', $node->__get(self::N_INTERFACES)),
                self::INFO_ENTITY        => !is_null($node->getTag('Entity')),
                self::INFO_CLASS_COMMENT => ($classComment = $node->__get(self::N_CLASS_COMMENT)),
            );

            if (in_array('\XLite\Base\IPatcher', $node->__get(self::N_INTERFACES))) {
                $this->templatePatches[] = $node->__get(self::N_CLASS);
            }

            if ($this->isMultilang($node->__get(self::N_PARENT_CLASS))) {
                $this->multilangs[] = $node->__get(self::N_CLASS);
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
        foreach (static::$classesInfo as $class => $info) {

            // Only rename classes which are not decorates controllers
            if (
                !empty($class)
                && $this->isModuleController($class)
                && !$info[self::INFO_IS_DECORATOR]
            ) {

                // Cut module-related part from class name
                $newClass = $this->prepareModuleController($class);

                // Error - such controller is already defined in LC core or in other module
                if (!is_null(static::getClassesTree()->find($newClass))) {
                    echo (sprintf(self::CONTROLLER_ERR_MSG, \Includes\Decorator\Utils\ModulesManager::getModuleNameByClassName($class), $class));
                    die (1);
                }

                // Rename and save data
                static::$classesInfo[$newClass] = array_merge($info, array(self::INFO_CLASS => $newClass));
                unset(static::$classesInfo[$class]);
                $this->normalizedControllers[$class] = $newClass;
            }
        }

        // Rename classes in the "INFO_EXTENDS" field
        foreach (static::$classesInfo as $class => $info) {

            if (isset($this->normalizedControllers[$info[self::INFO_EXTENDS]])) {
                static::$classesInfo[$class][self::INFO_EXTENDS]
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
        foreach (static::$classesInfo as $class => $info) {

            if ($info[self::INFO_IS_DECORATOR]) {

                // Create new node
                if (!isset(static::$classDecorators[$info[self::INFO_EXTENDS]])) {
                    static::$classDecorators[$info[self::INFO_EXTENDS]] = array();
                }

                // Save class name and its priority (equals to module priority)
                static::$classDecorators[$info[self::INFO_EXTENDS]][$class] = $this->getModulePriority(
                    \Includes\Decorator\Utils\ModulesManager::getModuleNameByClassName($class)
                );
            }

            // These fields are not needed
            if (empty($info[self::INFO_EXTENDS])) {
                unset(static::$classesInfo[$class][self::INFO_EXTENDS]);
            }
            unset(static::$classesInfo[$class][self::INFO_IS_DECORATOR]);
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
        foreach (static::$classDecorators as $class => $decorators) {

            // Sort decorated classes by module priority and invert decorator chain
            arsort($decorators, SORT_NUMERIC);
            $decorators = array_keys($decorators);

            $currentClass = $class;

            // Each decorator class extends a next one in decorator chain
            foreach ($decorators as $decorator) {
                static::$classesInfo[$currentClass][self::INFO_EXTENDS] = $decorator;
                $currentClass = $decorator;
            }

            // So called "root" class - class extended by decorators
            $rootClass = $class . self::ROOT_CLASS_SUFFIX;

            static::$classesInfo[$currentClass][self::INFO_EXTENDS] = $rootClass;
            static::$classesInfo[$class][self::INFO_IS_ROOT_CLASS] = true;

            // Wrong class name
            if (!isset(static::$classesInfo[$class][self::INFO_FILE])) {
                echo (sprintf(self::UNDEFINED_CLASS_MSG, $class));
                die (2);
            }

            // Assign new (reserved) name to root class and save other info
            static::$classesInfo[$rootClass] = array(
                self::INFO_FILE         => static::$classesInfo[$class][self::INFO_FILE],
                self::INFO_CLASS        => $rootClass,
                self::INFO_CLASS_ORIG   => $class,
                self::INFO_EXTENDS_ORIG => static::$classesInfo[$class][self::INFO_EXTENDS_ORIG],
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
        $this->createClassTreeFull();


        $this->normalizeModuleControllerNames();
        $this->createDecoratorTree();
        $this->mergeClassAndDecoratorTrees();

        // Write file to the cache directory
        foreach (static::$classesInfo as $class => $info) {
            $this->writeClassFile($class, $info);
        }

        // Clear all cache
        $this->clearDoctrineCache();

        // Postbuild multilanguage classes
        $this->buildMultilangs();

        // Generate models
        $this->generateModels();

        // Run registered plugins
        \Includes\Decorator\Utils\PluginManager::invokeHook('run');

        // Collect patches to DB
        $this->collectPatches();

        // Create templates cache
        $flexy = \XLite\Core\FlexyCompiler::getInstance();
        foreach (static::getTemplatesCollection()->getList() as $template) {
            $flexy->prepare(
                \Includes\Utils\FileManager::getRelativePath($template->__get(self::N_FILE_PATH), LC_ROOT_DIR, 'tpl'),
                true
            );
        }

        // Store files in APC
        if (function_exists('apc_compile_file')) {
            apc_clear_cache();
            foreach (static::$classesInfo as $class => $info) {
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
        $repos = array();

        foreach (\Includes\Decorator\Utils\Doctrine\EntityManager::getAllMetadata() as $metadata) {
            $path = LC_CLASSES_CACHE_DIR . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $metadata->name) . '.php';
            $data = file_get_contents($path);

            if (preg_match('/\ * @Entity/Ssi', $data)) {
                $repos[] = $metadata->name;   
            }

            $additionalMethods = array();

            $relations = array();

            foreach ($metadata->associationMappings as $an => $av) {

                if (
                    $av['type'] == $metadata::ONE_TO_MANY
                    || $av['type'] == $metadata::MANY_TO_MANY
                ) {
                    $relations[] = $an;
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

        // Renew meta data ceche
        foreach ($repos as $name) {
            \XLite\Core\Database::getRepo($name);
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

            $decorated = isset(static::$classDecorators[$class]);
            $fn = LC_CLASSES_CACHE_DIR . static::$classesInfo[$class]['file'];
            if (isset(static::$classDecorators[$class])) {
                $fn = preg_replace('/\.php/S', 'Abstract$0', $fn);
            }

            $tclass = $class . 'Translation';
            if (!isset(static::$classesInfo[$tclass])) {
                // TODO - add error logging
                continue;
            }

            $tfn = LC_CLASSES_CACHE_DIR . static::$classesInfo[$tclass]['file'];
            if (isset(static::$classDecorators[$tclass])) {
                $tfn = preg_replace('/\.php/S', 'Abstract$0', $tfn);
            }

            $tfiles = array($tfn);
            if (isset(static::$classDecorators[$tclass])) {
                foreach (static::$classDecorators[$tclass] as $f) {
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

            $tclass = ltrim($tclass, '\\');

            $block = <<<DATA
    /**
     * Translations (relation)
     * AUTOGENERATED
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @OneToMany (targetEntity="$tclass", mappedBy="owner", cascade={"persist","remove"})
     */
    protected \$translations;
DATA;
            $this->addPropertyToRepository($fn, $block);

            $data = file_get_contents($fn);

            foreach ($translationFields as $field) {

                $prefix = str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));

                $block = '';

                if (!preg_match('/ function get' . $prefix . '\(/Ss', $data)) {

                    $block .= <<<DATA
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

DATA;
                }

                if (!preg_match('/ function set' . $prefix . '\(/Ss', $data)) {

                    $block .= <<<DATA
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
                }

                if ($block) {
                    $this->addMethodToRepository($fn, $block);
                }
            }
            

            $class = ltrim($class, '\\');

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
            $pos   = strpos($data, $match);
            $pos   = strpos($data, ';', $pos) + 1;
            $data  = substr($data, 0, $pos) . "\n\n" . $block . substr($data, $pos);

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
     * Get final class by class-decorator
     * 
     * @param string $class Class-decorator
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getFinalClass($class)
    {
        if (isset(static::$classDecorators[$class])) {

            // Class already final
            $result = $class;

        } elseif (
            static::$classesInfo[$class][self::INFO_EXTENDS_ORIG] != static::$classesInfo[$class][self::INFO_EXTENDS]
            && isset(static::$classDecorators[static::$classesInfo[$class][self::INFO_EXTENDS_ORIG]])
        ) {

            // Class is decorator
            $result = static::$classesInfo[$class][self::INFO_EXTENDS_ORIG];

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
}
