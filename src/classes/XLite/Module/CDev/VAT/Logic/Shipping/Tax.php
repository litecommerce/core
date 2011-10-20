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

namespace XLite\Module\CDev\VAT\Logic\Shipping;

/**
 * Tax business logic for shipping cost
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Tax extends \XLite\Logic\ALogic
{

    // {{{ Calculation

    /**
     * Calculate rate cost
     * 
     * @param \XLite\Model\Shipping\Rate $rate Rate
     * @param float                      $price   Price
     *  
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function calculatRateCost(\XLite\Model\Shipping\Rate $rate, $price)
    {
        $zones = $this->getZonesList();
        $memebrship = $this->getMembership();
        $method = $rate->getMethod();

        foreach ($this->getTaxes() as $tax) {
            $includedZones = $tax->getVATZone() ? array($tax->getVATZone()->getZoneId()) : array();
            $included = $tax->getFilteredRate($includedZones, $tax->getVATMembership(), $method->getClasses());

            if ($included) {
                $price -= $included->calculateValueExcludingTax($price);
            }
        }

        return $price;
    }

    /**
     * Calculate rate net cost
     * 
     * @param \XLite\Model\Shipping\Rate $rate Rate
     * @param float                      $price   Price
     *  
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function calculateRateNetCost(\XLite\Model\Shipping\Rate $rate, $price)
    {
        $zones = $this->getZonesList();
        $memebrship = $this->getMembership();
        $method = $rate->getMethod();

        foreach ($this->getTaxes() as $tax) {
            $includedZones = $tax->getVATZone() ? array($tax->getVATZone()->getZoneId()) : array();
            $included = $tax->getFilteredRate($includedZones, $tax->getVATMembership(), $method->getClasses());
            $r = $tax->getFilteredRate($zones, $memebrship, $method->getClasses());

            if ($included != $r && $included) {
                $price -= $included->calculateValueExcludingTax($price);
            }
        }

        return $price;
    }

    /**
     * Calculate rate-based included taxes
     * 
     * @param \XLite\Model\Shipping\Rate $rate Rate
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function calculateRateTaxes(\XLite\Model\Shipping\Rate $rate)
    {
        $zones = $this->getZonesList();
        $memebrship = $this->getMembership();
        $method = $rate->getMethod();

        $taxes = array();
        $price = $rate->getTaxableBasis();
        foreach ($this->getTaxes() as $tax) {
            $includedZones = $tax->getVATZone() ? array($tax->getVATZone()->getZoneId()) : array();
            $included = $tax->getFilteredRate($includedZones, $tax->getVATMembership(), $method->getClasses());
            $r = $tax->getFilteredRate($zones, $memebrship, $method->getClasses());

            if ($included) {
                $price -= $included->calculateValueExcludingTax($price);
            }
            if ($r) {
                $taxes[$tax->getId()] = array(
                    'rate' => $r,
                    'cost' => $r->calculateValueIncludingTax($price),
                );
            }
        }

        return $taxes;
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
        return \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->findActive();
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
        return $this->getProfile()->getMembership();
    }

    /**
     * Get profile 
     * 
     * @return \XLite\Model\Profile
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProfile()
    {
        $controller = \XLite::getController();

        $profile = $controller instanceOf \XLite\Controller\Customer\ACustomer
            ? $controller->getCart()->getProfile()
            : \XLite\Core\Auth::getInstance()->getProfile();

        return $profile ?: $this->getDefaultProfile();
    }

    /**
     * Get default profile if user is not authorized
     * 
     * @return \XLite\Model\Profile
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultProfile()
    {
        return new \XLite\Model\Profile;
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

        $addressObj = $this->getProfile()->getBillingAddress();

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

    // }}}
}
