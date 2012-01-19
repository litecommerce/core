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
 * @since     1.0.16
 */

namespace XLite\View\ProductClasses\Book;

/**
 * Row 
 *
 * @see   ____class_see____
 * @since 1.0.16
 */
class Row extends \XLite\View\DraggableRows\Row\ARow
{
    /**
     * Widget param names
     */
    const PARAM_CLASS = 'productClass';

    /**
     * Return row identifier
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getRowUniqueId()
    {
        return $this->getClass()->getId() ?: '_';
    }

    /**
     * Return widget template path
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getDefaultTemplate()
    {
        return 'product_classes/book/row/body.tpl';
    }

    /**
     * Return name of the "position" field
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getRowPosFieldName()
    {
        return 'pos';
    }

    /**
     * Return value of the "position" field
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getRowPosFieldValue()
    {
        return $this->getClass()->getPos();
    }

    /**
     * Define widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_CLASS => new \XLite\Model\WidgetParam\Object(
                'Product class object', new \XLite\Model\ProductClass(), false, '\XLite\Model\ProductClass'
            ),
        );
    }

    /**
     * Alias
     *
     * @return \XLite\Model\Attribute\Group
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getClass()
    {
        return $this->getParam(static::PARAM_CLASS);
    }

    /**
     * Alias
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getClassName()
    {
        return $this->getClass()->getName();
    }

    /**
     * Alias
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAttributes()
    {
        return $this->getClass()->getAttributes();
    }

    /**
     * Alias
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAttributesNumber()
    {
        return count($this->getAttributes());
    }
}
