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
    protected $selectedRate;

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

        if (!$this->isShippingEnabled()) {
            $this->unsetModifier(self::MODIFIER_SHIPPING);

        } else {
            
            if ($this->isShipped()) {

                $rate = $this->getSelectedRate();

                if (is_object($rate)) {
                    $cost = $rate->getTotalRate();
                }
            }

            $this->saveModifier(self::MODIFIER_SHIPPING, $cost);
        }
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
     * Calculate shipping rates 
     * 
     * @return array of \XLite\Model\Shipping\Rate
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateShippingRates() 
    {
        $rates = array();

        if ($this->isShipped()) {

            $rates = \XLite\Model\Shipping::getInstance()->getRates($this);
            
            uasort($rates, array($this, 'compareShippingRates'));
        }

        return $rates;
    }

    /**
     * Shipping rates sorting callback 
     * 
     * @param \XLite\Model\Shipping\Rate $a First shipping rate
     * @param \XLite\Model\Shipping\Rate $b Second shipping rate
     *  
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function compareShippingRates(\XLite\Model\Shipping\Rate $a, \XLite\Model\Shipping\Rate $b)
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
     * Get shipping rates 
     * TODO: add checking if rates should be recalculated else get rates from cache
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
     * Get selected shipping rate 
     * 
     * @return \XLite\Model\Shipping\Rate
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSelectedRate()
    {
        if (
            !isset($this->selectedRate)
            || $this->selectedRate->getMethodId() != $this->getShippingId()
        ) {
            // Get shipping rates
            $rates = $this->getShippingRates();
            
            $selectedRate = null;

            if (!empty($rates)) {

                if (!$this->getShippingId() && $this->getProfile() && $this->getProfile()->getLastShippingId()) {

                    // Remember last shipping id
                    $this->setShippingId($this->getProfile()->getLastShippingId());
                }

                if (0 < intval($this->getShippingId())) {
                    // Set selected rate from the rates list if shipping_id is already assigned

                    foreach ($rates as $rate) {

                        if ($this->getShippingId() == $rate->getMethodId()) {
                            $selectedRate = $rate;
                            break;
                        }
                    }
                }
            }

            $this->setSelectedRate($selectedRate);
        }

        return $this->selectedRate;
    }

    /**
     * Set shipping rate and shipping method id 
     * 
     * @param \XLite\Model\Shipping\Rate $rate Shipping rate object
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setSelectedRate($rate) 
    {
        $newShippingId = $this->getShippingId();

        if ($rate instanceof \XLite\Model\Shipping\Rate) {
            // Set up selected rate and shipping_id
            $this->selectedRate = $rate;
            $newShippingId = $rate->getMethodId();

        } else {
            // Reset selected rate and shipping_id
            $this->selectedRate = null;
            $newShippingId = 0;
        }

        if ($this->getShippingId() != $newShippingId) {

            $this->setShippingId($newShippingId);

            \XLite\Core\Database::getEM()->persist($this);
            \XLite\Core\Database::getEM()->flush();
        }
    }

    /**
     * Service method: check if shipping is visible or not at the moment of saveModifier() call
     * 
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShippingVisible()
    {
        return $this->isShippingEnabled();
    }

    /**
     * Service method: check if shipping rate selected and should be displayed
     * Method is used by isAvailable() (\XLite\Model\OrderModifier class)
     * 
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShippingAvailable()
    {
        return $this->isDeliveryAvailable();
    }

    /**
     * Check if shipping enabled and available for calculation
     * 
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShippingEnabled()
    {
        return 'Y' == \XLite\Base::getInstance()->config->Shipping->shipping_enabled;
    }

    /**
     * Check if shipping rate has been selected
     * 
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShippingSelected()
    {
        return is_object($this->getSelectedRate());
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
        return $this->isShippingVisible() && $this->isShipped() && 0 < count($this->getShippingRates());
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
     * Get order weight 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWeight() 
    {
        $weight = 0;

        foreach ($this->getShippedItems() as $item) {
            $weight += $item->getWeight();
        }

        return $weight;
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
     * Get shipping method
     * 
     * @return \XLite\Model\Shipping\Method
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
     * Returns true if any of order items are shipped 
     * 
     * @return boolean 
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
