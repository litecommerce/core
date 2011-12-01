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
 * @since     1.0.14
 */

namespace XLite\View\DraggableRows\Row;

/**
 * ARow 
 *
 * @see   ____class_see____
 * @since 1.0.14
 */
abstract class ARow extends \XLite\View\AView
{
    /**
     * Return row identifier
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.14
     */
    abstract public function getRowUniqueId();

    /**
     * Return name of the "position" field
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    abstract protected function getRowPosFieldName();

    /**
     * Return value of the "position" field
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.14
     */
    abstract protected function getRowPosFieldValue();

    /**
     * Return name of the "position" input
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    public function getPosInputName()
    {
        return $this->getNamePostedData($this->getRowPosFieldName());
    }

    /**
     * Return value of the "position" input
     *
     * @return input
     * @see    ____func_see____
     * @since  1.0.14
     */
    public function getPosInputValue()
    {
        return $this->getRowPosFieldValue();
    }

    /**
     * Get name for data field
     *
     * @param string  $field Field name
     * @param integer $id    Model object ID OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getNamePostedData($field, $id = null)
    {
        return parent::getNamePostedData($field, $id ?: $this->getRowUniqueId());
    }
}
