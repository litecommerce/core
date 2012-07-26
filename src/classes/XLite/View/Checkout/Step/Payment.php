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
 * Payment checkout step
 *
 */
class Payment extends \XLite\View\Checkout\Step\AStep
{
    /**
     * Modifier (cache)
     *
     * @var \XLite\Model\Order\Modifier
     */
    protected $modifier;

    /**
     * Flag if the cart has been already payed (cache)
     *
     * @var boolean
     */
    protected $isPayedCart;


    /**
     * Get step name
     *
     * @return string
     */
    public function getStepName()
    {
        return 'payment';
    }

    /**
     * Get step title
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Payment info';
    }

    /**
     * Check if step is completed
     *
     * @return boolean
     */
    public function isCompleted()
    {
        return $this->getCart()->getProfile()
                && $this->getCart()->getProfile()->getBillingAddress()
                && $this->getCart()->getProfile()->getBillingAddress()->isCompleted(\XLite\Model\Address::BILLING)
                && ($this->getCart()->getPaymentMethod() || $this->isPayedCart());
    }

    /**
     * Check main button visibility (inactive mode)
     *
     * @return boolean
     */
    public function isInactiveButtonVisible()
    {
        return $this->isCompleted() && !$this->getStepsCollector()->isFutureStep($this);
    }

    /**
     * Return list of available payment methods
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        return $this->getCart()->getPaymentMethods();
    }

    /**
     * Check - billing address is completed or not
     *
     * @return boolean
     */
    public function isAddressCompleted()
    {
        $profile = $this->getCart()->getProfile();

        return $profile
            && $profile->getBillingAddress()
            && $profile->getBillingAddress()->isCompleted(\XLite\Model\Address::BILLING);
    }

    /**
     * Check - payment method is selected or not
     *
     * @param \XLite\Model\Payment\Method $method Payment methods
     *
     * @return boolean
     */
    public function isPaymentSelected(\XLite\Model\Payment\Method $method)
    {
        return $this->getCart()->getPaymentMethod() == $method;
    }

    /**
     * Check - shipping and billing addrsses are same or not
     *
     * @return boolean
     */
    public function isSameAddress()
    {
        return $this->getCart()->getProfile() && $this->getCart()->getProfile()->isEqualAddress();
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
     * Prepare payment method icon
     *
     * @param string $icon Icon local path
     *
     * @return string
     */
    protected function preparePaymentMethodIcon($icon)
    {
        return \XLite\Core\Layout::getInstance()->getResourceWebPath($icon, \XLite\Core\Layout::WEB_PATH_OUTPUT_URL);
    }

    /**
     * Return flag if the cart has been already payed
     *
     * @return boolean
     */
    protected function isPayedCart()
    {
        if (!isset($this->isPayedCart)) {

            $this->isPayedCart = $this->getCart()->isPayed();
        }

        return $this->isPayedCart;
    }
}
