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

namespace XLite\Module\CDev\ProductOptions\Model;

/**
 * Product option surcharge
 *
 *
 * @Entity (repositoryClass="\XLite\Module\CDev\ProductOptions\Model\Repo\OptionSurcharge")
 * @Table (name="option_surcharges",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="ot", columns={"option_id","type"})
 *      }
 * )
 */
class OptionSurcharge extends \XLite\Model\AEntity
{
    /**
     * Modifier types
     */
    const PERCENT_MODIFIER  = '%';
    const ABSOLUTE_MODIFIER = '$';


    /**
     * Option surcharge unique id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer")
     */
    protected $surcharge_id;

    /**
     * Type
     *
     * @var string
     *
     * @Column (type="string", length=32)
     */
    protected $type = 'price';

    /**
     * Modifier
     *
     * @var float
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $modifier = 0.0000;

    /**
     * Modifier type
     *
     * @var string
     *
     * @Column (type="string", length=1)
     */
    protected $modifier_type = self::PERCENT_MODIFIER;

    /**
     * Option (relation)
     *
     * @var \XLite\Module\CDev\ProductOptions\Model\Option
     *
     * @ManyToOne (targetEntity="XLite\Module\CDev\ProductOptions\Model\Option", inversedBy="surcharges")
     * @JoinColumn (name="option_id", referencedColumnName="option_id")
     */
    protected $option;

    /**
     * Get modifier 
     * 
     * @return float
     */
    public function getModifier()
    {
        return doubleval($this->modifier);
    }

    /**
     * Set modifier type
     *
     * @param string $type Modifier type code
     *
     * @return boolean
     */
    public function setModifierType($type)
    {
        $types = $this->getRepository()->getModifierTypes();

        $result = false;

        if (isset($types[$type])) {
            $this->modifier_type = $type;
            $result = true;
        }

        return $result;
    }

    /**
     * Get surcharge sign
     *
     * @return string
     */
    public function getSign()
    {
        $sign = '';
        $value = $this->getAbsoluteValue();
        if (0 > $value) {
            $sign = '&minus;';

        } elseif (0 < $value) {
            $sign = '+';
        }

        return $sign;
    }

    /**
     * Check - empty surcharge or not
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return 0 == $this->getAbsoluteValue();
    }

    /**
     * Get surcharge absolute value
     *
     * @return float
     */
    public function getAbsoluteValue()
    {
        $methodName = $this->getCalculator();

        // methodName defined in getCalculator() method
        return $this->postprocessSurcharge($this->$methodName());
    }

    /**
     * Get surcharge positive absolute value
     *
     * @return float
     */
    public function getPositiveAbsoluteValue()
    {
        return abs($this->getAbsoluteValue());
    }

    /**
     * Get surcharge calculator method
     *
     * @return string
     */
    protected function getCalculator()
    {
        switch ($this->getModifierType()) {
            case self::ABSOLUTE_MODIFIER:
                $name = 'calculateAbsolute';
                break;

            default:
                $name = 'calculateRelative';
        }

        return $name;
    }

    /**
     * Calculate absolute surcharge type
     *
     * @return mixed
     */
    protected function calculateAbsolute()
    {
        return $this->getModifier();
    }

    /**
     * Calculate relative surcharge type
     *
     * @return mixed
     */
    protected function calculateRelative()
    {
        return doubleval($this->getSurchargeBase()) * $this->getModifier() / 100;
    }

    /**
     * Get surcharge base value
     *
     * @return float
     */
    protected function getSurchargeBase()
    {
        static $methodNames = array();

        $type = $this->getType();
        if (!isset($methodNames[$type])) {
            $name = 'getSurchargeBase' . \XLite\Core\Converter::convertToCamelCase($this->getType());
            $methodNames[$type] = method_exists($this, $name)
                ? $name
                : false;
        }

        return $methodNames[$type]
            ? $this->{$methodNames[$type]}()
            : null;
    }

    /**
     * Get price-based surcharge base value
     *
     * @return float
     */
    protected function getSurchargeBasePrice()
    {
        $product = $this->getOption()
            ->getGroup()
            ->getProduct();

        return $product->getPrice();
    }

    /**
     * Get weight-based surcharge base value
     *
     * @return integer
     */
    protected function getSurchargeBaseWeight()
    {
        return $this->getOption()
            ->getGroup()
            ->getProduct()
            ->getWeight();
    }

    /**
     * Postprocess surcharge
     *
     * @param float $surcharge Surcharge
     *
     * @return mixed
     */
    protected function postprocessSurcharge($surcharge)
    {
        static $methodNames = array();

        $type = $this->getType();
        if (!isset($methodNames[$type])) {
            $name = 'postprocessSurcharge' . \XLite\Core\Converter::convertToCamelCase($this->getType());
            $methodNames[$type] = method_exists($this, $name)
                ? $name
                : false;
        }

        return $methodNames[$type]
            ? $this->{$methodNames[$type]}($surcharge)
            : $surcharge;
    }

    /**
     * Postprocess price surcharge
     *
     * @param float $surcharge Surcharge
     *
     * @return float
     */
    protected function postprocessSurchargePrice($surcharge)
    {
        return round($surcharge, 2);
    }

    /**
     * Postprocess weight surcharge
     *
     * @param float $surcharge Surcharge
     *
     * @return integer
     */
    protected function postprocessSurchargeWeight($surcharge)
    {
        return round($surcharge, 2);
    }
}
