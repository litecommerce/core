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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Double-linked list 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Model_List extends XLite_Base
{
    /**
     * Start element
     * 
     * @var    XLite_Model_ListNode
     * @access protected
     * @since  3.0.0
     */
    protected $head = null;

    protected $tail = null;

    protected function isInitialized()
    {
        return isset($this->head) && isset($this->tail);
    }

    public function findByCallbackResult($method)
    {
        $args = func_get_args();
        array_shift($args);

        $node = $this->head;

        while ($node && call_user_func_array(array($node, $method), $args)) {
            $node = $node->getNext();
        }

        return $node;
    }

    public function findByKey($key)
    {
        return $this->findByCallbackResult('checkKey', $key);
    }

    public function insertBefore(Xlite_Model_ListNode $node)
    {
        $current = $this->findByKey($key);

        if ($current == $this->head) {
            $this->head = $node;
        }

        $node->setNext($current);
        $node->setPrev($current->getPrev());
        $current->setPrev($node);

        if ($current->getPrev()) {
            $current->getPrev()->setNext($node);
        }
    }

    public function insertAfter($key, Xlite_Model_ListNode $node)
    {
        $current = $this->findByKey($key);

        if ($current == $this->tail) {
            $this->tail = $node;
        }

        $node->setNext($current->getNext());
        $node->setPrev($current);
        $current->setNext($node);

        if ($current->getNext()) {
            $current->getNext()->setPrev($node);
        }
    }

    public function add(Xlite_Model_ListNode $node)
    {
        if ($this->isInitialized()) {
            $this->insertAfter($this->tail->getKey(), $node);
        } else {
            $this->head = $this->tail = $node;
        }
    }

    public function getHead()
    {
        return $this->head;
    }

    public function getTail()
    {
        return $this->tail;
    }
}
