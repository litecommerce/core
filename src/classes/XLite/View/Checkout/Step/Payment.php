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
 * Payment checkout step
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Payment extends \XLite\View\Checkout\Step\AStep
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
        return 'payment';
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
        return static::t('Payment info');
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
        return $this->getCart()->getProfile()
            && $this->getCart()->getProfile()->getBillingAddress()
            && $this->getCart()->getProfile()->getBillingAddress()->isCompleted(\XLite\Model\Address::BILLING)
            && $this->getCart()->getPaymentMethod();
    }

    /**
     * Check main button visibility (inactive mode)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isInactiveButtonVisible()
    {
        return $this->isCompleted()
            && !$this->getStepsCollector()->isFutureStep($this);
    }

    /**
     * Return list of available payment methods
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPaymentMethods()
    {
        return $this->getCart()->getPaymentMethods();
    }

    /**
     * Check - billing address is completed or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isAddressCompleted()
    {
        $profile = $this->getCart()->getProfile();

        return $profile
            && $profile->getBillingAddress()
            && $profile->getBillingAddress()
                ->isCompleted(\XLite\Model\Address::BILLING);
    }

    /**
     * Check - payment method is selected or not
     *
     * @param \XLite\Model\Payment\Method $method Payment methods
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isPaymentSelected(\XLite\Model\Payment\Method $method)
    {
        return $this->getCart()->getPaymentMethod() == $method;
    }

    /**
     * Check - shipping and billing addrsses are same or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isSameAddress()
    {
        return $this->getCart()->getProfile() && $this->getCart()->getProfile()->isEqualAddress();
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
     * Prepare payment method icon 
     * 
     * @param string $icon Icon local path
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function preparePaymentMethodIcon($icon)
    {
        return \XLite\Core\Layout::getInstance()->getResourceWebPath($icon, \XLite\Core\Layout::WEB_PATH_OUTPUT_URL);
    }
}
