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

namespace XLite\Model\Tax\Processor;

/**
 * Abstract tax processor 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
abstract class AProcessor extends \XLite\Base
{
    /**
     * Calculate types 
     */
    const CALCULATE_BY_PRODUCT         = 'product';
    const CALCULATE_BY_ORDER_ITEM      = 'item';
    const CALCULATE_BY_ORDER_ITEM_LINE = 'item_line';
    const CALCULATE_BY_ORDER           = 'order';

    /**
     * Tax 
     * 
     * @var   \XLite\Model\Tax
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $tax;

    /**
     * Constructor
     *
     * @param \XLite\Model\Tax $tax Tax
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(\XLite\Model\Tax $tax)
    {
        $this->tax = $tax;
    }

    /**
     * Get tax displayed name
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDisplayedName()
    {
        return $tax->getName();
    }

    // {{{ Calculation routine

    /**
     * Calculate rate 
     * 
     * @param float              $basis   Taxable basis
     * @param \XLite\Model\Order $context Context order
     *  
     * @return float|void
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function calculateRate($basis, \XLite\Model\Order $context);

    /**
     * Calculate tax for product 
     * 
     * @param \XLite\Model\Product $product Product
     *  
     * @return \XLite\Model\Tax\Value
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calculateByProduct(
        \XLite\Model\Product $product,
        \XLite\Model\Order $context = null,
        $amount = null
    ) {
        $context = $this->getContext($context);
        $basis = $this->getTaxableBasisByProduct($product, $amount, $context);
        
        $rate = $this->calculateRate($basis, $context);

        $value = $rate
            ? $this->getTaxValue($rate, $context)
            : null;

        return ($value && $value->check()) ? $value : null;
    }

    /**
     * Calculate tax for order item price
     * 
     * @param \XLite\Model\OrderItem $item Order item
     *  
     * @return \XLite\Model\Tax\Value
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calculateByOrderItemPrice(\XLite\Model\OrderItem $item)
    {
        $context = $item->getOrder();
        $basis = $this->getTaxableBasisByOrderItem($item);

        $rate = $this->calculateRate($basis, $context);

        $value = $rate
            ? $this->getTaxValue($rate, $context)
            : null;

        return ($value && $value->check()) ? $value : null;
    }

    /**
     * Calculate tax for order item subtotal
     *
     * @param \XLite\Model\OrderItem $item Order item
     *
     * @return \XLite\Model\Tax\Value
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calculateOrderItemSubtotal(\XLite\Model\OrderItem $item)
    {
        $context = $item->getOrder();
        $basis = $this->getTaxableBasisByOrderItemSubtotal($item);

        $rate = $this->calculateRate($basis, $context);

        $value = $rate
            ? $this->getTaxValue($rate, $context)
            : null;

        return ($value && $value->check()) ? $value : null;
    }

    /**
     * Calculate tax for order 
     * 
     * @param \XLite\Model\Order $order Order
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calculateOrder(\XLite\Model\Order $order)
    {
        $basis = $this->getTaxableBasisByOrder($order);

        $rate = $this->calculateRate($basis, $order);

        $value = $rate
            ? $this->getTaxValue($rate, $order)
            : null;

        return ($value && $value->check()) ? $value : null;
    }

    /**
     * Get calculation context 
     * 
     * @param \XLite\Model\Order $context Context OPTIONAL
     *  
     * @return \XLite\Model\Order
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getContext(\XLite\Model\Order $context = null)
    {
        if (!$context) {
            $controller = \XLite::getController();
            if (!method_exists($controller, 'getCart')) {
                // TODO - add throw
            }
            $context = $controller->getCart();
        }

        return $context;
    }

    /**
     * Get taxable basis by product 
     * 
     * @param \XLite\Model\Product $product Product
     * @param float                $amount  Predefined basis
     * @param \XLite\Model\Order   $context Context
     *  
     * @return float
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTaxableBasisByProduct(\XLite\Model\Product $product, $amount, \XLite\Model\Order $context)
    {
        return $amount ?: $product->getTaxableBasis();
    }

    /**
     * Get taxable basis by order item
     *
     * @param \XLite\Model\OrderItem $item
     *
     * @return float
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTaxableBasisByOrderItem(\XLite\Model\OrderItem $item)
    {
        return $item->getTaxableBasis();
    }

    /**
     * Get taxable basis by order item line (subtotal)
     *
     * @param \XLite\Model\OrderItem $item
     *
     * @return float
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTaxableBasisByOrderItemSubtotal(\XLite\Model\OrderItem $item)
    {
        return $item->getTaxableBasisSubtotal();
    }

    /**
     * Get taxable basis by order
     *
     * @param \XLite\Model\Order $item
     *
     * @return float
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTaxableBasisByOrder(\XLite\Model\Order $order)
    {
        return $order->getTaxableBasis();
    }

    /**
     * Get tax value 
     * 
     * @param float              $rate    Calulated tax value
     * @param \XLite\Model\Order $context Context
     *  
     * @return \XLite\Model\Tax\Value
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTaxValue($rate, \XLite\Model\Order $context)
    {
        $value = new \XLite\Model\Tax\Value;

        $value->name     = $this->getDisplayedName();
        $value->value    = $rate;
        $value->currency = $context->getCurrency();
        $value->code     = $this->tax->getCode();

        return $value;
    }

    // }}}

    // {{{ Apply logic

    /**
     * Can tax apply or not
     *
     * @param \XLite\Model\Order $context Context
     *
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract public function canApply(\XLite\Model\Order $context);

    // }}}
}

