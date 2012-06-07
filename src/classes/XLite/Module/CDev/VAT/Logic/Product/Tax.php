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
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\VAT\Logic\Product;

/**
 * Product tax business logic
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Tax extends \XLite\Module\CDev\VAT\Logic\ATax
{

    // {{{ Product search

    /**
     * Get search price condition 
     * 
     * @param string $priceField   Price field name (ex. 'p.price')
     * @param string $classesAlias Produyct classes table alias (ex. 'classes')
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.8
     */
    public function getSearchPriceCondition($priceField, $classesAlias)
    {
        $cnd = $priceField;

        foreach ($this->getTaxes() as $tax) {
            $includedZones = $tax->getVATZone() ? array($tax->getVATZone()->getZoneId()) : array();
            $included = $tax->getFilteredRate($includedZones, $tax->getVATMembership());

            if ($included) {
                $cnd .= ' - (' . $included->getExcludeTaxFormula($priceField) . ')';
            }
        }

        return $cnd;
    }

    // }}}

    // {{{ Calculation

    /**
     * Calculate product-based included taxes
     * 
     * @param \XLite\Model\Product $product Product
     * @param float                $price   Price OPTIONAL
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function calculateProductTaxes(\XLite\Model\Product $product, $price)
    {
        $zones = $this->getZonesList();
        $membership = $this->getMembership();

        $taxes = array();

        foreach ($this->getTaxes() as $tax) {

            $rate = $tax->getFilteredRate($zones, $membership, $product->getClasses());

            if ($rate) {
                $taxes[$tax->getName()] = $rate->calculateProductPriceIncludingTax($product, $price);
            }
        }

        return $taxes;
    }

    /**
     * Calculate VAT value for specified product and price
     * 
     * @param \XLite\Model\Product $product Product model object
     * @param float                $price   Price
     *  
     * @return float
     * @see    ____func_see____
     * @since  1.0.21
     */
    public function getVATValue(\XLite\Model\Product $product, $price)
    {
        $taxes = $this->calculateProductTaxes($product, $price);

        $taxTotal = 0;

        if (!empty($taxes)) {
            foreach ($taxes as $tax) {
                $taxTotal += $tax;
            }
        }

        return $taxTotal;
    }

    /**
     * Calculate product net price
     * 
     * @param \XLite\Model\Product $product Product
     * @param float                $price   Price
     *  
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function deductTaxFromPrice(\XLite\Model\Product $product, $price)
    {
        foreach ($this->getTaxes() as $tax) {
            $includedZones = $tax->getVATZone() ? array($tax->getVATZone()->getZoneId()) : array();
            $included = $tax->getFilteredRate($includedZones, $tax->getVATMembership(), $product->getClasses());

            if ($included) {
                $price -= $included->calculateProductPriceExcludingTax($product, $price);
            }
        }

        return $price;
    }

    // }}}
}
