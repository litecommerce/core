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
     * Find node by key
     *
     * FIXME - must be protected 
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
    protected function addChildNode(\Includes\DataStructure\Node\Tree $parent, \Includes\DataStructure\Node\Tree $node)
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function removeChildNode(\Includes\DataStructure\Node\Tree $parent, \Includes\DataStructure\Node\Tree $node)
    {
        $parent->removeChild($node);
        unset($this->index[$node->getKey()]);
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
        // Replace existsting node
        if ($child = $this->find($node->getKey())) {

            // So called "re-plant" operation: change node parent
            $child->replant($parent, $node);
        }

        return $child;
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
     * Constructor
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        $this->root = new $this->nodeClass;
    }

    /**
     * Return tree index
     *
     * FIXME: DEVCODE, to remove
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
}
