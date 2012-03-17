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

namespace XLite\View\Product;

/**
 * QuantityBox
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class QuantityBox extends \XLite\View\Product\AProduct
{
    /**
     * Widget param names
     */

    const PARAM_PRODUCT      = 'product';
    const PARAM_FIELD_NAME   = 'fieldName';
    const PARAM_FIELD_VALUE  = 'fieldValue';
    const PARAM_FIELD_TITLE  = 'fieldTitle';
    const PARAM_STYLE        = 'style';
    const PARAM_IS_CART_PAGE = 'isCartPage';


    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/quantity_box.css';

        return $list;
    }

    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/controller.js';

        return $list;
    }


    /**
     * Return directory contains the template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return parent::getDir() . '/quantity_box';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_FIELD_NAME   => new \XLite\Model\WidgetParam\String('Name', 'amount'),
            self::PARAM_FIELD_TITLE  => new \XLite\Model\WidgetParam\String('Title', 'Quantity'),
            self::PARAM_PRODUCT      => new \XLite\Model\WidgetParam\Object('Product', null, false, '\XLite\Model\Product'),
            self::PARAM_FIELD_VALUE  => new \XLite\Model\WidgetParam\Int('Value', null),
            self::PARAM_STYLE        => new \XLite\Model\WidgetParam\String('CSS class', ''),
            self::PARAM_IS_CART_PAGE => new \XLite\Model\WidgetParam\Bool('Is cart page', false),
        );
    }

    /**
     * Alias
     *
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProduct()
    {
        return $this->getParam(self::PARAM_PRODUCT);
    }

    /**
     * Alias
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBoxName()
    {
        return $this->getParam(self::PARAM_FIELD_NAME);
    }

    /**
     * Alias
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBoxId()
    {
        return $this->getBoxName() . $this->getProduct()->getProductId();
    }

    /**
     * Alias
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBoxValue()
    {
        return $this->getParam(self::PARAM_FIELD_VALUE) ?: $this->getProduct()->getMinPurchaseLimit();
    }

    /**
     * Alias
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBoxTitle()
    {
        return $this->getParam(self::PARAM_FIELD_TITLE);
    }

    /**
     * Alias
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isCartPage()
    {
        return $this->getParam(self::PARAM_IS_CART_PAGE);
    }

    /**
     * Default CSS classes
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultClass()
    {
        return 'quantity wheel-ctrl' . ($this->isCartPage() ? ' watcher' : '');
    }

    /**
     * CSS class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getClass()
    {
        return $this->getDefaultClass() . ' ' . $this->getParam(self::PARAM_STYLE);
    }

    /**
     * Return name of the \XLite\Model\Inventory model to get max available quantity
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMaxQuantityMethod()
    {
        return $this->isCartPage() ? 'getAmount' : 'getAvailableAmount';
    }

    /**
     * Return maximum allowed quantity
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMaxQuantity()
    {
        return $this->getProduct()->getInventory()->{$this->getMaxQuantityMethod()}();
    }

    /**
     * Return minimum quantity
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMinQuantity()
    {
        return 1;
    }
}
