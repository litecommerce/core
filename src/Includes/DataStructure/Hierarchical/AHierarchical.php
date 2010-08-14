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
     * Start element
     *
     * @var    \Includes\DataStructure\Node\ANode
     * @access protected
     * @since  3.0.0
     */
    protected $head;

    /**
     * Nodes index (for quick search)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $index = array();


    /**
     * Add node key to the index 
     * 
     * @param \Includes\DataStructure\Node\ANode $node node to index
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function indexNode(\Includes\DataStructure\Node\ANode $node)
    {
        $this->index[$node->getKey()] = $node;
    }


    /**
     * Add node before the certain one
     *
     * @param string                             $key  key of the node to add before
     * @param \Includes\DataStructure\Node\ANode $node node to add
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract public function insertBefore($key, \Includes\DataStructure\Node\ANode $node);

    /**
     * Add node after the certain one
     * 
     * @param string                             $key  key of the node to add after
     * @param \Includes\DataStructure\Node\ANode $node node to add
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract public function insertAfter($key, \Includes\DataStructure\Node\ANode $node);


	/**
     * Check if structure is initialized
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isInitialized()
    {
        return isset($this->head);
    }

    /**
     * Search list element using a callback function
     *
     * @param string $method some public method of the \XLite\Model\ListNode class
     * @param array  $args   callback arguments
     *
     * @return \XLite\Model\ListNode
     * @access public
     * @since  3.0.0
     */
    public function findByCallbackResult($method, array $args = array())
    {
        $node = $this->head;

        while ($node && call_user_func_array(array($node, $method), $args)) {
            $node = $node->getNext();
        }

        return $node;
    }

    /**
     * Search list element by its key
     *
     * @param string $key node identifier
     *
     * @return \Includes\DataStructure\Node\ANode
     * @access public
     * @since  3.0.0
     */
    public function findByKey($key)
    {
        return isset($this->index[$key]) ? $this->index[$key] : null;
    }

	/**
     * Return first element of the list
     *
     * @return \Includes\DataStructure\Node\ANode
     * @access public
     * @since  3.0.0
     */
    public function getHead()
    {
        return $this->head;
    }
}
