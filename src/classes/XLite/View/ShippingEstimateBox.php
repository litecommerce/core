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

namespace XLite\View;

/**
 * Shipping estimate box 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="cart.panel.box", weight="10")
 */
class ShippingEstimateBox extends \XLite\View\AView
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
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'shopping_cart/parts/box.estimator.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean 
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getModifier()
            && $this->getModifier()->canApply();
    }

    /**
     * Check - shipping estimate and method selected or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isShippingEstimate()
    {
        return \XLite\Model\Shipping::getInstance()->getDestinationAddress($this->getModifier()->getModifier())
            && $this->getModifier()->getMethod();
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

    /**
     * Get shipping cost 
     * 
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getShippingCost()
    {
        $cart = $this->getCart();
        $cost = $cart->getSurchargesSubtotal(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, false);

        return $this->formatPrice($cost, $cart->getCurrency());
    }

    /**
     * Get shipping estimate address
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getEstimateAddress()
    {
        $string = '';

        $address = \XLite\Model\Shipping::getInstance()->getDestinationAddress($this->getModifier()->getModifier());

        if (is_array($address)) {
            $country = \XLite\Core\Database::getRepo('XLite\Model\Country')->find($address['country']);
            if ($address['state']) {
                $state = \XLite\Core\Database::getRepo('XLite\Model\State')->find($address['state']);

            } elseif ($this->getCart()->getProfile() && $this->getCart()->getProfile()->getShippingAddress()) {
                $state = $this->getCart()->getProfile()->getShippingAddress()->getState();
            }
        }

        if (isset($country)) {
            $string = $country->getCountry();
        }

        if ($state) {
            $string .= ', ' . ($state->getCode() ?: $state->getState());
        }

        $string .= ', ' . $address['zipcode'];

        return $string;
    }

}
