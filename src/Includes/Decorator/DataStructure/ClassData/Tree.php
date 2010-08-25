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
 * Tree 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class Tree extends \Includes\DataStructure\Hierarchical\Tree
{
    /**
     * Tree root
     *
     * @var    \Includes\Decorator\DataStructure\ClassData\Node
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $root;

    /**
     * Tree index
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $index = array();


    /**
     * Find node by key
     *
     * @param string $class node class (key to search)
     *
     * @return \Includes\Decorator\DataStructure\ClassData\Node
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    // FIXME
    public static function find($class)
    {
        return isset(static::$index[$class]) ? static::$index[$class] : null;
    }

    /**
     * Add new node and index it
     *
     * @param \Includes\Decorator\DataStructure\ClassData\Node $parent parent node
     * @param \Includes\Decorator\DataStructure\ClassData\Node $node   child node to add
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function addChildNode(\Includes\Decorator\DataStructure\ClassData\Node $parent, \Includes\Decorator\DataStructure\ClassData\Node $node)
    {
        $parent->addChild($node);
        static::$index[$node->getClass()] = $node;
    }

    /**
     * Search class parent by the class name
     *
     * @param \Includes\Decorator\DataStructure\ClassData\Node $node node to get info
     *
     * @return \Includes\Decorator\DataStructure\ClassData\Node
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getNodeParent(\Includes\Decorator\DataStructure\ClassData\Node $node)
    {
        $parent = static::$root;

        // Check if parent class is already add to the tree
        if ($node->getParentClass() && !($parent = static::find($node->getParentClass()))) {

            // If not, create the stub for the parent node
            static::addChildNode(
                static::$root,
                $parent = \Includes\Decorator\DataStructure\ClassData\Node::createStubNode($node->getParentClass())
            );
        }

        return $parent;
    }

    /**
     * Change node data and parent
     *
     * @param \Includes\Decorator\DataStructure\ClassData\Node $parent node new parent
     * @param \Includes\Decorator\DataStructure\ClassData\Node $node   node to get data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function replantNode(\Includes\Decorator\DataStructure\ClassData\Node $parent, \Includes\Decorator\DataStructure\ClassData\Node $node)
    {
        // Replace existsting node (only for "stub" nodes)
        if ($child = static::find($node->getClass())) {

            // Duplacate definition
            if (!$child->isStub()) {
                throw new \Exception('Duplicate class definition - "' . $child->getClass() . '"');
            }

            // So called "re-plant" operation: change node parent
            $child->replant($parent, $node);
        }

        return (bool) $child;
    }


    /**
     * Add class descriptor to the tree
     * 
     * @param array $data class node info
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function addNode(array $data)
    {
        $node   = new \Includes\Decorator\DataStructure\ClassData\Node($data);
        $parent = static::getNodeParent($node);

        // Add or replace node
        static::replantNode($parent, $node) ?: static::addChildNode($parent, $node);
    }

    /**
     * Remove node
     * 
     * @param \Includes\Decorator\DataStructure\ClassData\Node $node node to remove
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function removeNode(\Includes\Decorator\DataStructure\ClassData\Node $node)
    {
        $node->remove();
        unset(static::$index[$node->getClass()]);

        $node = null;
        unset($node);
    }

    /**
     * Remove the stub nodes
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function collectGarbage()
    {
        foreach (static::$index as $node) {
            !$node->isStub() ?: static::removeNode($node);
        }
    }

    /**
     * Static constructor
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function __constructStatic()
    {
        static::$root = new \Includes\Decorator\DataStructure\ClassData\Node();
    }

    /**
     * Return tree index
     * FIXME: DEVCODE, to remove
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getIndex()
    {
        return static::$index;
    }
}

// Call static constructor
\Includes\Decorator\DataStructure\ClassData\Tree::__constructStatic();
