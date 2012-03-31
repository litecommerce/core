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

namespace XLite\Module\CDev\VAT\Logic;

/**
 * Price modification logic
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Price extends \XLite\Logic\Price implements \XLite\Base\IDecorator
{
    protected function __construct()
    {
        parent::__construct();

        $this->registerPriceModifier('productNetPrice', 'getExcludeVATModifier', 100);
        $this->registerPriceModifier('productDisplayPrice', 'getIncludeVATModifier', 100);
    }

    protected function getExcludeVATModifier($price, $product)
    {
        return min(0, \XLite\Module\CDev\VAT\Logic\Product\Tax::getInstance()->deductTaxFromPrice($product, $price) - $price);
    }

    protected function getIncludeVATModifier($price, $product)
    {
        return max(
            0,
            \XLite\Core\Config::getInstance()->CDev->VAT->display_prices_including_vat
                ? \XLite\Module\CDev\VAT\Logic\Product\Tax::getInstance()->getVATValue($product, $price)
                : 0
            );
    }
}
