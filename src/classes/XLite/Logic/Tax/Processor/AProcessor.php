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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Logic\Tax\Processor;

/**
 * Abstract tax processor
 *
 */
abstract class AProcessor extends \XLite\Logic\ALogic
{
    /**
     * Order
     *
     * @var \XLite\Model\Order
     */
    protected $order;

    /**
     * Set processor context
     *
     * @param \XLite\Model\Order $order Context
     *
     * @return void
     */
    public function setContext(\XLite\Model\Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get processor context
     *
     * @return \XLite\Model\Order
     */
    public function getContext()
    {
        return $this->order;
    }

    /**
     * Check if process is ready or not
     *
     * @return boolean
     */
    protected function isReady()
    {
        return (bool) $this->order;
    }

    // {{{ Catalog displayed price calculation

    /**
     * Check - processor is modify product price or not
     *
     * @return boolean
     */
    public function isProductPriceModifier()
    {
        return false;
    }

    /**
     * Reverse product price
     *
     * @param \XLite\Model\Product $product Product
     * @param float                $amount  Currenct product price OPTIONAL
     *
     * @return float
     */
    public function reverseProductPrice(\XLite\Model\Product $product, $amount = null)
    {
        return $amount ?: $product->getPrice();
    }

    /**
     * Restore product price
     *
     * @param \XLite\Model\Product $product Product
     * @param float                $amount  Product restored price
     *
     * @return float
     */
    public function restoreProductPrice(\XLite\Model\Product $product, $amount)
    {
        return $amount;
    }

    // }}}

    // {{{ Order calculate

    /**
     * Calculate order tax
     *
     * @return void
     */
    public function calculateOrderTax()
    {
    }

    // }}}
}
