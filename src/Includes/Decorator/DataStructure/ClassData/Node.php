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

namespace Includes\Decorator\DataStructure\ClassData;

/**
 * Node 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class Node extends \Includes\DataStructure\Node\Tree
{
    /**
     * Field names
     */

    const N_NAME_SPACE    = 'nameSpace';
    const N_CLASS_COMMENT = 'classComment';
    const N_TAGS          = 'tags';
    const N_CLASS         = 'name';
    const N_PARENT_CLASS  = 'parent';
    const N_INTERFACES    = 'interfaces';
    const N_STUB          = 'stub';
    const N_FILE_PATH     = 'filePath';


    /**
     * Return constant by short name
     * 
     * @param string $name short name
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getProperty($name)
    {
        return constant('self::N' . strtoupper(preg_replace('/([A-Z])/Ss', '_$1', $name)));
    }

    /**
     * Method to access node properties
     * 
     * @param string $method called method
     * @param array  $args   method arguments
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __call($method, array $args = array())
    {
        // Parse getter name
        if (!preg_match('/(get|is|hasTag)(\w+)?/Ss', $method, $matches)) {
            throw new \Exception('Undefined class method or wrong getter/setter - "' . $method . '"');
        }

        $result = $this->__get(static::getProperty($matches[2]));

        if ('is' === $matches[1]) {
            $result = (bool) $result;
        }

        return $result;
    }

    /**
     * Get tag valus from class comment
     * 
     * @param string $name tag name
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasTag($name)
    {
        return in_array($name, $this->__get(self::N_TAGS));
    }

    /**
     * Return relative path to the class file
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFilePathRelative()
    {
        return preg_replace('/^' . preg_quote(LC_CLASSES_DIR, '/') . '(.*)\.php$/i', '$1.php', $this->__get(self::N_FILE_PATH));
    }


    /**
     * (Un)Set reference to parent node
     *
     * @param self $parent parent node ref
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setParent(self $parent = null)
    {
        $this->parent = $parent;
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
        // An unexpected logical error (replace in non-root node)
        if (isset($this->children[$node->getClass()], $this->parent)) {
            throw new \Exception('Duplicate child class - "' . $node->getClass() . '"');
        }

        $node->setParent($this);
        $this->children[$node->getClass()] = $node;
    }

    /**
     * Remove child node
     *
     * @param self $node node to remove
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function removeChild(self $node)
    {
        unset($this->children[$node->getClass()]);
        $node->setParent();
    }

    /**
     * "Re-plant" node: move the sub-tree from root to the already created sub-tree
     *
     * @param self $parent new parent (root to re-plant to)
     * @param self $node   new node to retrieve data
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function replant(self $parent, self $node)
    {
        $this->setData(array(self::N_STUB => false) + $node->getData());
        $parent->addChild($this);
    }

    /**
     * Remove node
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function remove()
    {
        foreach ($this->children as $child) {
            $child->setParent($this->parent);
        }

        $this->parent->removeChild($this);
    }


    /**
     * Add stub node to the tree
     *
     * @param string $class class name
     *
     * @return self
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function createStubNode($class)
    {
        return new static(array(self::N_CLASS => $class, self::N_STUB => true));
    }
}
