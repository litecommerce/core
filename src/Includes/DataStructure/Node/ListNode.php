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
 * List node
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ListNode extends \Includes\DataStructure\Node\ANode
{
    /**
     * Link to next list element or null
     * 
     * @var    \Includes\DataStructure\Node\ANode
     * @access protected
     * @since  3.0.0
     */
    protected $next;


    /**
     * Return link to next list element
     *
     * @return \Includes\DataStructure\Node\ANode
     * @access public
     * @since  3.0.0
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Set link to next list element
     *
     * @param \Includes\DataStructure\Node\ANode $node node link to set
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setNext(\Includes\DataStructure\Node\ANode $node = null)
    {
        $this->next = $node;
    }
}
