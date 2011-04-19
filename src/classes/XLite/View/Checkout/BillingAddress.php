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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\Checkout;

/**
 * Billing address block 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class BillingAddress extends \XLite\View\AView
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
     * Get same-as-shipping address 
     * 
     * @return \XLite\Model\Address
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSameAddress()
    {
        $address = null;

        if ($this->getCart()->getProfile()) {
            $address = $this->isSameAddress()
                ? $this->getCart()->getProfile()->getShippingAddress()
                : $this->getCart()->getProfile()->getBillingAddress();
        }

        return $address;
    }


    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'checkout/steps/payment/address.tpl';
    }

    /**
     * Check - same address box is visible or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isSameAddressVisible()
    {
        return $this->getModifier() && $this->getModifier()->canApply();
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
