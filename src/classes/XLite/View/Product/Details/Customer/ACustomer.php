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

namespace XLite\View\Product\Details\Customer;

/**
 * ACustomer
 *
 */
abstract class ACustomer extends \XLite\View\Product\Details\ADetails
{
    /**
     * Checks whether a product was added to the cart
     *
     * @return boolean
     */
    public function isProductAdded()
    {
        return $this->getCart()->isProductAdded($this->getProduct()->getProductId());
    }


    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getProduct();
    }

    /**
     * Check - product is available for sale or not
     * 
     * @return boolean
     */
    protected function isProductAvailableForSale()
    {
        return $this->getProduct()->isAvailable();
    }
}
