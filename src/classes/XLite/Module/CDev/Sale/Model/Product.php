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

namespace XLite\Module\CDev\Sale\Model;

/**
 * Product
 *
 *
 * @HasLifecycleCallbacks
 */
class Product extends \XLite\Model\Product implements \XLite\Base\IDecorator
{
    /**
     * The "Discount type" field is equal to this constant if it is "Sale price"
     */
    const SALE_DISCOUNT_TYPE_PRICE   = 'sale_price';

    /**
     * The "Discount type" field is equal to this constant if it is "Percent off"
     */
    const SALE_DISCOUNT_TYPE_PERCENT = 'sale_percent';

    /**
     * Flag, if the product participates in the sale
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $participateSale = false;

    /**
     * self::SALE_DISCOUNT_TYPE_PRICE   if "sale value" is considered as "Sale price",
     * self::SALE_DISCOUNT_TYPE_PERCENT if "sale value" is considered as "Percent Off".
     *
     * @var string
     *
     * @Column (type="string", length=32, nullable=false)
     */
    protected $discountType = self::SALE_DISCOUNT_TYPE_PRICE;

    /**
     * "Sale value"
     *
     * @var float
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $salePriceValue = 0;

    /**
     * "Sale value" price calculated
     *
     * @var float
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $salePriceValueCalculated = 0;

    /**
     * Get discount type 
     * 
     * @return string
     */
    public function getDiscountType()
    {
        return $this->discountType ?: self::SALE_DISCOUNT_TYPE_PRICE;
    }

    /**
     * Calculate "Sale percent off" value.
     * Based on "salePriceValue" and "discountType" fields values
     * 
     * @return float
     */
    public function getSalePercent()
    {
        $percent = 0;

        switch ($this->getDiscountType()) {

            case self::SALE_DISCOUNT_TYPE_PERCENT:
                $percent = $this->getSalePriceValue();
                break;

            case self::SALE_DISCOUNT_TYPE_PRICE:
                $price = $this->getPrice();
                $percent = (0 < $price)
                    ? (($price - $this->getSalePriceValue()) / $price * 100)
                    : 0;
                break;

            default :
        }

        return $percent;
    }

    /**
     * Return sale product price
     *
     * @param float $value Base OPTIONAL
     *
     * @return float
     */
    public function getSalePrice($value = null)
    {
        $salePrice = $price = isset($value) ? $value : $this->getPrice();

        if ($this->getParticipateSale()) {
            switch ($this->getDiscountType()) {

                case self::SALE_DISCOUNT_TYPE_PERCENT:
                    $salePrice = $price * (1 - $this->getSalePriceValue() / 100);
                    break;

                case self::SALE_DISCOUNT_TYPE_PRICE:
                    $salePrice = $this->getSalePriceValue();
                    break;

                default :
            }
        }

        return min($salePrice, $price);
    }

    /**
     * Return sale product price difference
     *
     * @return float
     */
    public function getSalePriceDifference()
    {
        $difference = 0;
        $price = $this->getPrice();

        if ($this->getParticipateSale()) {
            switch ($this->getDiscountType()) {

                case self::SALE_DISCOUNT_TYPE_PERCENT:
                    $difference = $price * $this->getSalePriceValue()/100;
                    break;

                case self::SALE_DISCOUNT_TYPE_PRICE:
                    $difference = $price - $this->getSalePriceValue();
                    break;

                default :
            }
        }

        return $difference;
    }

    /**
     * Return product list price (price for customer interface)
     *
     * @return float
     */
    public function getClearPrice()
    {
        return $this->getSalePrice();
    }

    /**
     * Prepare update date
     *
     * @return void
     *
     * @PreUpdate
     */
    public function prepareUpdateSalePriceCalculatedFields()
    {
        $this->setSalePriceValueCalculated($this->getSalePrice());
    }
}
