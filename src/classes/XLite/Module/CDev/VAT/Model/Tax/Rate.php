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

namespace XLite\Module\CDev\VAT\Model\Tax;

/**
 * Rate
 *
 * @see   ____class_see____
 * @since 1.0.0
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
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Value
     *
     * @var   float
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $value = 0.0000;

    /**
     * Type
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="fixedstring", length="1")
     */
    protected $type = self::TYPE_PERCENT;

    /**
     * Position
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $position = 0;

    /**
     * Tax (relation)
     *
     * @var   \XLite\Module\CDev\VAT\Model\Tax
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Module\CDev\VAT\Model\Tax", inversedBy="rates")
     * @JoinColumn (name="tax_id", referencedColumnName="id")
     */
    protected $tax;

    /**
     * Zone (relation)
     *
     * @var   \XLite\Model\Zone
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Zone", inversedBy="tax_rates")
     * @JoinColumn (name="zone_id", referencedColumnName="zone_id")
     */
    protected $zone;

    /**
     * Product class (relation)
     *
     * @var   \XLite\Model\ProductClass
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\ProductClass", inversedBy="tax_rates")
     * @JoinColumn (name="product_class_id", referencedColumnName="id")
     */
    protected $productClass;

    /**
     * Membership (relation)
     *
     * @var   \XLite\Model\Membership
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Membership", inversedBy="tax_rates")
     * @JoinColumn (name="membership_id", referencedColumnName="membership_id")
     */
    protected $membership;

    /**
     * Set zone 
     * 
     * @param \XLite\Model\Zone $zone Zone OPTIONAL
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setMembership(\XLite\Model\Membership $membership = null)
    {
        $this->membership = $membership;
    }

    /**
     * Check - rate is applyed by specified zones and membership or nopt
     *
     * @param array                                   $zones          Zone id list
     * @param \XLite\Model\Membership                 $membership     Membership OPTIONAL
     * @param \Doctrine\Common\Collections\Collection $productClasses Product classes OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isApplyed(
        array $zones,
        \XLite\Model\Membership $membership = null,
        \Doctrine\Common\Collections\Collection $productClasses = null
    ) {
        return (!$this->getZone() || in_array($this->getZone()->getZoneId(), $zones))
            && (!$this->getMembership() || ($membership && $this->getMembership()->getMembershipId() == $membership->getMembershipId()))
            && (2 == func_num_args() || !$this->getProductClass() || ($productClasses && $productClasses->contains($this->getProductClass())));
    }

    // {{{ Calculation

    /**
     * Calculate 
     * 
     * @param array $items Items
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function calculate(array $items)
    {
        $cost = 0;
        $list = array();

        if ($this->getBasis($items) && $this->getQuantity($items)) {
            list($cost, $list) = $this->getType() == static::TYPE_PERCENT
                ? $this->calculateIncludePercent($items)
                : $this->calculateIncludeAbsolute($items);
        }

        return array($cost, $list);
    }

    /**
     * getBasis 
     * 
     * @param array $items ____param_comment____
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBasis(array $items)
    {
        $basis = 0;

        foreach ($items as $item) {
            $basis += $item->getTaxableBasis() * $item->getAmount();
        }

        return $basis;
    }

    /**
     * Get product taxable basis 
     * 
     * @param \XLite\Model\Product $product Product
     *  
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProductBasis(\XLite\Model\Product $product)
    {
        return $product->getTaxableBasis();
    }

    /**
     * getQuantity 
     * 
     * @param array $items ____param_comment____
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getQuantity(array $items)
    {
        $quantity = 0;

        foreach ($items as $item) {
            $quantity += $item->getAmount();
        }

        return $quantity;
    }

    /**
     * calculateIncludePercent 
     * 
     * @param array $items ____param_comment____
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function calculateIncludePercent(array $items)
    {
        $base = $this->getBasis($items);

        $cost = $base - $base / (100 + $this->getValue()) * 100;

        $list = array();

        foreach ($items as $item) {
            $list[] = array(
                'item' => $item,
                'cost' => $item->getTaxableBasis() - $item->getTaxableBasis() / (100 + $this->getValue()) * 100,
            );
        }

        return array($cost, $list);
    }

    /**
     * calculateIncludeAbsolute 
     * 
     * @param array $items ____param_comment____
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function calculateIncludeAbsolute(array $items)
    {
        $cost = $this->getValue() * $this->getQuantity();

        $list = array();

        foreach ($items as $item) {
            $list[] = array(
                'item' => $item,
                'cost' => $item->getAmount() * $this->getValue(),
            );
        }

        return array($cost, $list);
    }

    // }}}

    // {{{ Product price calculation

    /**
     * Calculate pure product price (excluding tax)
     *
     * @param \XLite\Model\Product $product Product
     * @param float                $price   Price
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function calculateProductPriceExcludingTax(\XLite\Model\Product $product, $price)
    {
        return $this->getProductBasis($product)
            ? $this->calculateValueExcludingTax($price)
            : 0;
    }

    /**
     * Calculate value excluding tax 
     * 
     * @param float $base Base
     *  
     * @return float
     * @see    ____func_see____
     * @since  1.0.8
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function calculateProductPriceIncludingTax(\XLite\Model\Product $product, $price)
    {
        return $this->getProductBasis($product)
            ? $this->calculateValueIncludingTax($price)
            : 0;
    }

    /**
     * Calculate value including tax
     *
     * @param float $base Base
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.8
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.8
     */
    public function getExcludeTaxFormula($priceField)
    {
        return $this->getType() == self::TYPE_PERCENT
            ? $priceField . ' - ' . $priceField . ' / ' . ((100 + $this->getValue()) / 100)
            : $this->getValue();
    }

    /**
     * Get include tax formula 
     * 
     * @param string $priceField Product price field
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.8
     */
    public function getIncludeTaxFormula($priceField)
    {
        return $this->getType() == self::TYPE_PERCENT
            ? $priceField . ' * ' . ($this->getValue() / 100)
            : $this->getValue();
    }

    // }}}
}
