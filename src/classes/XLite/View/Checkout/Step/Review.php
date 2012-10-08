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
 * Review checkout step
 *
 */
class Review extends \XLite\View\Checkout\Step\AStep
{
    /**
     * Get step name
     *
     * @return string
     */
    public function getStepName()
    {
        return 'review';
    }

    /**
     * Get step title
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Order review';
    }

    /**
     * Check - step is complete or not
     *
     * @return boolean
     */
    public function isCompleted()
    {
        return false;
    }

    /**
     * Get Terms and Conditions page URL
     *
     * @return string
     */
    public function getTermsURL()
    {
        return \XLite\Core\Config::getInstance()->Company->terms_url;
    }

    /**
     * Get Place button title
     *
     * @return string
     */
    public function getPlaceTitle()
    {
        return static::t(
            $this->isNeedReplaceLabel() ? 'Proceed to payment X' : 'Place order X',
            array(
                'total' => $this->formatPrice($this->getCart()->getTotal(), $this->getCart()->getCurrency()),
            )
        );
    }

    /**
     * Return true if customer selected non-offline payment method
     * 
     * @return boolean
     */
    protected function isNeedReplaceLabel()
    {
        $cart = $this->getCart();

        return isset($cart)
            && 0 < $cart->getTotal()
            && $cart->getPaymentMethod()
            && 'O' != $cart->getPaymentMethod()->getType();
    }

    /**
     * Get payment processor
     *
     * @return \XLite\Model\Payment\Base\Processor
     */
    protected function getProcessor()
    {
        return $this->getCart()->getPaymentMethod()
            ? $this->getCart()->getPaymentMethod()->getProcessor()
            : null;
    }

    /**
     * Get payment template
     *
     * @return string|void
     */
    protected function getPaymentTemplate()
    {
        $processor = $this->getProcessor();

        return $processor ? $processor->getInputTemplate() : null;
    }

    // {{{ Surcharges

    /**
     * Get surcharge totals
     *
     * @return array
     */
    protected function getSurchargeTotals()
    {
        return $this->getCart()->getSurchargeTotals();
    }

    /**
     * Get surcharge class name
     *
     * @param string $type      Surcharge type
     * @param array  $surcharge Surcharge
     *
     * @return string
     */
    protected function getSurchargeClassName($type, array $surcharge)
    {
        return 'order-modifier '
            . $type . '-modifier '
            . strtolower($surcharge['code']) . '-code-modifier';
    }

    /**
     * Format surcharge value
     *
     * @param array $surcharge Surcharge
     *
     * @return string
     */
    protected function formatSurcharge(array $surcharge)
    {
        return abs($surcharge['cost']);
    }

    /**
     * Get exclude surcharges by type
     *
     * @param string $type Surcharge type
     *
     * @return array
     */
    protected function getExcludeSurchargesByType($type)
    {
        return $this->getCart()->getExcludeSurchargesByType($type);
    }

    // }}}
}
