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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace XLite\Module\CDev\Paypal\Model\Payment\Processor;

/**
 * Payflow Link payment processor
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class PayflowLink extends \XLite\Module\CDev\Paypal\Model\Payment\Processor\Iframe
{
    /**
     * Get allowed transactions list
     *
     * @return string Status code
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAllowedTransactions()
    {
        return array(
            TRAN_TYPE_AUTH,
            TRAN_TYPE_SALE,
            TRAN_TYPE_CAPTURE,
            TRAN_TYPE_VOID,
            TRAN_TYPE_REFUND,
            TRAN_TYPE_GET_INFO,
        );
    }

    /**
     * Get payment method row checkout template
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCheckoutTemplate(\XLite\Model\Payment\Method $method)
    {
        return 'modules/CDev/Paypal/method.tpl';
    }

    public function getAllowedMerchantCountries()
    {
        return array('US', 'CA');
    }

    /**
     * Process return
     *
     * @param \XLite\Model\Payment\Transaction $transaction Return-owner transaction
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function processReturn(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processReturn($transaction);

        \XLite\Module\CDev\Paypal\Main::addLog(
            'processReturn',
            \XLite\Core\Request::getInstance()->getData()
        );

        if (\XLite\Core\Request::getInstance()->cancel) {
            $this->setDetail(
                'status',
                'Payment transaction is cancelled',
                'Status'
            );
            $this->transaction->setNote('Payment transaction is cancelled');
            $this->transaction->setStatus($transaction::STATUS_FAILED);

        }
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

        if (!$request->isPost()) {
            // Callback request must be POST
            $this->markCallbackRequestAsInvalid(static::t('Request type must be POST'));

        } elseif (!isset($request->RESULT)) {
            // RESULT parameter must be presented in all callback requests
            $this->markCallbackRequestAsInvalid(static::t('\'RESULT\' argument not found'));

        } else {

            $this->setDetail(
                'status',
                isset($request->RESPMSG) ? $request->RESPMSG : 'Unknown',
                'Status'
            );

            $this->saveDataFromRequest();

            if ('0' === $request->RESULT) {
                // Transaction successful if RESULT == '0'
                $status = $transaction::STATUS_SUCCESS;

            } else {
                $status = $transaction::STATUS_FAILED;
            }

            // Amount checking
            if (isset($request->AMT) && !$this->checkTotal($request->AMT)) {
                $status = $transaction::STATUS_FAILED;
            }

            \XLite\Module\CDev\Paypal\Main::addLog(
                'processCallback',
                array(
                    'request' => $request,
                    'status' => $status
                )
            );

            $this->transaction->setStatus($status);
        }
    }

    /**
     * Check - payment method is configured or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured($method)
            && !empty(\XLite\Core\Config::getInstance()->CDev->Paypal->vendor)
            && !empty(\XLite\Core\Config::getInstance()->CDev->Paypal->pwd)
            && $this->isMerchantCountryAllowed();
    }

    /**
     * Check - payment processor is applicable for specified order or not
     *
     * @param \XLite\Model\Order          $order  Order
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isApplicable(\XLite\Model\Order $order, \XLite\Model\Payment\Method $method)
    {
        return parent::isApplicable($order, $method)
            && in_array(strtoupper($order->getCurrency()->getCode()), $this->getAllowedCurrencies($method));
    }

    /**
     * Return true if merchant country is allowed for this payment method
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isMerchantCountryAllowed()
    {
        return in_array(
            \XLite\Core\Config::getInstance()->Company->location_country,
            $this->getAllowedMerchantCountries()
        );
    }

    /**
     * Payment method has settings into Module settings section
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function hasModuleSettings()
    {
        return true;
    }

    /**
     * Define saved into transaction data schema
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineSavedData()
    {
        $data = parent::defineSavedData();

        $data['TRANSTIME'] = 'Transaction timestamp';
        $data['PNREF']     = 'Unique Payflow transaction ID (PNREF)';
        $data['PPREF']     = 'Unique PayPal transaction ID (PPREF)';
        $data['TYPE']      = 'Transaction type';
        $data['RESULT']    = 'Transaction result code (RESULT)';
        $data['RESPMSG']   = 'Transaction result message (RESPMSG)';

        $data['CORRELATIONID'] = 'Tracking ID'; // Can be provided to PayPal Merchant Technical Services to assist with debugging transactions.

        return $data;
    }

    /**
     * Get allowed currencies
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.9
     */
    protected function getAllowedCurrencies(\XLite\Model\Payment\Method $method)
    {
        return array_merge(
            parent::getAllowedCurrencies($method),
            array(
                'USD', // US Dollar
                'CAD', // Canadian Dollar
                'AUD', // Australian Dollar
                'EUR', // Euro
                'GBP', // British Pound Sterling
                'JPY', // Japanese Yen
            )
        );
    }
}
