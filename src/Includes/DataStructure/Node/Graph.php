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

namespace Includes\DataStructure\Node;

/**
 * Graph 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Graph extends \Includes\DataStructure\Node\ANode
{
    /**
     * Link to the parent element
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $parents = array();


    /**
     * Perform some action for all parents
     * 
     * @param string $method name of method to exec
     *  
     * @return null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function invokeAllParents($method)
    {
        foreach ($this->getParents() as $parent) {
            $parent->$method($this);
        }
    }


    /**
     * Check parent by key
     *
     * @param string $key node key
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkIfParentExists($key)
    {
        return isset($this->children[$key]);
    }

    /**
     * Return parent nodes
     *
     * @param string $key key to search node
     *  
     * @return array|self
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getParents($key = null)
    {
        // Tree integrity violation
        if (isset($key) && !$this->checkIfParentExists($key)) {
            \Includes\ErrorHandler::fireError('Node "' . $this->getKey() . '" has no parent "' . $key . '"');
        }

        return isset($key) ? $this->parents[$key] : $this->parents;
    }

    /**
     * Set reference to parent node
     *
     * @param self $node parent node ref
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addParent(self $node)
    {
        $this->parents[$node->getKey()] = $node;
    }

    /**
     * Unset reference to parent node
     *
     * @param self $node parent node ref
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function removeParent(self $node)
    {
        unset($this->parents[$node->getKey()]);
    }

    /**
     * Clear all parents refs
     * 
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function removeParents()
    {
        $this->parents = array();
    }

    /**
     * Add child node
     *
     * @param self $node node to add
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addChild(self $node)
    {
        $node->addParent($this);

        parent::addChild($node);
    }

    /**
     * Remove child node
     *
     * @param self $node node to remove
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function removeChild(self $node)
    {
        $node->removeParent($this);

        parent::removeChild($node);
    }

    /**
     * Change key for current node
     *
     * @param string $key key to set
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function changeKey($key)
    {
        parent::changeKey($key);

        $this->invokeAllParents('addChild');
    }

    /**
     * Remove node
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function remove()
    {
        $this->invokeAllParents('removeChild');
    }
}
