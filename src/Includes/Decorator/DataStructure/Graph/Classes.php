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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace Includes\Decorator\DataStructure\Graph;

/**
 * Classes
 *
 */
class Classes extends \Includes\DataStructure\Graph
{
    /**
     * Reflection object
     *
     * @var \ReflectionClass
     */
    protected $reflection;

    /**
     * Flag for so called "low-level" nodes
     *
     * @var boolean
     */
    protected $isLowLevelNode;

    /**
     * Flag for so called "top-level" stub nodes
     *
     * @var boolean
     */
    protected $isTopLevelNode;

    /**
     * Flag to determine if node was changed (e.g. its key was modified)
     *
     * @var boolean
     */
    protected $isChanged = false;

    // {{{ Constructor and common getters

    /**
     * Add child node
     *
     * @param self $node Node to add
     *
     * @return void
     */
    public function addChild(self $node)
    {
        parent::addChild($node);

        $node->setParentClass($this->getClass());
    }

    /**
     * Alias
     *
     * @return string
     */
    public function getClass()
    {
        return $this->prepareClassName($this->getKey());
    }

    /**
     * Getter for the flag
     *
     * @return boolean
     */
    public function isLowLevelNode()
    {
        return $this->isLowLevelNode;
    }

    /**
     * Getter for the flag
     *
     * @return boolean
     */
    public function isTopLevelNode()
    {
        return $this->isTopLevelNode;
    }

    /**
     * Return name of parent class
     *
     * @return string
     */
    public function getParentClass()
    {
        return $this->getReflection()->parentClass;
    }

    /**
     * Set name of parent class
     *
     * @param string $class Class name to set
     *
     * @return void
     */
    public function setParentClass($class)
    {
        $this->getReflection()->parentClass = $class;
    }

    /**
     * Return list of class implementing interfaces
     *
     * @return array
     */
    public function getInterfaces()
    {
        return $this->getReflection()->interfaces;
    }

    /**
     * Check if class implements interface
     *
     * @param string $interface Name of interface to check
     *
     * @return boolean
     */
    public function isImplements($interface)
    {
        return in_array($this->prepareClassName($interface), $this->getInterfaces());
    }

    /**
     * Check if class decorates another one
     *
     * @return boolean
     */
    public function isDecorator()
    {
        return $this->isImplements('XLite\Base\IDecorator');
    }

    /**
     * Return name of the module where the class defined
     *
     * @return string
     */
    public function getModuleName()
    {
        return \Includes\Utils\ModulesManager::getModuleNameByClassName($this->getClass());
    }

    /**
     * Return top-level child
     *
     * @return self
     */
    public function getTopLevelNode()
    {
        return $this->isDecorator() && ($children = $this->getChildren()) ? reset($children)->{__FUNCTION__}() : $this;
    }

    // }}}

    // {{{ Methods to modify graph

    /**
     * Set node key
     *
     * @param string  $key     Key to set
     * @param boolean $setFlag Flag OPTIONAL
     *
     * @return void
     */
    public function setKey($key, $setFlag = false)
    {
        foreach ($this->getChildren() as $node) {
            $node->setParentClass($key);
        }

        $this->moveClassFile($key);

        parent::setKey($key);

        if ($setFlag) {
            $this->isChanged = true;
        }
    }

    /**
     * Mark node as "low-level"
     *
     * @return void
     */
    public function setLowLevelNodeFlag()
    {
        $this->isLowLevelNode = true;
    }

    /**
     * Mark node as "top-level"
     *
     * @return void
     */
    public function setTopLevelNodeFlag()
    {
        $this->isTopLevelNode = true;
    }

    // }}}

    // {{{ Methods to get paths and source code

    /**
     * Name of the origin class file
     *
     * @param string $class Class name OPTIONAL
     * @param string $dir   Dir to file OPTIONAL
     *
     * @return string
     */
    public function getFile($class = null, $dir = LC_DIR_CACHE_CLASSES)
    {
        return $dir . $this->getPath($class);
    }

    /**
     * Transform class name into the relative path
     *
     * @param string $class Class name OPTIONAL
     *
     * @return string
     */
    public function getPath($class = null)
    {
        return \Includes\Utils\Converter::getClassFile($class ?: $this->getClass());
    }

    /**
     * Prepare source code of the class
     *
     * @param self $parent Parent node OPTIONAL
     *
     * @return string
     */
    public function getSource(self $parent = null)
    {
        return ($this->isChanged || $this->isDecorator())
            ? $this->getActualSource($parent)
            : ($this->isTopLevelNode() ? $this->getEmptySource($parent) : $this->getRegularSource());
    }

