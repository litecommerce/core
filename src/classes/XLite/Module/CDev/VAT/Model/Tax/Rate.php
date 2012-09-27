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

namespace XLite\Module\CDev\VAT\Model\Tax;

/**
 * Rate
 *
 *
 * @Entity
 * @Table (name="vat_tax_rates")
 */
class Rate extends \XLite\Model\AEntity
{
    /**
     * Rate type codes
     */
    const TYPE_ABSOLUTE = 'a';
    const TYPE_PERCENT  = 'p';


    /**
     * Product unique ID
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Value
     *
     * @var float
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $value = 0.0000;

    /**
     * Type
     *
     * @var string
     *
     * @Column (type="fixedstring", length=1)
     */
    protected $type = self::TYPE_PERCENT;

    /**
     * Position
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $position = 0;

    /**
     * Tax (relation)
     *
     * @var \XLite\Module\CDev\VAT\Model\Tax
     *
     * @ManyToOne  (targetEntity="XLite\Module\CDev\VAT\Model\Tax", inversedBy="rates")
     * @JoinColumn (name="tax_id", referencedColumnName="id")
     */
    protected $tax;

    /**
     * Zone (relation)
     *
     * @var \XLite\Model\Zone
     *
     * @ManyToOne  (targetEntity="XLite\Model\Zone")
     * @JoinColumn (name="zone_id", referencedColumnName="zone_id")
     */
    protected $zone;

    /**
     * Product class (relation)
     *
     * @var \XLite\Model\ProductClass
     *
     * @ManyToOne  (targetEntity="XLite\Model\ProductClass")
     * @JoinColumn (name="product_class_id", referencedColumnName="id")
     */
    protected $productClass;

    /**
     * Membership (relation)
     *
     * @var \XLite\Model\Membership
     *
     * @ManyToOne  (targetEntity="XLite\Model\Membership")
     * @JoinColumn (name="membership_id", referencedColumnName="membership_id")
     */
    protected $membership;

    /**
     * Set zone 
     * 
     * @param \XLite\Model\Zone $zone Zone OPTIONAL
     *  
     * @return void
     */
    public function setZone(\XLite\Model\Zone $zone = null)
    {
        $this->zone = $zone;
    }

    /**
     * Set product class 
     * 
     * @param \XLite\Model\ProductClass $class Product class OPTIONAL
     *  
     * @return void
     */
    public function setProductClass(\XLite\Model\ProductClass $class = null)
    {
        $this->productClass = $class;
    }

    /**
     * Set membership 
     * 
     * @param \XLite\Model\Membership $membership Membership OPTIONAL
     *  
     * @return void
     */
    public function setMembership(\XLite\Model\Membership $membership = null)
    {
        $this->membership = $membership;
    }

    /**
     * Check if rate is applied by specified zones and membership
     *
     * @param array                                   $zones          Zone id list
     * @param \XLite\Model\Membership                 $membership     Membership OPTIONAL
     * @param \Doctrine\Common\Collections\Collection $productClasses Product classes OPTIONAL
     *
     * @return boolean
     */
    public function isApplied(
        array $zones,
        \XLite\Model\Membership $membership = null,
        \Doctrine\Common\Collections\Collection $productClasses = null
    ) {

        $result = !$this->getZone() || in_array($this->getZone()->getZoneId(), $zones);

        if ($result && $this->getMembership()) {
            $result = $membership && $this->getMembership()->getMembershipId() == $membership->getMembershipId();
        }

        if ($result && 2 < func_num_args()) {
            $result = !$this->getProductClass()
                || ($productClasses && $productClasses->contains($this->getProductClass()));
        }

        return $result;
    }

    // {{{ Product price calculation

    /**
     * Calculate and return tax rate value for price which includes tax rate
     *
     * @param \XLite\Model\Product $product Product
     * @param float                $price   Price
     *
     * @return float
     */
    public function calculateProductPriceExcludingTax(\XLite\Model\Product $product, $price)
    {
        return $price
            ? $this->calculateValueExcludingTax($price)
            : 0;
    }

    /**
     * Calculate value excluding tax 
     * 
     * @param float $base Base
     *  
     * @return float
     */
    public function calculateValueExcludingTax($base)
    {
        return $this->getType() == static::TYPE_PERCENT
            ? $this->calculatePriceIncludePercent($base)
            : $this->calculatePriceIncludeAbsolute($base);
    }

    /**
     * Calculate product price including tax
     *
     * @param \XLite\Model\Product $product Product
     * @param float                $price   Pure price, without including tax
     *
     * @return float
     */
    public function calculateProductPriceIncludingTax(\XLite\Model\Product $product, $price)
    {
        return $price
            ? $this->calculateValueIncludingTax($price)
            : 0;
    }

    /**
     * Calculate value including tax
     *
     * @param float $base Base
     *
     * @return float
     */
    public function calculateValueIncludingTax($base)
    {
        return $this->getType() == static::TYPE_PERCENT
            ? $this->calculatePriceExcludePercent($base)
            : $this->calculatePriceExcludeAbsolute($base);
    }

    /**
     * Calculate VAT for single product price (percent value)
     *
     * @param float $price Price
     *
     * @return float
     */
    protected function calculatePriceIncludePercent($price)
    {
        return $price - $price / (100 + $this->getValue()) * 100;
    }

    /**
     * Calculate VAT for single product price (absolute value)
     * 
     * @param float $price Price
     *  
     * @return float
     */
    protected function calculatePriceIncludeAbsolute($price)
    {
        return $this->getValue();
    }

    /**
     * Calculate product price's excluded tax (as percent)
     * 
     * @param float $price Product price
     *  
     * @return float
     */
    protected function calculatePriceExcludePercent($price)
    {
        return $price * $this->getValue() / 100;
    }

    /**
     * Calculate product price's excluded tax (as absolute)
     * 
     * @param float $price Price
     *  
     * @return float
     */
    protected function calculatePriceExcludeAbsolute($price)
    {
        return $this->getValue();
    }

    // }}}

    // {{{ Search conditions

    /**
     * Get exclude tax formula 
     * 
     * @param string $priceField Product price field
     *  
     * @return string
     */
    public function getExcludeTaxFormula($priceField)
    {
        return $this->getType() == self::TYPE_PERCENT
            ? $priceField . ' - ' . $priceField . ' / ' . ((100 + $this->getValue()) / 100)
            : $this->getValue();
    }

    // }}}
}
