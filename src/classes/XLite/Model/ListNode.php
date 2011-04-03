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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Model;

/**
 * List node
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class ListNode extends \XLite\Base\SuperClass
{
    /**
     * Link to previous list element or null
     *
     * @var   \XLite_Model_ListNode or null
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $prev = null;

    /**
     * Link to next list element or null
     *
     * @var   \XLite_Model_ListNode|null
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $next = null;

    /**
     * Node identifier
     *
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $key = null;


    /**
     * Set node identifier
     *
     * @param string $key Node key
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Return link to previous list element
     *
     * @return \XLite\Model\ListNode
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     * Return link to next list element
     *
     * @return \XLite\Model\ListNode
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Set link to previous list element
     *
     * @param \Xlite\Model\ListNode $node Node link to set OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setPrev(\Xlite\Model\ListNode $node = null)
    {
        $this->prev = $node;
    }

    /**
     * Set link to next list element
     *
     * @param \Xlite\Model\ListNode $node Node link to set OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setNext(\Xlite\Model\ListNode $node = null)
    {
        $this->next = $node;
    }

    /**
     * Return node identifier
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Callback to search node by its identifier
     *
     * @param string $key Node key
     *
     * @return boolean 
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkKey($key)
    {
        return $key != $this->getKey();
    }
}
