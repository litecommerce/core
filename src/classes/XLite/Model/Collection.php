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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model;

/**
 * Double-linked list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Collection extends \XLite\Base\SuperClass
{
    /**
     * Start element
     *
     * @var    XLite_Model_ListNode
     * @access protected
     * @since  3.0.0
     */
    protected $head = null;

    /**
     * End element
     *
     * @var    XLite_Model_ListNode
     * @access protected
     * @since  3.0.0
     */
    protected $tail = null;


    /**
     * Check if list is initialized
     *
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function isInitialized()
    {
        return isset($this->head) && isset($this->tail);
    }


    /**
     * Search list element using a callback function
     *
     * @param string $method some public method of the XLite_Model_ListNode class
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
     * @return \XLite\Model\ListNode
     * @access public
     * @since  3.0.0
     */
    public function findByKey($key)
    {
        return $this->findByCallbackResult('checkKey', array($key));
    }

    /**
     * Insert new node before a certain node
     *
     * @param string                $key  node key to search
     * @param \Xlite\Model\ListNode $node new node to insert
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function insertBefore($key, \Xlite\Model\ListNode $node)
    {
        $current = $this->findByKey($key);
        $prev = $current->getPrev();

        $current->setPrev($node);

        $node->setNext($current);
        $node->setPrev($prev);

        if (isset($prev)) {
            $prev->setNext($node);
        } else {
            $this->head = $node;
        }
    }

    /**
     * Insert new node after a certain node
     *
     * @param string                $key  node key to search
     * @param \Xlite\Model\ListNode $node new node to insert
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function insertAfter($key, \Xlite\Model\ListNode $node)
    {
        $current = $this->findByKey($key);
        $next = $current->getNext();

        $current->setNext($node);

        $node->setPrev($current);
        $node->setNext($next);

        if (isset($next)) {
            $next->setPrev($node);
        } else {
            $this->tail = $node;
        }
    }

    /**
     * Add new node to the end of list
     *
     * @param \Xlite\Model\ListNode $node node to add
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function add(\Xlite\Model\ListNode $node)
    {
        if ($this->isInitialized()) {
            $this->insertAfter($this->tail->getKey(), $node);
        } else {
            $this->head = $this->tail = $node;
        }
    }

    /**
     * Return first element of the list
     *
     * @return \Xlite\Model\ListNode
     * @access public
     * @since  3.0.0
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * Return last element of the list
     *
     * @return \Xlite\Model\ListNode
     * @access public
     * @since  3.0.0
     */
    public function getTail()
    {
        return $this->tail;
    }
}
