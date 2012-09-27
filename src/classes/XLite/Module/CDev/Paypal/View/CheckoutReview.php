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

namespace XLite\Module\CDev\Paypal\View;

/**
 * Extend Checkout\Step\Review widget
 *
 */
class CheckoutReview extends \XLite\View\Checkout\Step\Review implements \XLite\Base\IDecorator
{
    /**
     * Get Place button title
     * 
     * @return string
     */
    public function getPlaceTitle()
    {
        $label = parent::getPlaceTitle();

        if ($this->isNeedReplaceLabel()) {

            $label = static::t(
                'Proceed to Payment X',
                array(
                    'total' => $this->formatPrice($this->getCart()->getTotal(), $this->getCart()->getCurrency()),
                )
            );
        }

        return $label;
    }

    /**
     * Return true if Express Checkout shortcut is selected by customer
     * 
     * @return boolean
     */
    protected function isNeedReplaceLabel()
    {
        $cart = $this->getCart();

        return isset($cart)
            && 0 < $this->getCart()->getTotal()
            && $cart->getPaymentMethod()
            && $cart->isExpressCheckout($cart->getPaymentMethod())
            && \XLite\Module\CDev\Paypal\Model\Payment\Processor\ExpressCheckout::EC_TYPE_SHORTCUT
                == \XLite\Core\Session::getInstance()->ec_type;
    }
}
