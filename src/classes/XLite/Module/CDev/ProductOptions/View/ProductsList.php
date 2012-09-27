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

namespace XLite\Module\CDev\ProductOptions\View;

/**
 * Products list
 *
 */
abstract class ProductsList extends \XLite\View\ItemsList\Product\Customer\ACustomer implements \XLite\Base\IDecorator
{
    /**
     * Check - show Add to cart button or not
     *
     * @param \XLite\Model\Product $product Product
     *
     * @return boolean
     */
    protected function isShowAdd2Cart(\XLite\Model\Product $product)
    {
        $result = parent::isShowAdd2Cart($product);

        // FIXME[INVENTORY_TRACKING]: check this later
        /*if (
            $result
            && $this->xlite->get('InventoryTrackingEnabled')
            && $product->getComplex('inventory.found')
            && !$product->get('tracking')
        ) {
            $result = 0 < $product->getComplex('inventory.amount');
        }*/

        return $result;
    }
}
