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
 * ANode 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class ANode extends \Includes\DataStructure\Cell
{
    /**
     * Flag for so called "stub" nodes
     */
    const IS_STUB = 'isStub';


    /**
     * Child nodes list
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $children = array();


    /**
     * Return name of the key field
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getKeyField();


    /**
     * Check child by key
     * 
     * @param string $key node key
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkIfChildExists($key)
    {
        return isset($this->children[$key]);
    }

    /**
     * Get node key
     *
     * @return string|int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getKey()
    {
        return $this->__get($this->getKeyField());
    }

    /**
     * Return list of child nodes
     *
     * @param string $key node key
     *
     * @return array|self
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getChildren($key = null)
    {
        // Tree integrity violation
        if (isset($key) && !$this->checkIfChildExists($key)) {
            \Includes\ErrorHandler::fireError('Node "' . $this->getKey() . '" has no child "' . $key . '"');
        }

        return isset($key) ? $this->children[$key] : $this->children;
    }

    /**
     * Check if this node is the "stub" node
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isStub()
    {
        return $this->__isset(self::IS_STUB);
    }

    /**
     * Return node name for output
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getReadableName()
    {
        return $this->getKey();
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
        $this->children[$node->getKey()] = $node;
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
        unset($this->children[$node->getKey()]);
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
        $this->remove();
        $this->__set($this->getKeyField(), $key);
    }

    /**
     * "Re-plant" node: move the sub-tree from root to the already created sub-tree
     *
     * @param self  $parent new parent (root to re-plant to)
     * @param array $data   data for replanted node
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function replant(self $parent, array $data = array())
    {
        $this->__unset(self::IS_STUB);
        $this->setData($data);

        $this->remove();
        $parent->addChild($this);
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
    }

    /**
     * Add stub node to the tree
     *
     * @param string $key new node key
     *
     * @return self
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createStubNode($key)
    {
        return new static(array($this->getKeyField() => $key, self::IS_STUB => true));
    }
}
