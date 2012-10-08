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

namespace XLite\View\Checkout\Step;

/**
 * Shipping step
 *
 */
class Shipping extends \XLite\View\Checkout\Step\AStep
{
    /**
     * Modifier (cache)
     *
     * @var \XLite\Model\Order\Modifier
     */
    protected $modifier;

    /**
     * Get step name
     *
     * @return string
     */
    public function getStepName()
    {
        return 'shipping';
    }

    /**
     * Get step title
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Shipping info';
    }

    /**
     * Check - step is complete or not
     *
     * @return boolean
     */
    public function isCompleted()
    {
        return !$this->isEnabled()
            || (
                $this->isAddressCompleted()
                && $this->getCart()->getProfile()->getLogin()
                && (!$this->getModifier() || !$this->getModifier()->canApply() || $this->getModifier()->getMethod())
            );
    }

    /**
     * Check - shipping address is completed or not
     *
     * @return boolean
     */
    public function isAddressCompleted()
    {
        $profile = $this->getCart()->getProfile();

        return $profile
            && $profile->getShippingAddress()
            && $profile->getShippingAddress()->isCompleted(\XLite\Model\Address::SHIPPING);
    }

    /**
     * Check - shipping system is enabled or not
     *
     * @return boolean
     */
    public function isShippingEnabled()
    {
        return $this->getModifier() && $this->getModifier()->canApply();
    }

    /**
     * Check - shipping rates is available or not
     *
     * @return boolean
     */
    public function isShippingAvailable()
    {
        return $this->isShippingEnabled();
    }

    /**
     * Get rate markup
     *
     * @param \XLite\Model\Shipping\Rate $rate Shipping rate
     *
     * @return float
     */
    public function getTotalRate(\XLite\Model\Shipping\Rate $rate)
    {
        return $rate->getTotalRate();
    }

    /**
     * Check - display Address book button or not
     *
     * @return boolean
     */
    public function isDisplayAddressButton()
    {
        return !$this->isAnonymous()
            && $this->getCart()->getProfile()
            && 0 < count($this->getCart()->getProfile()->getAddresses());
    }

    /**
     * Check - step is enabled (true) or skipped (false)
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return parent::isEnabled() && $this->isShippingEnabled();
    }

    /**
     * Get modifier
     *
     * @return \XLite\Model\Order\Modifier
     */
    protected function getModifier()
    {
        if (!isset($this->modifier)) {
            $this->modifier = $this->getCart()->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING');
        }

        return $this->modifier;
    }
}
