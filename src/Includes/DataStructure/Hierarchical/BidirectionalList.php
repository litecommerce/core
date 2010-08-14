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
 * Double-linked list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class BidirectionalList extends \Includes\DataStructure\Hierarchical\AHierarchical
{
    /**
     * End element 
     * 
     * @var    \Includes\DataStructure\Node\ANode
     * @access protected
     * @since  3.0.0
     */
    protected $tail;


    /**
     * Check if list is initialized 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isInitialized()
    {
        return parent::isInitialized() && isset($this->tail);
    }

    /**
     * Insert new node before a certain node
     * 
     * @param string                             $key  node key to search
     * @param \Includes\DataStructure\Node\ANode $node new node to insert
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function insertBefore($key, \Includes\DataStructure\Node\ANode $node)
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

        $this->indexNode($node);
    }

    /**
     * Insert new node after a certain node
     *
     * @param string                             $key  node key to search
     * @param \Includes\DataStructure\Node\ANode $node new node to insert
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function insertAfter($key, \Includes\DataStructure\Node\ANode $node)
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

        $this->indexNode($node);
    }

    /**
     * Add new node to the end of list
     * 
     * @param \Includes\DataStructure\Node\ANode $node node to add
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function add(\Includes\DataStructure\Node\ANode $node)
    {
        if ($this->isInitialized()) {
            $this->insertAfter($this->tail->getKey(), $node);
        } else {
            $this->head = $this->tail = $node;
            $this->indexNode($node);
        }
    }

    /**
     * Return last element of the list
     *
     * @return \Includes\DataStructure\Node\ANode
     * @access public
     * @since  3.0.0
     */
    public function getTail()
    {
        return $this->tail;
    }
}
