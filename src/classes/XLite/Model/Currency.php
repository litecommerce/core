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

namespace XLite\Model;

/**
 * Currency
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity
 * @Table (name="currencies",
 *      indexes = {
 *          @Index (name="code", columns={"code"})
 *      }
 * )
 */
class Currency extends \XLite\Model\Base\I18n
{
    /**
     * Currency unique id (ISO 4217 number)
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @Column (type="uinteger")
     */
    protected $currency_id;

    /**
     * Currency code (ISO 4217 alpha-3)
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="fixedstring", length="3", unique=true)
     */
    protected $code;

    /**
     * Symbol
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="16")
     */
    protected $symbol = '';

    /**
     * Number of digits after the decimal separator.
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $e = 0;

    /**
     * Currency symbol is displayed before the price
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $symbolBefore = true;

    /**
     * Decimal part delimiter
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="8")
     */
    protected $decimalDelimiter = '.';

    /**
     * Thousand delimier
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="8")
     */
    protected $thousandDelimiter = '';

    /**
     * Orders
     *
     * @var   \Doctrine\Common\Collections\Collection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Order", mappedBy="currency")
     */
    protected $orders;

    /**
     * Country
     *
     * @var   \XLite\Model\Country
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToOne   (targetEntity="XLite\Model\Country", inversedBy="currency")
     * @JoinColumn (name="country_code", referencedColumnName="code")
     */
    protected $country;


    /**
     * Set currency Id
     *
     * @param integer $value Currency id
     * TODO - Doctrine is not generate setter for identifier. We must reworkt it
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setCurrencyId($value)
    {
        $this->currency_id = $value;
    }

    /**
     * Round value
     *
     * @param float $value Value
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function roundValue($value)
    {
        return \XLite\Logic\Math::getInstance()->roundByCurrency($value, $this);
    }

    /**
     * Round value as integer
     *
     * @param float $value Value
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function roundValueAsInteger($value)
    {
        return intval(round($this->roundValue($value) * pow(10, $this->getE()), 0));
    }

    /**
     * Convert integer to float
     *
     * @param integer $value Value
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function convertIntegerToFloat($value)
    {
        return $value / pow(10, $this->getE());
    }

    /**
     * Format value
     *
     * @param float $value Value
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function formatValue($value)
    {
        return implode('', $this->formatParts($value));
    }

    /**
     * Get minimum value 
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function getMinimumValue()
    {
        return $this->convertIntegerToFloat(1);
    }

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $data = array())
    {
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Format value as parts list
     * 
     * @param float $value Value
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function formatParts($value)
    {
        return \XLite\Logic\Math::getInstance()->formatParts($value, $this);
    }

}
