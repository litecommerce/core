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
 * Payment actions unit widget (button capture or refund or void etc)
 * 
 *
 * @ListChild (list="order.details.payment_actions", zone="admin")
 */
class PaymentActionsUnit extends \XLite\View\AView
{
    /**
     *  Widget parameter names
     */
    const PARAM_TRANSACTION = 'transaction';
    const PARAM_UNIT        = 'unit';


    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'order/order/payment_actions/unit.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_TRANSACTION => new \XLite\Model\WidgetParam\Object('Transaction', null, false, 'XLite\Model\Payment\Transaction'),
            self::PARAM_UNIT        => new \XLite\Model\WidgetParam\String('Unit', '', false),
        );
    }

    protected function getCSSClass()
    {
        return 'action payment-action-button';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getParam(self::PARAM_TRANSACTION)
            && $this->isTransactionUnitAllowed($this->getParam(self::PARAM_TRANSACTION), $this->getParam(self::PARAM_UNIT));
    }

    /**
     * Return true if requested unit is allowed for the transaction
     * 
     * @return boolean
     */
    protected function isTransactionUnitAllowed($transaction, $unit)
    {
        return $transaction->getPaymentMethod()->getProcessor()->isTransactionAllowed($transaction, $unit);
    }

    /**
     * Get unit name (for button naming)
     * 
     * @return string
     */
    protected function getUnitName()
    {
        return ucfirst($this->getParam(self::PARAM_UNIT));
    }

    /**
     * Get action URL
     * 
     * @return string
     */
    protected function getActionURL()
    {
        return \XLite\Core\Converter::buildURL(
            'order',
            $this->getParam(self::PARAM_UNIT),
            array(
                'order_id' => $this->getParam(self::PARAM_TRANSACTION)->getOrder()->getOrderId(),
                'trn_id'   => $this->getParam(self::PARAM_TRANSACTION)->getTransactionId()
            )
        );
    }
}

