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

namespace XLite\View\Checkout\Step;

/**
 * Shipping step
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Shipping extends \XLite\View\Checkout\Step\AStep
{
    /**
     * Modifier (cache)
     *
     * @var   \XLite\Model\Order\Modifier
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $modifier;

    /**
     * Get step name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getStepName()
    {
        return 'shipping';
    }

    /**
     * Get step title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return static::t('Shipping info');
    }

    /**
     * Check - step is complete or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isCompleted()
    {
        return $this->isDisabled()
            || (
                $this->getCart()->getProfile()
                && $this->getCart()->getProfile()->getShippingAddress()
                && $this->getCart()->getProfile()->getShippingAddress()->isCompleted(\XLite\Model\Address::SHIPPING)
                && (!$this->getModifier() || !$this->getModifier()->canApply() || $this->getModifier()->getMethod())
            );
    }

    /**
     * Check - shipping address is completed or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isAddressCompleted()
    {
        $profile = $this->getCart()->getProfile();

        return $profile
            && $profile->getShippingAddress()
            && $profile->getShippingAddress()
                ->isCompleted(\XLite\Model\Address::SHIPPING);
    }

    /**
     * Check - shipping system is enabled or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isShippingEnabled()
    {
        return $this->getModifier() && $this->getModifier()->canApply();
    }

    /**
     * Check - shipping rates is available or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMarkup(\XLite\Model\Shipping\Rate $rate)
    {
        return $rate->getMarkup()->getMarkupValue();
    }

    /**
     * Check - display Address book button or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isDisplayAddressButton()
    {
        return !$this->isAnonymous()
            && $this->getCart()->getProfile()
            && 0 < count($this->getCart()->getProfile()->getAddresses());
    }

    /**
     * Check - step is disabled or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isDisabled()
    {
        return parent::isDisabled()
            || !$this->isShippingEnabled();
    }

    /**
     * Get modifier
     *
     * @return \XLite\Model\Order\Modifier
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModifier()
    {
        if (!isset($this->modifier)) {
            $this->modifier = $this->getCart()->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING');
        }

        return $this->modifier;
    }

}
