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
 * Shipping order modifier
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Shipping extends \XLite\Model\Order implements \XLite\Base\IDecorator
{
    /**
     * Modifier name definition
     */
    const MODIFIER_SHIPPING = 'shipping';

    /**
     * shippingRate 
     * 
     * @var    \XLite\Model\Shipping\Rate
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $shippingRate;

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

        $list[10] = self::MODIFIER_SHIPPING;

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
    protected function calculateShipping()
    {
        $cost = 0;

        if ($this->isShipped()) {

            $rate = $this->getSelectedRate();

            if (is_object($rate)) {
                $cost = $rate->getTotalRate();
            }
        }

        $this->saveModifier(self::MODIFIER_SHIPPING, $cost);
    }

    /**
     * getSelectedRate 
     * 
     * @return \XLite\Model\Shipping\Rate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSelectedRate()
    {
        if (
            !is_object($this->shippingRate)
            || $this->shippingRate->getMethodId() != $this->getShippingId()
        ) {

            // Get shipping rates
            $rates = $this->calculateShippingRates();

            if (!empty($rates)) {

                if (0 < intval($this->getShippingId())) {

                    foreach ($rates as $rate) {

                        if ($this->getShippingId() == $rate->getMethodId()) {
                            $this->shippingRate = $rate;
                            break;
                        }
                    }
            
                } else {
                    $this->shippingRate = array_shift($rates);
                    $this->setShippingId($this->shippingRate->getMethodId());

                    \XLite\Core\Database::getEM()->persist($this);
                    \XLite\Core\Database::getEM()->flush();
                }
            }
        }

        return $this->shippingRate;
    }

    /**
     * Check - shipping cost is visible or not
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isShippingVisible()
    {
        return true;
    }

    /**
     * Get shipping cost row name 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getShippingName()
    {
        return 'Shipping cost';
    }

    /**
     * Check - shipping cost is available or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShippingAvailable()
    {
        return is_object($this->getSelectedRate());
    }

    /**
     * Calculate shipping rates 
     * 
     * @return array of \XLite\Model\ShippingRate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateShippingRates() 
    {
        $data = array();

        if ($this->isShipped()) {

            $data = \XLite\Model\Shipping::getInstance()->getRates($this);
            
            uasort($data, array($this, 'getShippingRatesOrderCallback'));
        }

        return $data;
    }

    /**
     * Shipping rates sorting callback 
     * 
     * @param \XLite\Model\ShippingRate $a First shipping rate
     * @param \XLite\Model\ShippingRate $b Second shipping rate
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingRatesOrderCallback(\XLite\Model\Shipping\Rate $a, \XLite\Model\Shipping\Rate $b)
    {
        $result = 0;

        $sa = $a->getMethod();
        $sb = $b->getMethod();

        if (isset($sa) && isset($sb)) {

            if ($sa->getPosition() > $sb->getPosition()) {
                $result = 1;

            } elseif ($sa->getPosition() < $sb->getPosition()) {
                $result = -1;
            }
        }

        return $result;
    }

    /**
     * Get shipped items 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippedItems() 
    {
        $result = array();

        foreach ($this->getItems() as $item) {
            if ($item->isShipped()) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * Count shipped items quantity
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function countShippedItems() 
    {
        $result = 0;

        foreach ($this->getShippedItems() as $item) {
            $result += $item->getAmount();
        }

        return $result;
    }

    /**
     * Check - is shipping methopd defined or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShippingDefined()
    {
        return is_object($this->getSelectedRate());
    }

    /**
     * Get shipping method 
     * 
     * @return \XLite\Model\Shipping
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingMethod()
    {
        $result = null;

        $rate = $this->getSelectedRate();

        if (isset($rate)) {
            $result = $rate->getMethod();
        }

        return $result;
    }

    /**
     * Set shipping rate and shipping method id 
     * 
     * @param \XLite\Model\Shipping\Rate $shippingRate Shipping rate object
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setShippingRate($shippingRate) 
    {
        if (!is_null($shippingRate) && $shippingRate instanceof \XLite\Model\Shipping\Rate) {
            $this->shippingRate = $shippingRate;
            $this->setShippingId($shippingRate->getMethodId());

        } else {
            $this->shippingRate = false;
            $this->setShippingId(0);
        }
    }

    /**
     * Get shipping rates 
     * 
     * @return array of \XLite\Model\Shipping\Rate
     * @access public
     * @since  3.0.0
     */
    public function getShippingRates()
    {
        return $this->calculateShippingRates();
    }

    /**
     * Check - shipping is available for this order or not
     * 
     * @return boolean
     * @access public
     * @since  3.0.0
     */
    public function isDeliveryAvailable()
    {
        return 0 < count($this->getShippingRates());
    }

    /**
     * Assign first shipping rate 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function assignFirstShippingRate()
    {
        $rates = $this->getShippingRates();

        $rate = null;

        if (0 < count($rates)) {
            $rate = array_shift($rates);
        }

        $this->setShippingRate($rate);
    }

    /**
     * Returns true if any of order items are shipped 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isShipped()
    {
        $result = false;

        foreach ($this->getItems() as $item) {
            if ($item->isShipped()) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Get order subtotal only for shipped items
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippedSubtotal() 
    {
        $this->calculateSubtotal();

        $subtotal = 0;

        foreach ($this->getItems() as $item) {
            if ($item->isShipped()) {
                $subtotal += $item->getTotal();
            }
        }

        return $subtotal;
    }
}
