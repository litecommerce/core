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

namespace Includes\DataStructure\Hierarchical;

/**
 * AHierarchical 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class AHierarchical
{
    /**
     * Base class for tree nodes 
     */
    const NODE_CLASS_BASE = '\Includes\DataStructure\Node\ANode';


    /**
     * Tree root
     *
     * @var    \Includes\DataStructure\Node\ANode
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $root;

    /**
     * Tree index
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $index = array();

    /**
     * Name of the node class (abstract property)
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $nodeClass;


    /**
     * Action to perform in "collectGarbage" method
     *
     * @param \Includes\DataStructure\Node\ANode $node current node
     *
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function performCleanupAction(\Includes\DataStructure\Node\ANode $node)
    {
         // !$node->isStub() ?: $this->removeNode($node);
    }

    /**
     * Remove the stub nodes
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function collectGarbage()
    {
        /* foreach ($this->getIndex() as $node) {
            $this->performCleanupAction($node);
        } */
    }

    /**
     * Check and prepare current element data
     *
     * @param string|int $key  node key in data array
     * @param mixed      $data data to prepare
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareNodeData($key, $data)
    {
        return $data;
    }

    /**
     * Check constrains
     *
     * @param \Includes\DataStructure\Node\ANode $node node to check
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkAddedNode(\Includes\DataStructure\Node\ANode $node)
    {
    }

    /**
     * Stub function to use in "addNode()"
     * 
     * @param \Includes\DataStructure\Node\ANode $node node to get info
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNodeParents(\Includes\DataStructure\Node\ANode $node)
    {
        return array();
    }

    /**
     * Ancillary method to use in "addNode()"
     * 
     * @param mixed $key some data to use as key
     *  
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNodeLogicalParentKey($key)
    {
        return $key;
    }


    /**
     * Add child node and index it
     *
     * @param \Includes\DataStructure\Node\ANode $parent parent node
     * @param \Includes\DataStructure\Node\ANode $node   child node to add
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addChildNode(\Includes\DataStructure\Node\ANode $parent, \Includes\DataStructure\Node\ANode $node)
    {
        $parent->addChild($node);
        $this->index[$node->getKey()] = $node;
    }

    /**
     * Remove node and clear index
     *
     * @param \Includes\DataStructure\Node\ANode $parent parent node
     * @param \Includes\DataStructure\Node\ANode $node   child node to add
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function removeChildNode(\Includes\DataStructure\Node\ANode $parent, \Includes\DataStructure\Node\ANode $node)
    {
        $parent->removeChild($node);
        unset($this->index[$node->getKey()]);
    }

    /**
     * So called "re-plant" operation: move subtree to a new parent
     *
     * @param \Includes\DataStructure\Node\ANode $parent new parent node
     * @param \Includes\DataStructure\Node\ANode $node   node to move
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function replantNode(\Includes\DataStructure\Node\ANode $parent, \Includes\DataStructure\Node\ANode $node)
    {
        // Find existsting node
        if ($child = $this->find($node->getKey())) {

            // So called "re-plant" operation: change node parent
            $child->replant($parent, $node->getData());

        } else {

            // Node not found - add new
            $this->addChildNode($parent, $node);
        }
    }

    /**
     * Create new node
     *
     * @param array $node node data
     *
     * @return \Includes\DataStructure\Node\ANode
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createNode(array $data = array())
    {
        return new $this->nodeClass($data);
    }

    /**
     * Change key for node
     * 
     * @param \Includes\DataStructure\Node\ANode $node node to change key
     * @param string                             $key  new key
     *  
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function changeNodeKey(\Includes\DataStructure\Node\ANode $node, $key)
    {
        if ($this->find($key)) {
            \Includes\ErrorHandler::fireError('Duplicate key: "' . $key . '"');
        }

        $node->changeKey($key);
    }

    /**
     * Remove node
     *
     * @param \Includes\DataStructure\Node\ANode $node node to remove
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function removeNode(\Includes\DataStructure\Node\ANode $node)
    {
        $keys = array($node->getKey());

        foreach ($node->getChildren() as $child) {
            $keys = array_merge($keys, $this->removeNode($child));
        }

        $node->remove();
        unset($this->index[$node->getKey()]);
        unset($node);

        return $keys;
    }

    /**
     * Find node by key
     *
     * @param string|int $key key to search
     *
     * @return \Includes\DataStructure\Node\ANode|null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function find($key)
    {
        return isset($this->index[$key]) ? $this->index[$key] : null;
    }

    /**
     * Find nodes using a callback function
     *
     * @param mixed $callback callback to execute
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByCallback($callback)
    {
        return array_filter($this->getIndex(), $callback);
    }

    /**
     * Return tree index
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Return root node
     *
     * @return \Includes\DataStructure\Node\ANode
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Visualize tree
     *
     * @param \Includes\DataStructure\Node\ANode $root   root node of current level
     * @param int                               $offset level offset
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function draw(\Includes\DataStructure\Node\ANode $root = null, $offset = 0)
    {
        if (!isset($root)) {
            $root = $this->getRoot();
        }

        foreach ($root->getChildren() as $child) {

            // Output
            echo (str_repeat('|__', floor($offset / 2)) . $child->getReadableName() . '<br />');

            // Recursive call: next level
            $this->draw($child, $offset + 2);
        }
    }

    /**
     * Common method to add node to the structure
     *
     * @param \Includes\DataStructure\Node\ANode $node node to add
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addNode(\Includes\DataStructure\Node\ANode $node)
    {
        // Check if node has parents
        if ($parents = $this->getNodeParents($node)) {

            // Link node to all parent nodes
            foreach ((array) $parents as $key) {

                // Check if it's allowed to add this node
                if ($key = $this->getNodeLogicalParentKey($key)) {
            
                     // Check if parent class was already added to the tree
                    if (!($parent = $this->find($key))) {

                        // Create stub node for parent if it not exists
                        $this->addChildNode($this->getRoot(), $parent = $node::createStubNode($key));
                    }

                    // Re-plant current node
                    $this->replantNode($parent, $node);
                }
            }

        } else {

            // Root class
            $this->replantNode($this->getRoot(), $node);
        }
    }

    /**
     * Create the tree using a plain array
     *
     * @param array|Iterator $dataset plain array
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createFromArray($dataset)
    {
        // Iterate over the passed array
        foreach ($dataset as $key => $data) {

            // Check and prepare current element data
            if ($data = $this->prepareNodeData($key, $data)) {

                // Add node to the tree
                $this->addNode($node = $this->createNode($data));

                // Check constrains
                $this->checkAddedNode($node);
            }
        }

        // Remove the stub nodes
        $this->collectGarbage();

        // Check if graph is correct
        $this->checkIntegrity();
    }

    /**
     * Check tree integrity
     * 
     * @param \Includes\DataStructure\Node\ANode $root      root node for current step
     * @param array                              $checklist list of nodes which are not still checked
     *  
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkIntegrity(\Includes\DataStructure\Node\ANode $root = null, array &$checklist = null)
    {
        // Get default values
        isset($root) ?: ($root = $this->getRoot());
        isset($checklist) ?: ($checklist = array_fill_keys(array_keys($this->getIndex()), true));

        // Scan child nodes
        foreach ($root->getChildren() as $node) {

            // By some reason node was not added to the index
            if (!isset($checklist[$node->getKey()])) {
                \Includes\ErrorHandler::fireError(
                    'Node "' . $node->getKey() . '" is not indexed or included into a hamilton cycle'
                );
            }

            // Recursive call
            unset($checklist[$node->getKey()]);
            $this->checkIntegrity($node, $checklist);
        }

        // There are nodes not connected to the root one
        if (!$root->getKey() && !empty($checklist)) {
            \Includes\ErrorHandler::fireError('Non-linked nodes: "' . implode('", "', array_keys($checklist)) . '"');
        }
    }

    /**
     * Constructor
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($nodeClass = null)
    {
        if ($nodeClass = $nodeClass ?: $this->nodeClass) {

            // Check if node class is allowed
            if (!is_subclass_of($nodeClass, self::NODE_CLASS_BASE)) {
                \Includes\ErrorHandler::fireError('"' . $nodeClass . '": invalid class for node tree');
            }

        } else {

            // Nor defined neither passed
            \Includes\ErrorHandler::fireError('Class for tree nodes is not defined');
        }

        $this->nodeClass = $nodeClass;
        $this->root = $this->createNode();
    }
}
