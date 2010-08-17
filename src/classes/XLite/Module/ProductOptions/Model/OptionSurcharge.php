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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\ProductOptions\Model;

/**
 * Product option surcharge
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity (repositoryClass="\XLite\Module\ProductOptions\Model\Repo\OptionSurcharge")
 * @Table (name="option_surcharges")
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
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer")
     */
    protected $surcharge_id;

    /**
     * Type
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="32")
     */
    protected $type = 'price';

    /**
     * Modifier 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="4", scale="12")
     */
    protected $modifier = 0.0000;

    /**
     * Modifier type 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="1")
     */
    protected $modifier_type = self::PERCENT_MODIFIER;

    /**
     * Option (relation)
     * 
     * @var    \XLite\Module\ProductOptions\Model\Option
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @ManyToOne (targetEntity="XLite\Module\ProductOptions\Model\Option", inversedBy="surcharges")
     * @JoinColumn (name="option_id", referencedColumnName="option_id")
     */
    protected $option;

    /**
     * Set modifier type 
     * 
     * @param string $type Modifier type code
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSign()
    {
        $sign = '';
        $value = $this->getAbsoluteValue();
        if (0 > $value) {
            $sign = '-';

        } elseif (0 < $value) {
            $sign = '+';
        }

        return $sign;
    }

    /**
     * Check - empty surcharge or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isEmpty()
    {
        return 0 == $this->getAbsoluteValue();
    }

    /**
     * Get surcharge absolute value 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAbsoluteValue()
    {
        $methodName = $this->getCalculator();

        // methodName defined in getCalculator() method
        return $this->postprocessSurcharge($this->$methodName());
    }

    /**
     * Get surcharge calculator method
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateAbsolute()
    {
        return $this->getModifier();
    }

    /**
     * Calculate relative surcharge type
     * 
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateRelative()
    {
        return doubleval($this->getSurchargeBase()) * $this->getModifier() / 100;
    }

    /**
     * Get surcharge base value
     * 
     * @return float
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSurchargeBasePrice()
    {
        $product = $this->getOption()
            ->getGroup()
            ->getProduct();

        // TODO - rework this

        global $calcAllTaxesInside;

        return $calcAllTaxesInside ? $product->getListPrice() : $product->getPrice();
    }

    /**
     * Get weight-based surcharge base value
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * Postprocess pruice surcharge 
     * 
     * @param float $surcharge Surcharge
     *  
     * @return float
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessSurchargeWeight($surcharge)
    {
        return round($surcharge, 2);
    }

}
