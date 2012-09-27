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

namespace XLite\Logic\Order\Modifier;

/**
 * Shipping modifier
 *
 */
class Shipping extends \XLite\Logic\Order\Modifier\AShipping
{
    /**
     * Modifier unique code
     *
     * @var string
     */
    protected $code = 'SHIPPING';

    /**
     * Selected rate (cache)
     *
     * @var \XLite\Model\Shipping\Rate
     */
    protected $selectedRate;

    /**
     * Check - can apply this modifier or not
     *
     * @return boolean
     */
    public function canApply()
    {
        return parent::canApply()
            && $this->isShippable();
    }

    /**
     * Calculate
     *
     * @return void
     */
    public function calculate()
    {
        $cost = null;

        if ($this->isShippable()) {

            $rate = $this->getSelectedRate();

            if (isset($rate)) {
                $cost = $this->getOrder()->getCurrency()->roundValue($rate->getTotalRate());
            }

            $this->addOrderSurcharge($this->code, doubleval($cost), false, isset($cost));

        } else {

            foreach ($this->order->getSurcharges() as $s) {
                if ($s->getType() == $this->type && $s->getCode() == $this->code) {
                    $this->order->getSurcharges()->remove($s);
                    \XLite\Core\Database::geEM()->remove($s);
                }
            }
        }
    }

    /**
     * Check - shipping rates exists or not
     *
     * @return boolean
     */
    public function isRatesExists()
    {
        return (bool)$this->getRates();
    }

    /**
     * Get shipping rates
     * TODO: add checking if rates should be recalculated else get rates from cache
     *
     * @return array(\XLite\Model\Shipping\Rate)
     */
    public function getRates()
    {
        return $this->calculateRates();
    }

    /**
     * Returns true if any of order items are shipped
     *
     * @return boolean
     */
    protected function isShippable()
    {
        $result = false;

        foreach ($this->getItems() as $item) {
            if ($item->isShippable()) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    // {{{ Shipping rates

    /**
     * Calculate shipping rates
     *
     * @return array(\XLite\Model\Shipping\Rate)
     */
    protected function calculateRates()
    {
        $rates = array();

        if ($this->isShippable()) {

            $rates = \XLite\Model\Shipping::getInstance()->getRates($this);

            uasort($rates, array($this, 'compareRates'));
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
     */
    protected function compareRates(\XLite\Model\Shipping\Rate $a, \XLite\Model\Shipping\Rate $b)
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

    // }}}

    // {{{ Current shipping method and rate

    /**
     * Get selected shipping rate
     *
     * @return \XLite\Model\Shipping\Rate
     */
    public function getSelectedRate()
    {
        if (
            !isset($this->selectedRate)
            || $this->selectedRate->getMethodId() != $this->order->getShippingId()
        ) {
            // Get shipping rates
            $rates = $this->getRates();

            $selectedRate = null;

            if (!empty($rates)) {

                if (
                    !$this->order->getShippingId()
                    && $this->order->getProfile()
                    && $this->order->getProfile()->getLastShippingId()
                ) {

                    // Remember last shipping id
                    $this->order->setShippingId($this->order->getProfile()->getLastShippingId());
                }

                if (0 < intval($this->order->getShippingId())) {
                    // Set selected rate from the rates list if shipping_id is already assigned

                    foreach ($rates as $rate) {

                        if ($this->order->getShippingId() == $rate->getMethodId()) {
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
     * @param \XLite\Model\Shipping\Rate $rate Shipping rate object OPTIONAL
     *
     * @return void
     */
    public function setSelectedRate(\XLite\Model\Shipping\Rate $rate = null)
    {
        $newShippingId = $this->order->getShippingId();

        $this->selectedRate = $rate;
        $newShippingId = $rate ? $rate->getMethodId() : 0;

        if ($this->order->getShippingId() != $newShippingId) {

            $this->order->setShippingId($newShippingId);
            $this->order->setShippingMethodName($rate ? $rate->getMethod()->getName() : null);

            \XLite\Core\Database::getEM()->flush();
        }
    }

    /**
     * Get shipping method
     *
     * @return \XLite\Model\Shipping\Method
     */
    public function getMethod()
    {
        $result = null;

        $rate = $this->getSelectedRate();

        if (isset($rate)) {
            $result = $rate->getMethod();
        }

        return $result;
    }

    /**
     * Get shipping method name
     *
     * @return string|void
     */
    public function getActualName()
    {
        $name = null;

        if ($this->getShippingMethod()) {
            $name = $this->getShippingMethod()->getName();

        } elseif ($this->order->getShippingMethodName()) {
            $name = $this->order->getShippingMethodName();
        }

        return $name;
    }

    // }}}

    // {{{ Shipping calculation data

    /**
     * Get shipped items
     *
     * @return array
     */
    public function getItems()
    {
        $result = array();

        foreach ($this->order->getItems() as $item) {
            if ($item->isShippable()) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * Get order weight
     *
     * @return float
     */
    public function getWeight()
    {
        $weight = 0;

        foreach ($this->getItems() as $item) {
            $weight += $item->getWeight();
        }

        return $weight;
    }

    /**
     * Count shipped items quantity
     *
     * @return integer
     */
    public function countItems()
    {
        $result = 0;

        foreach ($this->getItems() as $item) {
            $result += $item->getAmount();
        }

        return $result;
    }

    /**
     * Get order subtotal only for shipped items
     *
     * @return float
     */
    public function getSubtotal()
    {
        $subtotal = 0;

        foreach ($this->getItems() as $item) {
            $subtotal += $item->getTotal();
        }

        return $subtotal;
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

        $info->name = \XLite\Core\Translation::lbl('Shipping cost');
        $info->notAvailableReason = \XLite\Core\Translation::lbl('Shipping address is not defined');

        return $info;
    }

    // }}}
}
