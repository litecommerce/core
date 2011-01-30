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
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Decorator\DataStructure\Node;

/**
 * ClassInfo 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ClassInfo extends \Includes\DataStructure\Node\Graph
{
    /**
     * Interface for decorator classes
     */
    const INTERFACE_DECORATOR = '\XLite\Base\IDecorator';


    /**
     * Return name of the key field
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getKeyField()
    {
        return \Includes\Decorator\ADecorator::N_CLASS;
    }

    /**
     * Get class name parts (namespace and basename)
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNameParts()
    {
        $parts = explode('\\', \Includes\Utils\Converter::trimLeadingChars($this->getClass(), '\\'));
        $base  = array_pop($parts);

        return array(implode('\\', $parts), $base);
    }

    /**
     * Check if class implements an interface
     * 
     * @param self   $node      node to check
     * @param string $interface interface name
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkForInterface(self $node, $interface = self::INTERFACE_DECORATOR)
    {
        return in_array($interface, $node->getInterfaces());
    }

    /**
     * Check if class exytends a current one
     *
     * @param self $node Node to check
     *
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkForParentClass(self $node)
    {
        return in_array($this->getClass(), $node->getParentClasses());
    }

    /**
     * Check if class is a decorator
     *
     * @param self $node Node to check
     *
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkForDecorator(self $node)
    {
        return $this->checkForInterface($node, self::INTERFACE_DECORATOR) && $this->checkForParentClass($node);
    }


    /**
     * Alias
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getClass()
    {
        return $this->getKey();
    }

    /**
     * Return list of class description tags
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTags()
    {
        return (array) $this->{\Includes\Decorator\ADecorator::N_TAGS};
    }

    /**
     * Get tag value from class comment
     * 
     * @param string $name tag name
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTag($name)
    {
        return \Includes\Utils\Converter::getIndex($this->getTags(), strtolower($name), true);
    }

    /**
     * Check if current node implements an interface
     * 
     * @param string $interface interface name
     *  
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isImplements($interface)
    {
        return $this->checkForInterface($this, $interface);
    }

    /**
     * Check if current node extends a class
     * 
     * @param string $class class name
     *  
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isExtends($class)
    {
        return (bool) \Includes\Utils\ArrayManager::searchInObjectsArray($this->getParents(), static::getKeyField(), $class);
    }

    /**
     * Return list of parent classes
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getParentClasses()
    {
        return array_values(array_filter((array) $this->{\Includes\Decorator\ADecorator::N_PARENT_CLASS}));
    }

    /**
     * Return the parent class name (from the PHP "extends")
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getParentClass()
    {
        return \Includes\Utils\Converter::getIndex($this->getParentClasses(), 0, true);
    }

    /**
     * Return list of implemented interfaces
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getInterfaces()
    {
        return array_values(array_filter((array) $this->{\Includes\Decorator\ADecorator::N_INTERFACES}));
    }

    /**
     * Return node name for output
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getReadableName()
    {
        $name = str_replace('\\XLite\\', '', parent::getReadableName());

        if ($this->isDecorator()) {
            $name = '<strong>' . $name . '</strong>';
        }

        return $name;
    }

    /**
     * Check if current node decorates a class
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDecorator()
    {
        return $this->isImplements(self::INTERFACE_DECORATOR);
    }

    /**
     * Return list of classes which are decorate current node
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDecorators()
    {
        return ($nodes = $this->getChildren()) ? array_filter($nodes, array($this, 'checkForDecorator')) : array();
    }

    /**
     * Return top-level class in node decoration chain
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFinalClass()
    {
        return $this->isDecorator() ? $this->getParentClass() : $this->getClass();
    }

    /**
     * Return node namespace
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getNamespace()
    {
        return \Includes\Utils\Converter::getIndex($this->getNameParts(), 0, true);
    }

    /**
     * Return class basename part
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getBasename()
    {
        return \Includes\Utils\Converter::getIndex($this->getNameParts(), 1, true);
    }

    /**
     * Add child node
     *
     * @param self $node node to add
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addChild(self $node)
    {
        // An unexpected logical error (replacement in non-root node)
        if ($this->checkIfChildExists($node->getClass()) && $this->getParents()) {
            \Includes\ErrorHandler::fireError('Duplicate child class - "' . $node->getClass() . '"');
        }

        parent::addChild($node);
    }
}
