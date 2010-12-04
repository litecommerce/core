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

namespace Includes\Decorator\DataStructure\Node;

/**
 * ClassInfo 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ClassInfo extends \Includes\DataStructure\Node\Graph
{
    /**
     * Interface for decorator classes
     */
    const INTERFACE_DECORATOR = '\XLite\Base\IDecorator';


    /**
     * Return name of the key field
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getKeyField()
    {
        return \Includes\Decorator\ADecorator::N_CLASS;
    }

    /**
     * Check if class implements an interface
     * 
     * @param self   $node      node to check
     * @param string $interface interface name
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkForInterface(self $node, $interface = self::INTERFACE_DECORATOR)
    {
        return in_array($interface, $node->__get(\Includes\Decorator\ADecorator::N_INTERFACES));
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
        return $this->getKey();
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
     * Return node name for output
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getReadableName()
    {
        $name = str_replace('\\XLite\\', '', parent::getReadableName());

        if ($this->isDecorator()) {
            $name = '<strong>' . $name . '</strong>';
        }

        return $name;
    }

    /**
     * Check if current node decorates a class
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDecorator()
    {
        return $this->checkForInterface($this);
    }

    /**
     * Return list of classes which are decorate current node
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDecorators()
    {
        return ($nodes = $this->getChildren()) ? array_filter($nodes, array($this, 'checkForInterface')) : array();
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
        if ($this->checkIfChildExists($node->getClass()) && $this->getParents()) {
            \Includes\Decorator\ADecorator::fireError('Duplicate child class - "' . $node->getClass() . '"');
        }

        parent::addChild($node);
    }
}