    /**
     * Return modified DOC block
     *
     * @param array   $lines   Lines to add
     * @param boolean $replace Flag OPTIONAL
     * @param boolean $asTags  Flag OPTIONAL
     *
     * @return string
     */
    public function addLinesToDocBlock(array $lines, $replace = false, $asTags = true)
    {
        $separator = PHP_EOL . ' * ';

        if ($asTags) {
            $separator .= '@';

            if (!$replace) {
                foreach ($lines as $index => $line) {
                    $line = preg_split('/\s+/Ss', $line);

                    if (false !== strpos($this->getReflection()->docComment, '@' . array_shift($line))) {
                        unset($lines[$index]);
                    }
                }
            }
        }

        $result = $separator . implode($separator, array_unique($lines));

        if ($replace) {
            $result = '/**' . $result . PHP_EOL . ' */';

        } else {
            $result = preg_replace('/(\s+\*+\/)$/Ss', $result . '$1', $this->getReflection()->docComment);
        }

        return $result;
    }

    /**
     * Return modified DOC block
     *
     * @param array   $lines  Lines to add
     * @param boolean $asTags Flag OPTIONAL
     *
     * @return string
     */
    public function removeLinesFromDocBlock(array $lines, $asTags = true)
    {
        $pattern = $asTags
            ? \Includes\Decorator\Utils\Operator::getTagPattern($lines)
            : '/^(\s*\*\s*)?(' . implode('|', $lines) . ').*$/Smi';

        return preg_replace($pattern, '', $this->getReflection()->docComment);
    }

    /**
     * Actualize and return source code for node
     *
     * @param self $parent Parent node OPTIONAL
     *
     * @return string
     */
    protected function getActualSource(self $parent = null)
    {
        return \Includes\Decorator\Utils\Tokenizer::getSourceCode(
            $this->getFile(),
            $this->getActualNamespace(),
            $this->getClassBaseName(),
            $this->getActualParentClassName($parent),
            $this->removeLinesFromDocBlock(array('ListChild')),
            ($this->isLowLevelNode() || $this->isDecorator()) ? 'abstract' : null
        );
    }

    /**
     * Return source code for "top-level" decorator node
     *
     * @param self $parent Parent node OPTIONAL
     *
     * @return string
     */
    protected function getEmptySource(self $parent = null)
    {
        return '<?php' . PHP_EOL . PHP_EOL
            . (($namespace = $this->getActualNamespace()) ? ('namespace ' . $namespace . ';' . PHP_EOL . PHP_EOL) : '')
            . (($comment = $this->removeLinesFromDocBlock(array('HasLifecycleCallbacks'))) ? ($comment . PHP_EOL) : '')
            . ($this->getReflection()->isFinal ? 'final '    : '')
            . ($this->getReflection()->isAbstract ? 'abstract ' : '')
            . ($this->getReflection()->isInterface ? 'interface' : 'class') . ' ' . $this->getClassBaseName()
            . (($class = $this->getActualParentClassName($parent)) ? (' extends ' . $class) : '')
            . (($interfaces = $this->getInterfaces()) ? (' implements \\' . implode(', \\', $interfaces)) : '')
            . PHP_EOL . '{' . PHP_EOL . '}';
    }

    /**
     * Return source code for regular node
     *
     * @return string
     */
    protected function getRegularSource()
    {
        return \Includes\Utils\FileManager::read($this->getFile());
    }

    /**
     * Return actual parent class name
     *
     * @param self $parent Node to get class name OPTIONAL
     *
     * @return string
     */
    protected function getActualParentClassName(self $parent = null)
    {
        return ($parent && ($class = $parent->getClass())) ? ('\\' . $this->prepareClassName($class)) : null;
    }

    /**
     * Return namespace by class name
     *
     * @return string
     */
    protected function getActualNamespace()
    {
        list(, $namespace) = $this->getClassNameParts();

        return $namespace ? $this->prepareClassName(implode('\\', $namespace)) : null;
    }

    /**
     * Return base part of the class name
     *
     * @return string
     */
    protected function getClassBaseName()
    {
        list($basename, ) = $this->getClassNameParts();

        return $this->prepareClassName($basename);
    }

    /**
     * Parse class name into parts
     *
     * @return array
     */
    protected function getClassNameParts()
    {
        $parts = explode('\\', $this->getClass());

        return array(array_pop($parts), $parts);
    }

    // }}}

    // {{{ Tags

