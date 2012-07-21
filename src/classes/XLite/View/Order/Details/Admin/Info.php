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
 * @since     1.0.24
 */

namespace XLite\View\Order\Details\Admin;

/**
 * Orer info 
 * 
 * @see   ____class_see____
 * @since 1.0.24
 */
class Info extends \XLite\View\AView
{
    /**
     * Shipping modifier (cache)
     *
     * @var   \XLite\Model\Order\Modifier
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $shippingModifier;

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'order/page/info.css';

        return $list;
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
        return 'order/page/info.tpl';
    }

    // {{{ Content helpers

    /**
     * Get shipping modifier
     *
     * @return \XLite\Model\Order\Modifier
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getShippingModifier()
    {
        if (!isset($this->shippingModifier)) {
            $this->shippingModifier = $this->getOrder()
                ->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING');
        }

        return $this->shippingModifier;
    }

    /**
     * Get order formatted creation date 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getOrderDate()
    {
        return $this->formatTime($this->getOrder()->getDate());
    }

    /**
     * Get profile URL 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getProfileURL()
    {
        return \XLite\Core\Converter::buildURL(
            'profile',
            '',
            array('profile_id' => $this->getOrder()->getOrigProfile()->getProfileId())
        );
    }

    /**
     * Get profile name 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getProfileName()
    {
        $address = $this->getOrder()->getProfile()->getBillingAddress() ?: $this->getOrder()->getProfile()->getShippingAddress();

        if (!$address) {
            $this->getOrder()->getProfile()->getAddresses()->first();
        }

        return $address ? $address->getName() : $this->getOrder()->getProfile()->getLogin();
    }

    /**
     * Check - has profile separate modification page or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function hasProfilePage()
    {
        return $this->getOrder()->getOrigProfile()
            && $this->getOrder()->getOrigProfile()->getProfileId() != $this->getOrder()->getProfile()->getProfileId();
    }

    /**
     * Get order formatted total
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getOrderTotal()
    {
        return $this->formatPrice($this->getOrder()->getTotal(), $this->getOrder()->getCurrency());
    }

    /**
     * Get shipping cost 
     * 
     * @return float
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getShippingCost()
    {
        return $this->getOrder()->getSurchargeSumByType(\XLite\Model\Base\Surcharge::TYPE_SHIPPING);
    }

    /**
     * Get membership 
     * 
     * @return \XLite\Model\Membership
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getMembership()
    {
        return $this->getOrder()->getOrigProfile()
            ? $this->getOrder()->getOrigProfile()->getMembership()
            : null;
    }

    // }}}

    // {{{ Items content helpers

    /**
     * Get columns span
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function getColumnsSpan()
    {
        return 4 + count($this->getOrder()->getItemsExcludeSurcharges());
    }

    /**
     * Get item fescription block columns count
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getItemDescriptionCount()
    {
        return 3;
    }

    /**
     * Get surcharge totals
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getSurchargeTotals()
    {
        return $this->getOrder()->getSurchargeTotals();
    }

    /**
     * Get surcharge class name
     *
     * @param string $type      Surcharge type
     * @param array  $surcharge Surcharge
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
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
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function formatSurcharge(array $surcharge)
    {
        return $this->formatPrice(abs($surcharge['cost']), $this->getOrder()->getCurrency());
    }

    /**
     * Check - customer notes block is visible or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function isCustomerNotesVisible()
    {
        return (bool)$this->getOrder()->getNotes();
    }

    /**
     * Get list of actual payment sums (authorized, captured, refunded)
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function getPaymentTransactionSums()
    {
        return $this->getOrder()->getPaymentTransactionSums();
    }

    /**
     * Returns true if order has payment transaction sums greater than zero
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function hasPaymentTransactionSums()
    {
        return 0 < array_sum($this->getPaymentTransactionSums());
    }

    // }}}

}

