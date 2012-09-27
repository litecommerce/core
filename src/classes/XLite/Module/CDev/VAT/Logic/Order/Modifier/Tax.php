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

namespace XLite\Module\CDev\VAT\Logic\Order\Modifier;

/**
 * Tax  business logic
 *
 */
class Tax extends \XLite\Logic\Order\Modifier\ATax
{
    /**
     * Modifier unique code
     *
     * @var string
     */
    protected $code = 'CDEV.VAT';

    /**
     * Surcharge identification pattern
     *
     * @var string
     */
    protected $identificationPattern = '/^CDEV\.VAT\.(\d+)(?:\.[A-Z]+)?$/Ss';


    /**
     * Check - can apply this modifier or not
     *
     * @return boolean
     */
    public function canApply()
    {
        return parent::canApply()
            && $this->getTaxes();
    }

    // {{{ Calculation

    /**
     * Calculate
     *
     * @return void
     */
    public function calculate()
    {
        $zones = $this->getZonesList();
        $membership = $this->getMembership();

        // Shipping cost VAT
        $modifier = $this->order->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING');
        $taxes = array();
        if ($modifier && $modifier->getSelectedRate() && $modifier->getSelectedRate()->getMethod()) {
            $taxes = $this->getShippingTaxRates($modifier->getSelectedRate(), $zones, $membership);
        }

        foreach ($this->getTaxes() as $tax) {
            
            $sum = 0;
            $rates = array();
            $taxId = $tax->getId();

            foreach ($this->getTaxableItems() as $item) {
                $product = $item->getProduct();
                $rate = $tax->getFilteredRate($zones, $membership, $product->getClasses());
                if ($rate) {
                    if (!isset($rates[$rate->getId()])) {
                        $rates[$rate->getId()] = array(
                            'rate' => $rate,
                            'base' => 0,
                        );
                    }

                    $rates[$rate->getId()]['base'] += $item->getDiscountedSubtotal();
                }
            }

            foreach ($rates as $rate) {
                if (isset($taxes[$taxId]) && $taxes[$taxId]['rate']->getId() == $rate['rate']->getId()) {
                    $rate['base'] += $this->order->getCurrency()->roundValue($taxes[$taxId]['base']);
                    unset($taxes[$taxId]);
                }

                $sum += $rate['rate']->calculateValueIncludingTax($rate['base']);
            }

            // Add shipping cost VAT
            if (isset($taxes[$taxId])) {
                $sum += $taxes[$taxId]['rate']->calculateValueIncludingTax($taxes[$taxId]['base']);
            }

            if ($sum) {
                $this->addOrderSurcharge(
                    $this->code . '.' . $taxId,
                    $sum,
                    false
                );
            }
        }
    }

    /**
     * Get shipping tax rates 
     * 
     * @param \XLite\Model\Shipping\Rate $rate       Shipping rate
     * @param array                      $zones      Zones list
     * @param \XLite\Model\Membership    $membership Membership OPTIONAL
     *  
     * @return void
     */
    protected function getShippingTaxRates(\XLite\Model\Shipping\Rate $rate, array $zones, \XLite\Model\Membership $membership = null)
    {
        $method = $rate->getMethod();

        $taxes = array();
        $price = $rate->getTaxableBasis();
        foreach ($this->getTaxes() as $tax) {
            $includedZones = $tax->getVATZone() ? array($tax->getVATZone()->getZoneId()) : array();
            $included = $tax->getFilteredRate($includedZones, $tax->getVATMembership(), $method->getClasses());
            $r = $tax->getFilteredRate($zones, $membership, $method->getClasses());

            if ($included) {
                $price -= $included->calculateValueExcludingTax($price);
            }
            if ($r) {
                $taxes[$tax->getId()] = array(
                    'rate' => $r,
                    'base' => $price,
                );
            }
        }

        return $taxes;
    }

    /**
     * Get taxes 
     * 
     * @return array
     */
    protected function getTaxes()
    {
        return \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->findActive();
    }

    /**
     * Get taxable order items 
     * 
     * @return array
     */
    protected function getTaxableItems()
    {
        $list = array();

        foreach ($this->getOrder()->getItems() as $item) {
            $product = $item->getProduct();
            if ($product) {
                $list[] = $item;
            }
        }

        return $list;
    }

    /**
     * Get zones list 
     * 
     * @return array
     */
    protected function getZonesList()
    {
        $address = $this->getAddress();

        $zones = $address ? \XLite\Core\Database::getRepo('XLite\Model\Zone')->findApplicableZones($address) : array();

        foreach ($zones as $i => $zone) {
            $zones[$i] = $zone->getZoneId();
        }

        return $zones;
    }

    /**
     * Get membership 
     * 
     * @return \XLite\Model\Membership
     */
    protected function getMembership()
    {
        return $this->getOrder()->getProfile()
            ? $this->getOrder()->getProfile()->getMembership()
            : null;
    }

    /**
     * Get address for zone calculator
     * 
     * @return array
     */
    protected function getAddress()
    {
        $address = null;

        $addressObj = $this->getOrderAddress();

        if ($addressObj) {

            // Profile is exists
            $address = array(
                'address' => $addressObj->getStreet(),
                'city'    => $addressObj->getCity(),
                'state'   => $addressObj->getState()->getStateId(),
                'zipcode' => $addressObj->getZipcode(),
                'country' => $addressObj->getCountry() ? $addressObj->getCountry()->getCode() : '',
            );
        }

        if (!isset($address)) {

            // Anonymous address
            $config = \XLite\Core\Config::getInstance()->Shipping;
            $address = array(
                'address' => $config->anonymous_address,
                'city'    => $config->anonymous_city,
                'state'   => $config->anonymous_state,
                'zipcode' => $config->anonymous_zipcode,
                'country' => $config->anonymous_country,
            );
        }

        return $address;
    }

    /**
     * Get order-based address 
     * 
     * @return \XLite\Model\Address
     */
    protected function getOrderAddress()
    {
        return ($this->getOrder()->getProfile() && $this->getOrder()->getProfile()->getBillingAddress())
            ? $this->getOrder()->getProfile()->getBillingAddress()
            : null;
    }

    // }}}

    // {{{ Surcharge operations

    /**
     * Get surcharge name
     *
     * @param \XLite\Model\Order\Surcharge $surcharge Surcharge
     *
     * @return \XLite\DataSet\Transport\Order\Surcharge
     */
    public function getSurchargeInfo(\XLite\Model\Base\Surcharge $surcharge)
    {
        $info = new \XLite\DataSet\Transport\Order\Surcharge;

        if (preg_match($this->identificationPattern, $surcharge->getCode(), $match)) {
            $id = intval($match[1]);
            $code = (isset($match[2]) && $match[2]) ? $match[2] : null;
            $tax = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->find($id);
            $info->name = $tax
                ? $tax->getName()
                : \XLite\Core\Translation::lbl('VAT');

        } else {
            $info->name = \XLite\Core\Translation::lbl('VAT');
        }

        $info->notAvailableReason = \XLite\Core\Translation::lbl('Billing address is not defined');

        return $info;
    }

    // }}}
}
