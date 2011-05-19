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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\DataSet;

/**
 * Collection
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Collection extends \Doctrine\Common\Collections\ArrayCollection
{
    // {{{ Elements checking

    /**
     * Constructor
     *
     * @param array $elements Elements OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $elements = array())
    {
        parent::__construct($elements);
        $this->filterElements();
    }

    /**
     * ArrayAccess implementation of  offsetSet()
     *
     * @param mixed $offset Offset
     * @param mixed $value  Value
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function offsetSet($offset, $value)
    {
        if ($this->checkElement($value, $offset)) {
            parent::offsetSet($offset, $value);
        }
    }

    /**
     * Filter elements
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function filterElements()
    {
        foreach ($this as $i => $e) {
            if (!$this->checkElement($e, $i)) {
                unset($this[$i]);
            }
        }
    }

    /**
     * Check element
     *
     * @param mixed $element Element
     * @param mixed $key     Element key
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkElement($element, $key)
    {
        return true;
    }

    // }}}

    // {{{ Siblings

    /**
     * Get element previous siblings
     *
     * @param mixed $element Element
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPreviousSiblings($element)
    {
        $previous = array();

        foreach ($this as $i => $e) {
            if ($e == $element) {
                break;
            }

            $previous[$i] = $e;
        }

        return $previous;
    }

    /**
     * Get element next siblings
     *
     * @param mixed $element Element
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getNextSiblings($element)
    {
        $next = array();
        $found = false;

        foreach ($this as $i => $e) {
            if ($found) {
                $next[$i] = $e;
            }

            if ($e == $element) {
                $found = true;
            }
        }

        return $next;
    }

    // }}}
}
