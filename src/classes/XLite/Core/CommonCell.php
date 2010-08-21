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

namespace XLite\Core;

/**
 * Common cell class
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class CommonCell extends \Includes\DataStructure\Cell implements \Iterator
{
    /**
     * Return the current element
     *
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function current()
    {
        return current($this->properties);
    }

    /**
     * Return the key of the current element
     *
     * @return scalar
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function key()
    {
        return key($this->properties);
    }

    /**
     * Move forward to next element
     *
     * @return mixed (ignored)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function next()
    {
        return next($this->properties);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return mixed (ignored)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function rewind()
    {
        return reset($this->properties);
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function valid()
    {
        return false !== $this->current();
    }
}
