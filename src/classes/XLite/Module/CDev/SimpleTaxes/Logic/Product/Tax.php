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

namespace XLite\Module\CDev\SimpleTaxes\Logic\Product;

/**
 * Tax business logic
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Tax extends \XLite\Logic\ALogic
{
    // {{{ Calculation

    /**
     * Calculate product price
     * 
     * @param \XLite\Model\Product $product Product
     * @param float                $price   Price
     *  
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function calculateProductPrice(\XLite\Model\Product $product, $price)
    {
        $zones = $this->getZonesList();
        $memebrship = $this->getMembership();

        foreach ($this->getTaxes() as $tax) {
            foreach ($tax->getFilteredRates($zones, $memebrship, $product->getClasses()) as $rate) {
                $price -= $rate->calculateProductPrice($product, $price);
                break;
            }
        }

        return $price;
    }

    /**
     * Calculate product-based included taxes
     * 
     * @param \XLite\Model\Product $product Product
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function calculateProduct(\XLite\Model\Product $product)
    {
        $zones = $this->getZonesList();
        $memebrship = $this->getMembership();

        $taxes = array();
        foreach ($this->getTaxes() as $tax) {
            foreach ($tax->getFilteredRates($zones, $memebrship, $product->getClasses()) as $rate) {
                $taxes[$tax->getName()] = $rate->calculateProduct($product);
                break;
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
        $list = array();

        foreach (\XLite\Core\Database::getRepo('XLite\Module\CDev\SimpleTaxes\Model\Tax')->findActive() as $tax) {
            if ($tax->getIncluded()) {
                $list[] = $tax;
            }
        }

        return $list;
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

        $zones = $address ? \XLite\Core\Database::getRepo()->findApplicableZones($address) : array();

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
        return \XLite\Core\Auth::getInstance()->getProfile() ?: $this->getDefaultProfile();
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
