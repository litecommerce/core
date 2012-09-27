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

namespace XLite\Module\CDev\VAT\Logic\Shipping;

/**
 * Tax business logic for shipping cost
 *
 */
class Tax extends \XLite\Module\CDev\VAT\Logic\ATax
{

    // {{{ Calculation

    /**
     * Calculate rate cost
     * 
     * @param \XLite\Model\Shipping\Rate $rate  Rate
     * @param float                      $price Price
     *  
     * @return float
     */
    public function calculateRateCost(\XLite\Model\Shipping\Rate $rate, $price)
    {
        return $this->deductTaxFromPrice($rate, $price);
    }

    /**
     * Calculate shipping net price
     * 
     * @param \XLite\Model\Shipping\Rate $rate  Rate
     * @param float                      $price Price
     *  
     * @return float
     */
    public function deductTaxFromPrice(\XLite\Model\Shipping\Rate $rate, $price)
    {
        $classes = $rate->getMethod() ? $rate->getMethod()->getClasses() : null;

        foreach ($this->getTaxes() as $tax) {
            $includedZones = $tax->getVATZone() ? array($tax->getVATZone()->getZoneId()) : array();
            $included = $tax->getFilteredRate($includedZones, $tax->getVATMembership(), $classes);

            if ($included) {
                $price -= $included->calculateValueExcludingTax($price);
            }
        }

        return $price;
    }

    // }}}
}
