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

namespace XLite\Logic\Tax\Processor;

/**
 * Abstract tax processor 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
abstract class AProcessor extends \XLite\Logic\ALogic
{
    /**
     * Order 
     * 
     * @var   \XLite\Model\Order
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $order;

    /**
     * Set processor context 
     * 
     * @param \XLite\Model\Order $order Context
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setContext(\XLite\Model\Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get processor context 
     * 
     * @return \XLite\Model\Order
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getContext()
    {
        return $this->order;
    }

    /**
     * Check - process or is ready or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isReady()
    {
        return (bool)$this->order;
    }

    // {{{ Catalog displayed price calculation

    /**
     * Check - processor is modify product price or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function reverseProductPrice(\XLite\Model\Product $product, $amount = null)
    {
        $amount = $amount ?: $product->getPrice();

        return $amount;
    }

    /**
     * Restore product price 
     * 
     * @param \XLite\Model\Product $product Product
     * @param float                $amount  Product restored price
     *  
     * @return float
     * @see    ____func_see____
     * @since  3.0.0
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calculateOrderTax()
    {
    }

    // }}}

}
