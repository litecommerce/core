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
class Tree extends \Includes\DataStructure\Hierarchical\Tree
{
    /**
     * Name of the node class
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $nodeClass = '\Includes\Decorator\DataStructure\ClassData\Node';


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
    protected function getNodeParent(\Includes\Decorator\DataStructure\ClassData\Node $node)
    {
        $parent = $this->root;
        $parentClass = $node->__get(\Includes\Decorator\ADecorator::N_PARENT_CLASS);

        // Check if parent class is already add to the tree
        if ($parentClass && !($parent = $this->find($parentClass))) {

            // If not, create the stub for the parent node
            $this->addChildNode(
                $this->root,
                $parent = \Includes\Decorator\DataStructure\ClassData\Node::createStubNode(
                    array(\Includes\Decorator\ADecorator::N_CLASS => $parentClass)
                )
            );
        }

        return $parent;
    }

    /**
     * Change node data and parent
     *
     * @param \Includes\DataStructure\Node\Tree $parent node new parent
     * @param \Includes\DataStructure\Node\Tree $node   node to get data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function replantNode(\Includes\DataStructure\Node\Tree $parent, \Includes\DataStructure\Node\Tree $node)
    {
        // Duplacate definition
        if (($child = $this->find($node->getKey())) && !$child->isStub()) {
            throw new \Exception('Duplicate class definition - "' . $child->getKey() . '"');
        }

        return parent::replantNode($parent, $node);
    }

    /**
     * Add class descriptor to the tree
     *
     * @param array $data class node info
     *
     * @return \Includes\Decorator\DataStructure\ClassData\Node
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addNode(array $data)
    {
        $node   = new \Includes\Decorator\DataStructure\ClassData\Node($data);
        $parent = $this->getNodeParent($node);

        // Add or replace node
        $this->replantNode($parent, $node) ?: $this->addChildNode($parent, $node);

        return $node;
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
        foreach ($this->index as $node) {
            !$node->isStub() ?: $this->removeNode($node);
        }
    }


    /**
     * Walk through the PHP files tree and collect classes info
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function create()
    {
        // Iterate over all PHP files in the "classes" directory
        foreach (\Includes\Utils\FileFilter::filterByExtension(LC_CLASSES_DIR, 'php') as $fileInfo) {

            // Check if file contains class definition
            if ($data = \Includes\Decorator\Utils\ClassData\Parser::parse($fileInfo)) {

                // Create node in the classes tree
                $node = $this->addNode($data);

                // Check constrains
                \Includes\Decorator\Utils\ClassData\Verifier::checkNode($node);
            }
        }

        // Remove the stub nodes
        $this->collectGarbage();
    }


    /**
     * Constructor
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        parent::__construct();

        // Walk through the PHP files tree and collect classes info
        $this->create();
    }
}
