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

namespace XLite\View\Order\Details\Admin;

/**
 * Order info
 *
 */
class Info extends \XLite\View\AView
{
    /**
     * Shipping modifier (cache)
     *
     * @var \XLite\Model\Order\Modifier
     */
    protected $shippingModifier;

    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'order/page/info.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
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
     */
    protected function getOrderDate()
    {
        return $this->formatTime($this->getOrder()->getDate());
    }

    /**
     * Get profile URL
     *
     * @return string
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
     */
    protected function getOrderTotal()
    {
        return $this->formatPrice($this->getOrder()->getTotal(), $this->getOrder()->getCurrency());
    }

    /**
     * Get shipping cost
     *
     * @return float
     */
    protected function getShippingCost()
    {
        return $this->getOrder()->getSurchargeSumByType(\XLite\Model\Base\Surcharge::TYPE_SHIPPING);
    }

    /**
     * Get membership
     *
     * @return \XLite\Model\Membership
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
     */
    protected function getColumnsSpan()
    {
        return 4 + count($this->getOrder()->getItemsExcludeSurcharges());
    }

    /**
     * Get item fescription block columns count
     *
     * @return integer
     */
    protected function getItemDescriptionCount()
    {
        return 3;
    }

    /**
     * Get surcharge totals
     *
     * @return array
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
        return $this->formatPrice(abs($surcharge['cost']), $this->getOrder()->getCurrency());
    }

    /**
     * Check - customer notes block is visible or not
     *
     * @return boolean
     */
    protected function isCustomerNotesVisible()
    {
        return (bool)$this->getOrder()->getNotes();
    }

    /**
     * Get list of actual payment sums (authorized, captured, refunded)
     *
     * @return array
     */
    protected function getPaymentTransactionSums()
    {
        return $this->getOrder()->getPaymentTransactionSums();
    }

    /**
     * Returns true if order has payment transaction sums greater than zero
     *
     * @return boolean
     */
    protected function hasPaymentTransactionSums()
    {
        return 0 < array_sum($this->getPaymentTransactionSums());
    }

    // }}}
    
}

