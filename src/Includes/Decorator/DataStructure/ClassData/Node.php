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
 * Node 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class Node extends \Includes\DataStructure\Node\Tree
{
    /**
     * Flag for so called "stub" nodes
     */
    const IS_STUB = 'isStub';


    /**
     * Return node key (class name)
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getKey()
    {
        return $this->__get(\Includes\Decorator\ADecorator::N_CLASS);
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
     * Get tag value from class comment
     * 
     * @param string $name tag name
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTag($name)
    {
        $tags = $this->__get(\Includes\Decorator\ADecorator::N_TAGS);

        return isset($tags[$name = strtolower($name)]) ? $tags[$name] : null;
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
        // An unexpected logical error (replacement in non-root node)
        if (isset($this->children[$node->getKey()], $this->parent)) {
            throw new \Exception('Duplicate child class - "' . $node->getKey() . '"');
        }

        parent::addChild($node);
    }

    /**
     * "Re-plant" node: move the sub-tree from root to the already created sub-tree
     *
     * @param self $parent new parent (root to re-plant to)
     * @param self $node   new node to retrieve data
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function replant(self $parent, self $node)
    {
        $this->__unset(self::IS_STUB);

        parent::replant($parent, $node);
    }


    /**
     * Add stub node to the tree
     *
     * @param array $data data to set
     *
     * @return self
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function createStubNode(array $data = array())
    {
        return new static($data + array(self::IS_STUB => true));
    }
}
