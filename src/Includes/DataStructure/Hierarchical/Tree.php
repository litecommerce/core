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
 * Tree 
 *
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class Tree extends \Includes\DataStructure\Hierarchical\AHierarchical
{
    /**
     * Tree root
     *
     * @var    \Includes\DataStructure\Node\Tree
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
     * Name of the node class
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $nodeClass = '\Includes\DataStructure\Node\Tree';


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
        foreach ($this->index as $node) {
            !$node->isStub() ?: $this->removeNode($node);
        }   
    }

    /**
     * Search possible parent for a node
     *
     * @param \Includes\DataStructure\Node\Tree $node node to get info
     *
     * @return \Includes\DataStructure\Node\Tree
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNodeLogicalParentKey(\Includes\DataStructure\Node\Tree $node)
    {
        return null;
    }

    /**
     * Search possible parent for a node
     *
     * @param \Includes\DataStructure\Node\Tree $node   node to get info
     * @param bool                              $create create parent if it not exists
     *
     * @return \Includes\DataStructure\Node\Tree
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNodeLogicalParent(\Includes\DataStructure\Node\Tree $node, $create = false)
    {
        // Search by no-empty key
        $parent = ($key = $this->getNodeLogicalParentKey($node)) ? $this->find($key) : null;

        // If node not found
        if (!isset($parent)) {

            if ($create) {
                // Add so called "stub" node
                $this->addChildNode(
                    $this->root,
                    $parent = call_user_func_array(array($node, 'createStubNode'), array($key))
                );
            } else {
                // Use root node as the parent one
                $parent = $this->root; 
            }
        }

        return $parent;
    }

    /**
     * Check and prepare current element data
     * 
     * @param mixed $data data to prepare
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareNodeData($data)
    {
        return $data;
    }

    /**
     * Check constrains
     * 
     * @param \Includes\DataStructure\Node\Tree $node node to check
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkAddedNode(\Includes\DataStructure\Node\Tree $node)
    {
    }


    /**
     * Add child node and index it
     *
     * @param \Includes\DataStructure\Node\Tree $parent parent node
     * @param \Includes\DataStructure\Node\Tree $node   child node to add
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addChildNode(\Includes\DataStructure\Node\Tree $parent, \Includes\DataStructure\Node\Tree $node)
    {
        $parent->addChild($node);
        $this->index[$node->getKey()] = $node;
    }

    /**
     * Remove node and clear index
     *
     * @param \Includes\DataStructure\Node\Tree $parent parent node
     * @param \Includes\DataStructure\Node\Tree $node   child node to add
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function removeChildNode(\Includes\DataStructure\Node\Tree $parent, \Includes\DataStructure\Node\Tree $node)
    {
        $parent->removeChild($node);
        unset($this->index[$node->getKey()]);
    }

    /**
     * Create new node 
     * 
     * @param array $node node data
     *  
     * @return \Includes\DataStructure\Node\Tree
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createNode(array $data = array())
    {
        return new $this->nodeClass($data);
    }

    /**
     * Add class descriptor to the tree
     *
     * @param \Includes\DataStructure\Node\Tree $node   node to add
     * @param bool                              $create create parent if it not exists
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addNode(\Includes\DataStructure\Node\Tree $node, $create = false)
    {
        $parent = $this->getNodeLogicalParent($node, $create);

        // Add or replace node
        $this->replantNode($parent, $node) ?: $this->addChildNode($parent, $node);
    }

    /**
     * Remove node
     *
     * @param \Includes\DataStructure\Node\Tree $node node to remove
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function removeNode(\Includes\DataStructure\Node\Tree $node)
    {
        $node->remove();
        unset($this->index[$node->getKey()]);

        $node = null;
        unset($node);
    }

    /**
     * Change node data and parent
     *
     * @param \Includes\DataStructure\Node\Tree $parent node new parent
     * @param \Includes\DataStructure\Node\Tree $node   node to get data
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function replantNode(\Includes\DataStructure\Node\Tree $parent, \Includes\DataStructure\Node\Tree $node)
    {
        // Replace existsting node
        if ($child = $this->find($node->getKey())) {

            // So called "re-plant" operation: change node parent
            $child->replant($parent, $node);
        }

        return $child;
    }

    /**
     * Find node by key
     *
     * @param string|int $key key to search
     *
     * @return \Includes\DataStructure\Node\Tree
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function find($key)
    {
        return isset($this->index[$key]) ? $this->index[$key] : null;
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
     * Create the tree using a plain array
     * 
     * @param array|Iterator $dataSet plain array
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createFromArray($dataSet)
    {
        // Iterate over the passed array
        foreach ($dataSet as $data) {

            // Check and prepare current element data
            if ($data = $this->prepareNodeData($data)) {

                // Add node to the tree
                $this->addNode($node = $this->createNode($data), true);

                // Check constrains
                $this->checkAddedNode($node);
            }
        }

        // Remove the stub nodes
        $this->collectGarbage();
    }

    /**
     * Constructor
     *
     * @param string $nodeClass node class name
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($nodeClass = null)
    {
        if (empty($nodeClass)) {
            if (empty($this->nodeClass)) {
                throw new \Exception('Node class is not defined for this tree');
            }
        } else {
            $this->nodeClass = $nodeClass;
        }

        $this->root = $this->createNode();
    }
}
