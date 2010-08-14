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
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class ANode
{
    /**
     * Node identifier
     *
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $key = null;

    /**
     * Link to previous list element or null
     *
     * @var    \Includes\DataStructure\Node\ANode
     * @access protected
     * @since  3.0.0
     */
    protected $prev;


    /**
     * Return link to next list element
     *
     * @return \Includes\DataStructure\Node\ANode
     * @access public
     * @since  3.0.0
     */
    abstract public function getNext();

    /**
     * Set link to next list element
     *
     * @param \Includes\DataStructure\Node\ANode $node node link to set
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    abstract public function setNext(\Includes\DataStructure\Node\ANode $node = null);


    /**
     * Set node identifier
     *
     * @param string $key node key
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Return link to previous list element
     *
     * @return \Includes\DataStructure\Node\ANode
     * @access public
     * @since  3.0.0
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     * Set link to previous list element
     *
     * @param \Includes\DataStructure\Node\ANode $node node link to set
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setPrev(\Includes\DataStructure\Node\ANode $node = null)
    {
        $this->prev = $node;
    }

    /**
     * Return node identifier
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Callback to search node by its identifier
     *
     * @param string $key node key
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function checkKey($key)
    {
        return $key != $this->getKey();
    }
}
