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
 * Product widget
 *
 */
abstract class Product extends \XLite\View\Product\Details\Customer\ACustomer implements \XLite\Base\IDecorator
{
    /**
     * Check - available product for sale or not
     *
     * @return boolean
     */
    public function isAvailableForSale()
    {
        // FIXME[INVENTORY_TRACKING]: check this later
        /* TODO - rework
        if ($this->xlite->get('InventoryTrackingEnabled')) {
            $product = $this->getProduct();
            if ($product->getComplex('inventory.found') && !$product->get('tracking')) {
                $result = 0 < $product->getComplex('inventory.amount');
            }
        }

        return isset($result) ? $result :
        */
        return parent::isAvailableForSale();
    }

    /**
     * Get selected options
     *
     * @return array
     */
    public function getSelectedOptions()
    {
        $saved = \XLite\Core\Session::getInstance()->saved_invalid_options;

        return is_array($saved) && isset($saved[$this->getProduct()->getProductId()])
            ? $saved[$this->getProduct()->getProductId()]
            : array();
    }

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'modules/CDev/ProductOptions/product_details.css';

        return $list;
    }
}
