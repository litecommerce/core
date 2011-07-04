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
 * @since     1.0.1
 */

namespace XLite\Module\CDev\PaypalWPS\Model\Payment\Processor;

/**
 * Paypal Website Payments Standard payment processor
 *
 * @see   ____class_see____
 * @since 1.0.1
 */
class PaypalWPS extends \XLite\Model\Payment\Base\WebBased
{
    /**
     * Mode value for testing
     */
    const TEST_MODE = 'test';

    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function getSettingsWidget()
    {
        return 'modules/CDev/PaypalWPS/config.tpl';
    }

    /**
     * Process callback
     *
     * @param \XLite\Model\Payment\Transaction $transaction Callback-owner transaction
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function processCallback(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processCallback($transaction);

        $request = \XLite\Core\Request::getInstance();

        if ($request->isPost() && isset($request->status)) {
            $message = isset($this->statuses[$request->status]) ? $this->statuses[$request->status] : 'Failed';

            $this->saveDataFromRequest();

            switch ($request->status) {
                case 0:
                    $status = $transaction::STATUS_PENDING;
                    break;

                case 2:
                    $status = $transaction::STATUS_SUCCESS;
                    break;

                default:
                    $status = $transaction::STATUS_FAILED;
            }

            $this->transaction->setStatus($status);
        }
    }

    /**
     * Process return
     *
     * @param \XLite\Model\Payment\Transaction $transaction Return-owner transaction
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function processReturn(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processReturn($transaction);

        if (\XLite\Core\Request::getInstance()->cancel) {
            $this->setDetail(
                'cancel',
                'Payment transaction is cancelled'
            );
            $this->transaction->setStatus($transaction::STATUS_FAILED);

        } elseif ($transaction::STATUS_INPROGRESS == $this->transaction->getStatus()) {
            $this->transaction->setStatus($transaction::STATUS_PENDING);
        }
   }

    /**
     * Check - payment method is configured or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured($method)
            && $method->getSetting('account');
    }


    /**
     * Get redirect form URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function getFormURL()
    {
        return $this->isTestMode()
            ? 'https://www.sandbox.paypal.com/cgi-bin/webscr'
            : 'https://www.paypal.com/cgi-bin/webscr';
    }

    /**
     * Return TRUE if the test mode is ON
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function isTestMode()
    {
        return \XLite\View\FormField\Select\TestLiveMode::TEST === $this->getSetting('mode');
    }


    /**
     * Return ITEM NAME for request
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function getItemName()
    {
        return $this->getSetting('innerItemName') . '(Order #' . $this->getOrder()->getOrderId() . ')';
    }

    /**
     * Get redirect form fields list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function getFormFields()
    {
        $orderId = $this->getOrder()->getOrderId();

        $fields = array(
            'charset'       => 'UTF-8',
            'cmd'           => '_ext-enter',
            'custom'        => $orderId,
            'invoice'       => $orderId,
            'redirect_cmd'  => '_xclick',
            'item_name'     => $this->getItemName(),
            'rm'            => '2',
            'email'         => $this->getProfile()->getLogin(),
            'first_name'    => $this->getProfile()->getBillingAddress()->getFirstname(),
            'last_name'     => $this->getProfile()->getBillingAddress()->getLastname(),
            'business'      => $this->getSetting('account'),
            'amount'        => $this->transaction->getValue(),
            'tax_cart'      => 0,
            'shipping'      => 0,
            'handling'      => 0,
            'weight_cart'   => 0,
            'currency_code' => $this->getOrder()->getCurrency()->getCode(),

            'return'        => $this->getReturnURL(null, true),
            'cancel_return' => $this->getReturnURL(null, true, true),
            'shopping_url'  => $this->getReturnURL(null, true, true),
            'notify_url'    => $this->getCallbackURL(null, true),

            'country'       => $this->getProfile()->getBillingAddress()->getCountry()
                ? $this->getProfile()->getBillingAddress()->getCountry()->getCode()
                : '',

            'state'         => $this->getProfile()->getShippingAddress()->getState()->getState(),
            'address1'      => $this->getProfile()->getShippingAddress()->getStreet(),
            'address2'      => 'n/a',
            'city'          => $this->getProfile()->getBillingAddress()->getCity(),
            'zip'           => $this->getProfile()->getBillingAddress()->getZipcode(),
            'upload'        => 1,
            'bn'            => 'LiteCommerce',

        );

        if ('Y' === $this->getSetting('address_override')) {
            $fields['address_override'] = 1;
        }

        $fields = array_merge($fields, $this->getPhone());

        return $fields;
    }

    /**
     * Return Phone structure. specific for Paypal
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function getPhone()
    {
        $result = array();
        $phone = $this->getProfile()->getBillingAddress()->getPhone();

        if ($phone) {
            if (
                $this->getProfile()->getBillingAddress()->getCountry()
                && 'US' == $this->getProfile()->getBillingAddress()->getCountry()->getCode()
            ) {
                $result = array(
                    'night_phone_a' => substr($phone, -10, -7),
                    'night_phone_b' => substr($phone, -7, -4),
                    'night_phone_c' => substr($phone, -4),
                );
            } else {
                $result['night_phone_b'] = substr($phone, -10);
            }
        }

        return $result;
    }

    /**
     * Define saved into transaction data schema
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function defineSavedData()
    {
        return array(
            'transID'        => 'Transaction id',
            'authCode'       => 'Auth. code',
            'decline_reason' => 'Decline reason',
            'errorcode'      => 'Error code',
            'avs_result'     => 'AVS result',
            'cvv2_result'    => 'CVV2 result',
            'max_score'      => 'MaxMind score',
        );
    }

    /**
     * Log redirect form
     *
     * @param array $list Form fields list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function logRedirect(array $list)
    {
        $list = $this->maskCell($list, 'account');

        parent::logRedirect($list);
    }
}
