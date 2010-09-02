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
    const MODIFIER_SHIPPING = 'shipping';

    protected $shippingMethod;

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
        
            $shippingMethod = $this->getShippingMethod();
            $cost = is_object($shippingMethod) ? $shippingMethod->calculate($this) : false;

            if (false === $cost) {
                $rates = $this->calculateShippingRates();

                // find the first available shipping method
                if (!is_null($rates) && count($rates) > 0) {
                    foreach ($rates as $key => $val) {
                        $shippingID = $key;
                        break;
                    }

                    $shippingMethod = new \XLite\Model\Shipping($shippingID);
                    $this->setShippingMethod($shippingMethod);
                    $cost = $shippingMethod->calculate($this);
                }
            }
        }

        $this->saveModifier(self::MODIFIER_SHIPPING, $cost);
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
        return (bool)$this->getShippingMethod();
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
            
        foreach (\XLite\Model\Shipping::getModules() as $module) {
            $data += $module->getRates($this);
        }

        uasort($data, array($this, 'getShippingRatesOrderCallback'));

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
    public function getShippingRatesOrderCallback(\XLite\Model\ShippingRate $a, \XLite\Model\ShippingRate $b)
    {
        $sa = $a->getShipping();
        $sb = $b->getShipping();

        return ($sa && $sb)
            ? strcmp($sa->get('order_by'), $sb->get('order_by'))
            : 0;
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
        return (bool)$this->getShippingId();
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
        if (!isset($this->shippingMethod) && $this->getShippingId()) {
            $this->shippingMethod = new \XLite\Model\Shipping($this->getShippingId());
        }

        return $this->shippingMethod;
    }

    /**
     * Set shipping method 
     * 
     * @param \XLite\Model\Shipping $shippingMethod Shipping method
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setShippingMethod($shippingMethod) 
    {
        if (!is_null($shippingMethod) && $shippingMethod instanceof \XLite\Model\Shipping) {
            $this->shippingMethod = $shippingMethod;
            $this->setShippingId($shippingMethod->get('shipping_id'));

        } else {
            $this->shippingMethod = false;
            $this->setShippingId(0);
        }
    }

    /**
     * Get shipping rates 
     * 
     * @return array of \XLite\Model\ShippingRate
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

        $shipping = null;
        if (0 < count($rates)) {
            $rate = array_shift($rates);
            $shipping = $rate->get('shipping');
        }

        $this->setShippingMethod($shipping);
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
