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

namespace XLite\Module\CDev\SalesTax\Logic\Order\Modifier;

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
    protected $code = 'CDEV.STAX';

    /**
     * Surcharge identification pattern
     *
     * @var string
     */
    protected $identificationPattern = '/^CDEV\.STAX\.\d+$/Ss';


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

        foreach ($this->getTaxes() as $tax) {
            $previousItems = array();
            $previousClasses = array();
            $cost = 0;
            $ratesExists = false;

            foreach ($tax->getFilteredRates($zones, $membership) as $rate) {
                $ratesExists = true;
                $productClass = $rate->getProductClass() ?: null;
                if (!in_array($productClass, $previousClasses)) {

                    // Get tax cost for products in the cart with specified product class
                    $items = $this->getTaxableItems($productClass, $previousItems);
                    if ($items) {
                        foreach ($items as $item) {
                            $previousItems[] = $item->getProduct()->getProductId();
                        }
                        $cost += $rate->calculate($items);
                    }

                    // Add shipping tax cost
                    $cost += $rate->calculateShippingTax($this->getTaxableShippingCost($productClass));

                    $previousClasses[] = $productClass;
                }
            }

            if ($cost) {
                $this->addOrderSurcharge(
                    $this->code . '.' . $tax->getId(),
                    doubleval($cost),
                    false,
                    $ratesExists
                );
            }
        }
    }

    /**
     * Get taxable shipping cost
     *
     * @param \XLite\Model\ProductClass $class Product class object
     *
     * @return float
     */
    protected function getTaxableShippingCost($class)
    {
        $result = 0;

        $modifier = $this->order->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING');

        if ($modifier && $modifier->getSelectedRate() && $modifier->getSelectedRate()->getMethod()) {

            $rate = $modifier->getSelectedRate();

            if (!$class || ($class && $rate->getMethod()->getClasses()->contains($class))) {
                $result = $rate->getTaxableBasis();
            }
        }

        return $result;
    }

    /**
     * Get taxes 
     * 
     * @return array
     */
    protected function getTaxes()
    {
        return \XLite\Core\Database::getRepo('XLite\Module\CDev\SalesTax\Model\Tax')->findActive();
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
     * Get taxable items 
     * 
     * @param \XLite\Model\ProductClass $class         Product class OPTIONAL
     * @param array                     $previousItems Previous selected items OPTIONAL
     *  
     * @return array
     */
    protected function getTaxableItems(\XLite\Model\ProductClass $class = null, array $previousItems = array())
    {
        $list = array();

        foreach ($this->getOrder()->getItems() as $item) {
            if (
                !in_array($item->getProduct()->getProductId(), $previousItems)
                && (!$class || ($class && $item->getProductClasses()->contains($class)))
            ) {
                $list[] = $item;
            }
        }

        return $list;
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

        if (0 === strpos($surcharge->getCode(), $this->code . '.')) {
            $id = intval(substr($surcharge->getCode(), strlen($this->code) + 1));
            $tax = \XLite\Core\Database::getRepo('XLite\Module\CDev\SalesTax\Model\Tax')->find($id);
            $info->name = $tax
                ? $tax->getName()
                : \XLite\Core\Translation::lbl('Sales tax');

        } else {
            $info->name = \XLite\Core\Translation::lbl('Sales tax');
        }

        $info->notAvailableReason = \XLite\Core\Translation::lbl('Billing address is not defined');

        return $info;
    }

    // }}}
}
