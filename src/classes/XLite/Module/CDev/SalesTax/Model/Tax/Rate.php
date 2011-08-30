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

namespace XLite\Module\CDev\SalesTax\Model\Tax;

/**
 * Rate
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity
 * @Table (name="sales_tax_rates")
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
     * @var   \XLite\Module\CDev\SalesTax\Model\Tax
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Module\CDev\SalesTax\Model\Tax", inversedBy="rates")
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
     * @ManyToOne  (targetEntity="XLite\Model\ProductClass")
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
     * Check - rate is applyed by specified zones and membership or nopt
     *
     * @param array                   $zones      Zone id list
     * @param \XLite\Model\Membership $membership Membership
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isApplyed(
        array $zones,
        \XLite\Model\Membership $membership = null
    ) {
        return (!$this->getZone() || in_array($this->getZone()->getZoneId(), $zones))
            && (!$this->getMembership() || ($membership && $this->getMembership()->getMembershipId() == $membership->getMembershipId()));
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

        if ($this->getBasis($items) && $this->getQuantity($items)) {
            $cost = $this->getType() == static::TYPE_PERCENT
                ? $this->calculatePercent($items)
                : $this->calculateAbsolute($items);
        }

        return $cost;
    }

    /**
     * Get basis 
     * 
     * @param array $items Items
     *  
     * @return float
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
     * Get quantity 
     * 
     * @param array $items Items
     *  
     * @return integer
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
     * calculateExcludePercent 
     * 
     * @param array $items ____param_comment____
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function calculatePercent(array $items)
    {
        return $this->getBasis($items) * $this->getValue() / 100;
    }

    /**
     * Calculate tax as percent
     * 
     * @param array $items Items
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function calculateAbsolute(array $items)
    {
        return $this->getValue() * $this->getQuantity();
    }

    // }}}
}
