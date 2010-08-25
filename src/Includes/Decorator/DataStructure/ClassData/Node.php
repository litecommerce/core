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
     * Field names
     */

    const NAME_SPACE = 'namespace';
    const NAME       = 'name';
    const PARENT     = 'parent';
    const INTERFACES = 'interfaces';
    const IS_STUB    = 'isStub';
    const FILE_PATH  = 'filePath';


    /**
     * Set the reference to parent node
     *
     * @param self $parent parent node ref
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setParent(self $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Unset the parent node
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function unsetParent()
    {
        $this->parent = null;
    }

    /**
     * Alias
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getClass()
    {
        return $this->__get(self::NAME);
    }

    /**
     * Alias
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getParentClass()
    {
        return $this->__get(self::PARENT);
    }

    /**
     * Check if class implements some interface
     *
     * @param string $name interface to check
     *
     * @return bool
     * @access protected
     * @since  3.0
     */
    public function isImplements($name)
    {
        return in_array($name, (array) $this->__get(self::INTERFACES));
    }

    /**
     * Alias
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFilePath()
    {
        return $this->__get(self::FILE_PATH);
    }

    /**
     * Check if it's the so called "stub" node
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isStub()
    {
        return (bool) $this->__get(self::IS_STUB);
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
        // An unexpected logical error (replace in non-root node)
        if (isset($this->children[$node->getClass()], $this->parent)) {
            throw new \Exception('Duplicate child class - "' . $node->getClass() . '"');
        }

        $node->setParent($this);
        $this->children[$node->getClass()] = $node;
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
        unset($this->children[$node->getClass()]);
        $node->unsetParent();
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
        $this->setData(array(self::IS_STUB => false) + $node->getData());
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
        foreach ($this->children as $child) {
            $child->setParent($this->parent);
        }

        $this->parent->removeChild($this);
    }

    /**
     * Add stub node to the tree
     *
     * @param string $class class name
     *
     * @return self
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function createStubNode($class)
    {
        return new static(array(self::NAME => $class, self::IS_STUB => true));
    }
}
