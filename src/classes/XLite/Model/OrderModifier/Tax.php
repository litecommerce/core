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

namespace XLite\Model\OrderModifier;

/**
 * Tax order modifier
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Tax extends \XLite\Model\Order implements \XLite\Base\IDecorator
{
    const MODIFIER_TAX = 'tax';

    /**
     * Shipping taxes (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $shippingTaxes = array();

    /**
     * Define order modifiers 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineModifiers()
    {
        $list = parent::defineModifiers();

        $list[20] = self::MODIFIER_TAX;

        return $list;
    }

    /**
     * Calculate shipping 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateTax()
    {
        // Base tax cost
        $this->calculateAllTaxes();

        // Shipping-based tax cost
        $shippingTax = 0;

        if ($this->isShippingSelected()) {
            $shippingTaxes = $this->shippingTaxes;
            if (is_array($shippingTaxes)) {
                if (\XLite\Core\Config::getInstance()->Taxes->prices_include_tax && isset($shippingTaxes['Tax'])) {
                    $shippingTax = $shippingTaxes['Tax'];

                } else {
                    foreach ($shippingTaxes as $name => $value) {
                        if (
                            isset($taxes[$name])
                            && (\XLite\Core\Config::getInstance()->Taxes->prices_include_tax || $taxes[$name] == $value)
                        ) {
                            $shippingTax += $value;
                        }
                    }
                }
            }
        }

        // Save shipping tax
        $this->saveModifier(self::MODIFIER_TAX, $shippingTax, 'shipping_tax');

        // Save all visible taxes
        $taxes = $this->getDisplayTaxes();
        if ($taxes) {
            foreach ($taxes as $name => $value) {
                $this->saveModifier(self::MODIFIER_TAX, $value, $name);
            }

        } else {
            $this->saveModifier(self::MODIFIER_TAX, 0);
        }
    }

    /**
     * Check - tax is visible or not
     * 
     * @param string $subcode Subcode (tax name)
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isTaxVisible($subcode)
    {
        $result = true;

        if ('shipping_tax' == $subcode) {
            $result = false;
        }

        return $result;
    }

    /**
     * Get tax name
     * 
     * @param string $subcode Subcode (tax name)
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTaxName($subcode)
    {
        return $subcode ?: 'Tax';
    }

    /**
     * Check - tax is available or not
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTaxAvailable()
    {
        return 0 < count($this->getDisplayTaxes());
    }

    /**
     * Check - tax is summable or not
     * 
     * @param string $subcode Subcode (tax name)
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTaxSummable($subcode)
    {
        return 'Tax' == $subcode;
    }

    /**
     * Get display taxes list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDisplayTaxes() 
    {
        $taxes = array();

        if (
            !is_null($this->getProfile())
            || \XLite\Core\Config::getInstance()->General->def_calc_shippings_taxes
        ) {

            $taxRates = new \XLite\Model\TaxRates();
            $values = $names = $orderby = array();
            foreach ((array)$this->getTaxes() as $name => $value) {
                if ($taxRates->getTaxLabel($name)) {
                    $values[] = $value;
                    $names[] = $name;
                    $orderby[] = $taxRates->getTaxPosition($name);
                }
            }

            // sort taxes according to $orderby
            array_multisort($orderby, $values, $names);

            if (!empty($names)) {
                $taxes = array_combine($names, $values);
            }
        }

        return $taxes;
    }

    /**
     * Get tax label 
     * 
     * @param string $name Tax name
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTaxLabel($name) 
    {
        $tax = new \XLite\Model\TaxRates();

        return $tax->getTaxLabel($name);
    }

    /**
     * Get registration 
     * 
     * @param string $name Tax name
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRegistration($name) 
    {
        $tax = new \XLite\Model\TaxRates();

        return $tax->getRegistration($name);
    }

    /**
     * Check - any tax is registered  or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTaxRegistered()
    {
        $result = false;

        foreach ((array)$this->getTaxes() as $name => $value) {
            if ($this->getRegistration($name) != '') {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Calculate and return all order taxes
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateAllTaxes() 
    {
        $taxRates = new \XLite\Model\TaxRates();
        $taxRates->set('order', $this);
        $result = array();
        foreach ($this->getItems() as $item) {
            $product = $item->getProduct();
            if (\XLite\Core\Config::getInstance()->Taxes->prices_include_tax && isset($product)) {
                $item->setPrice($product->getPrice());
            }

            $taxRates->set('orderItem', $item);
            $taxRates->calculateTaxes();

            $result = $this->addTaxes($result, $taxRates->get('allTaxes'));
        }

        // tax on shipping
        $pricesIncludeTax = \XLite\Core\Config::getInstance()->Taxes->prices_include_tax;
        if (
            $this->isShippingSelected()
            && (!$pricesIncludeTax || ($pricesIncludeTax && $taxRates->isShippingDefined()))
        ) {
            $taxRates->_conditionValues['product class'] = 'shipping service';
            $taxRates->_conditionValues['cost'] = $this->getTotalByModifier('shipping');
            $taxRates->calculateTaxes();
            $result = $this->addTaxes($result, $taxRates->get('allTaxes'));

            // Calculate shipping taxes
            $this->shippingTaxes = $this->addTaxes(array(), $taxRates->get('shippingTaxes'));

            // Prepared shipping taxes values
            foreach ($this->shippingTaxes as $name => $value) {
                $this->shippingTaxes[$name] = \XLite\Core\Converter::formatCurrency($value);
            }
        }

        // Prepared all tax values
        foreach ($result as $name => $value) {
            $result[$name] = \XLite\Core\Converter::formatCurrency($result[$name]);
        }

        $this->setTaxes($result);
    }

    /**
     * Add new taxes into existsing taxes list
     * 
     * @param array $acc   Existing taxes list
     * @param array $taxes New taxes
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addTaxes(array $acc, array $taxes) 
    {
        foreach ($taxes as $tax => $value) {
            if (!isset($acc[$tax])) {
                $acc[$tax] = 0;
            }
            $acc[$tax] += $value;
        }

        return $acc;
    }

    /**
     * Check - is tax defined or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTaxDefined() 
    {
        return true;
    }

}
