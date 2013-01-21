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

namespace XLite\View\Checkout;

/**
 * Billing address block
 *
 */
class BillingAddress extends \XLite\View\Checkout\AAddressBlock
{
    /**
     * Modifier (cache)
     *
     * @var \XLite\Model\Order\Modifier
     */
    protected $modifier;


    /**
     * Check - shipping and billing addrsses are same or not
     *
     * @return boolean
     */
    protected function isSameAddress()
    {
        return $this->getCart()->getProfile() && $this->getCart()->getProfile()->isEqualAddress();
    }

    /**
     * Check - billing address is editable or not
     *
     * @return void
     */
    protected function isEditableAddress()
    {
        return !$this->isSameAddress() || !$this->isSameAddressVisible();
    }

    /**
     * Get same-as-shipping address
     *
     * @return \XLite\Model\Address
     */
    protected function getSameAddress()
    {
        $address = null;
        $profile = $this->getCart()->getProfile();

        if ($profile) {
            $address = $profile->getBillingAddress() ?: $profile->getShippingAddress();
        }

        return $address;
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'checkout/steps/payment/address.tpl';
    }

    /**
     * Check - same address box is visible or not
     *
     * @return boolean
     */
    protected function isSameAddressVisible()
    {
        return $this->getModifier() && $this->getModifier()->canApply();
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

    /**
     * Get address info model
     *
     * @return \XLite\Model\Address
     */
    protected function getAddressInfo()
    {
        return $this->getSameAddress();
    }
}