    /**
     * Get tag info
     *
     * @param string $name Tag name
     * @param boolean $forceTokenizer Flag to force tokenizer use (since LC_Dependencies classes could be non-working)
     *
     * @return array
     */
    public function getTag($name, $forceTokenizer = false)
    {
        return \Includes\Utils\ArrayManager::getIndex($this->getTags($forceTokenizer), strtolower($name), true);
    }

    /**
     * Setter
     *
     * @param string $name  Tag name
     * @param array  $value Value to set
     *
     * @return void
     */
    public function setTag($name, array $value)
    {
        $this->getTags();

        $this->tags[$name] = $value;
    }

    /**
     * Parse and return all tags
     *
     * @param boolean $forceTokenizer Flag to force tokenizer use (since LC_Dependencies classes could be non-working)
     *
     * @return array
     */
    public function getTags($forceTokenizer = false)
    {
        if (!isset($this->tags)) {
            $this->tags = \Includes\Decorator\Utils\Operator::getTags($this->getReflection($forceTokenizer)->docComment);

            if (!empty($this->tags['lc_dependencies'][0])) {
                $this->tags['lc_dependencies'] = \Includes\Utils\Converter::parseQuery(
                    $this->tags['lc_dependencies'][0], null, ',', '"\'', false
                );
            }
        }

        return $this->tags;
    }

    /**
     * Clear all tags
     *
     * @param boolean $reversible Flag OPTIONAL
     *
     * @return void
     */
    public function clearTags($reversible = false)
    {
        $this->tags = $reversible ? null : array();
    }

    // }}}

    // {{{ Auxiliary methods

    /**
     * Return the ReflectionClass object for the current node
     *
     * @param boolean $forceTokenizer Flag to force tokenizer use (since LC_Dependencies classes could be non-working)
     *
     * @return \ReflectionClass
     */
    public function getReflection($forceTokenizer = false)
    {
        if (!isset($this->reflection)) {
            $util = '\Includes\Decorator\Utils\Tokenizer';
            $this->reflection = new \StdClass();

            if ($forceTokenizer || $util::getDecoratorFlag()) {
                $this->reflection->parentClass = $util::getParentClassName($this->getFile());
                $this->reflection->interfaces  = $util::getInterfaces($this->getFile());
                $this->reflection->docComment  = $util::getDockBlock($this->getFile());
                $this->reflection->isFinal     = $util::getFlag($this->getFile(), T_FINAL);
                $this->reflection->isAbstract  = $util::getFlag($this->getFile(), T_ABSTRACT);
                $this->reflection->isInterface = (bool) $util::getInterfaceName($this->getFile());

            } else {
                $reflection = new \ReflectionClass($this->getClass());

                $this->reflection->parentClass = ($class = $reflection->getParentClass()) ? $class->getName() : null;
                $this->reflection->interfaces  = (array) $reflection->getInterfaceNames();
                $this->reflection->docComment  = $reflection->getDocComment();
                $this->reflection->isFinal     = $reflection->isFinal();
                $this->reflection->isAbstract  = $reflection->isAbstract();
                $this->reflection->isInterface = $reflection->isInterface();
            }

            $this->reflection->parentClass = $this->prepareClassName($this->reflection->parentClass);
            $this->reflection->interfaces  = array_map(array($this, 'prepareClassName'), $this->reflection->interfaces);

            $this->reflection->hasStaticConstructor = $util::hasMethod(
                $this->getFile(),
                \Includes\Decorator\Plugin\StaticRoutines\Main::STATIC_CONSTRUCTOR_METHOD
            );
        }

        return $this->reflection;
    }

    /**
     * Prepare class name
     *
     * @param string $class Class name to prepare
     *
     * @return string
     */
    protected function prepareClassName($class)
    {
        return \Includes\Utils\Converter::trimLeadingChars($class, '\\');
    }

    /**
     * For additional info
     *
     * @param self $node Current node
     *
     * @return string
     */
    protected function drawAdditional(self $node)
    {
        $result = parent::drawAdditional($node);

        if ($node->getParentClass()) {
            $result .= ' <i>(' . $node->getParentClass() . ')</i>';
        }

        return $result;
    }

    /**
     * Move/copy class file
     *
     * @param string $class New class name
     *
     * @return void
     */
    protected function moveClassFile($class)
    {
        if (!$this->isRoot() && !$this->isRoot($class)) {
            if ($this->getClass()) {
                \Includes\Utils\FileManager::move($this->getFile(), $this->getFile($class));

            } else {
                \Includes\Utils\FileManager::copy($this->getFile($class, LC_DIR_CLASSES), $this->getFile($class));
            }
        }
    }

    // }}}
}
