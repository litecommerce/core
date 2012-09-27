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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace Includes\DataStructure;

/**
 * Graph 
 *
 */
class Graph
{
    /**
     * Reserved key for root node
     */
    const ROOT_NODE_KEY = '____ROOT____';

    /**
     * Node unique key
     *
     * @var string
     */
    protected $key;

    /**
     * Node children
     *
     * @var array
     */
    protected $children = array();

    // {{{ Constructor and common getters

    /**
     * Constructor
     *
     * @param string $key Node unique key OPTIONAL
     *
     * @return void
     */
    public function __construct($key = self::ROOT_NODE_KEY)
    {
        $this->setKey($key);
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Getter
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Check for root node
     *
     * @param string $key Key to check OPTIONAL
     *
     * @return void
     */
    public function isRoot($key = null)
    {
        return static::ROOT_NODE_KEY === ($key ?: $this->getKey());
    }

    // }}}

    // {{{ Methods to modify graph

    /**
     * Add child node
     *
     * @param self $node Node to add
     *
     * @return void
     */
    public function addChild(self $node)
    {
        $this->children[] = $node;
    }

    /**
     * Remove child node
     *
     * @param self $node Node to remove
     *
     * @return void
     */
    public function removeChild(self $node)
    {
        // Check all children
        foreach ($this->getChildren() as $index => $child) {

            // Deletion criteria - keys are equal
            if ($node->getKey() === $child->getKey()) {
                unset($this->children[$index]);
            }
        }
    }

    /**
     * Set node key
     *
     * @param string $key Key to set
     *
     * @return void
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * So called "re-plant" operation: change node parent
     *
     * @param self $oldParent Replant from
     * @param self $newParent Replant to
     *
     * @return void
     */
    public function replant(self $oldParent, self $newParent)
    {
        $oldParent->removeChild($this);
        $newParent->addChild($this);
    }

    // }}}

    // {{{ Methods to iterate over the graph

    /**
     * Common method to iterate over the tree
     *
     * @param callback $callback  Callback to perform on each node
     * @param boolean  $invert    Flag OPTIONAL
     * @param self     $parent    Parent node (this param is needed for recursion) OPTIONAL
     * @param boolean  $isStarted Flag OPTIONAL
     *
     * @return void
     */
    public function walkThrough($callback, $invert = false, self $parent = null, $isStarted = false)
    {
        // Condition to avoid callback on the root node
        if ($isStarted && $invert) {
            call_user_func_array($callback, array($this, $parent));
        }

        // Recursive call on all child nodes
        foreach ($this->getChildren() as $node) {
            $node->{__FUNCTION__}($callback, $invert, $isStarted ? $this : null, true);
        }

        // Condition to avoid callback on the root node
        if ($isStarted && !$invert) {
            call_user_func_array($callback, array($this, $parent));
        }
    }

    /**
     * Find all nodes by key
     *
     * @param string $key Key to search OPTIONAL
     *
     * @return array
     */
    public function findAll($key = null)
    {
        $searchResult = array();

        $this->walkThrough(
            function (\Includes\DataStructure\Graph $node) use ($key, &$searchResult) {
                if (!isset($key) || $node->getKey() == $key) {
                    $searchResult[] = $node;
                }
            }
        );

        return $searchResult;
    }

    /**
     * Find node by key
     *
     * @param string $key Key to search
     *
     * @return array
     */
    public function find($key)
    {
        return \Includes\Utils\ArrayManager::getIndex($this->findAll($key), 0, true);
    }

    // }}}

    // {{{ Integrity check

    /**
     * Check graph integrity
     *
     * @return void
     */
    public function checkIntegrity()
    {
    }

    // }}}

    // {{{ Error handling

    /**
     * Method to fire an error
     *
     * @param string $code Error code (or message)
     * @param self   $node Node  Node caused the error OPTIONAL
     *
     * @return void
     */
    public function handleError($code, self $node = null)
    {
        \Includes\ErrorHandler::fireError($this->prepareErrorMessage($code, $node));
    }

    /**
     * Prepare and return error message
     *
     * @param string $code Error code (or message)
     * @param self   $node Node  Node caused the error OPTIONAL
     *
     * @return string
     */
    protected function prepareErrorMessage($code, self $node = null)
    {
        return $code . ' (' . $this->getKey() . ($node ? (', ' . $node->getKey()) : '') . ')';
    }

    // }}}

    // {{{ Visualization

    /**
     * Visualize tree
     *
     * @param self    $root   Root node of current level OPTIONAL
     * @param integer $offset Level offset OPTIONAL
     *
     * @return void
     */
    public function draw(self $root = null, $offset = 0)
    {
        // Recursive call support
        if (!isset($root)) {
            $root = $this;
        }

        // Walk through all nodes
        foreach ($root->getChildren() as $child) {

            // Output
            echo (str_repeat('|__', floor($offset / 2)) . $child->getKey() . $this->drawAdditional($child) . '<br />');

            // Recursive call: next level
            $this->{__FUNCTION__}($child, $offset + 2);
        }
    }

    /**
     * For additional info
     *
     * @param self $node Current node
     *
     * @return string
     */
    protected function drawAdditional(self $node)
    {
        return '';
    }

    // }}}
}
