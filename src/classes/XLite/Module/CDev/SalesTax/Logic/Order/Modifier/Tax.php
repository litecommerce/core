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

namespace XLite\Module\CDev\SalesTax\Logic\Order\Modifier;

/**
 * Tax  business logic
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Tax extends \XLite\Logic\Order\Modifier\ATax
{
    /**
     * Modifier unique code
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $code = 'CDEV.STAX';

    /**
     * Surcharge identification pattern
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $identificationPattern = '/^CDEV\.STAX\.\d+$/Ss';


    /**
     * Check - can apply this modifier or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function calculate()
    {
        $zones = $this->getZonesList();
        $memebrship = $this->getMembership();

        foreach ($this->getTaxes() as $tax) {
            $previousItems = array();
            $previousClasses = array();
            $cost = 0;
            $ratesExists = false;

            foreach ($tax->getFilteredRates($zones, $memebrship) as $rate) {
                $ratesExists = true;
                $productClass = $rate->getProductClass() ?: null;
                if (!in_array($productClass, $previousClasses)) {

                    $items = $this->getTaxableItems($productClass, $previousItems);
                    if ($items) {
                        $previousItems = array_merge($previousItems, $items);
                        $cost += $rate->calculate($items);
                    }

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
     * Get taxes 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTaxes()
    {
        return \XLite\Core\Database::getRepo('XLite\Module\CDev\SalesTax\Model\Tax')->findActive();
    }

    /**
     * Get zones list 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTaxableItems(\XLite\Model\ProductClass $class = null, array $previousItems = array())
    {
        $list = array();

        foreach ($this->getOrder()->getItems() as $item) {
            if (
                !in_array($item, $previousItems)
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
