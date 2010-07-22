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
 * List node
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ListNode extends \XLite\Base
{
    /**
     * Link to previous list element or null
     * 
     * @var    \XLite\Model\ListNode or null
     * @access protected
     * @since  3.0.0
     */
    protected $prev = null;

    /**
     * Link to next list element or null
     * 
     * @var    \XLite\Model\ListNode|null
     * @access protected
     * @since  3.0.0
     */
    protected $next = null;

    /**
     * Node identifier 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $key = null;


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
     * @return \XLite\Model\ListNode|null
     * @access public
     * @since  3.0.0
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     * Return link to next list element
     *
     * @return \XLite\Model\ListNode|null
     * @access public
     * @since  3.0.0
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Set link to previous list element 
     * 
     * @param \XLite\Model\ListNode $node node link to set
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setPrev(\XLite\Model\ListNode $node = null)
    {
        $this->prev = $node;
    }

    /**
     * Set link to next list element
     *
     * @param \XLite\Model\ListNode $node node link to set
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setNext(\XLite\Model\ListNode $node = null)
    {
        $this->next = $node;
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
