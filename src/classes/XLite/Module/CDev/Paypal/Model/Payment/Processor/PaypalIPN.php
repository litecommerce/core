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

namespace XLite\Module\CDev\Paypal\Model\Payment\Processor;

/**
 * Paypal IPN processor (helper class)
 *
 */
class PaypalIPN extends \XLite\Base\Singleton
{
    /**
     * IPN statuses
     */
    const IPN_VERIFIED = 'verify';
    const IPN_DECLINED = 'decline';
    const IPN_REQUEST_ERROR = 'request_error';


    /**
     * Return true if received callback request is Paypal IPN 
     * 
     * @return boolean
     */
    public function isCallbackIPN()
    {
        return !empty(\XLite\Core\Request::getInstance()->payment_status);
    }

    /**
     * Process callback
     *
     * @param \XLite\Model\Payment\Transaction    $transaction Callback-owner transaction
     * @param \XLite\Model\Payment\Base\Processor $processor   Payment processor object
     *
     * @return void
     */
    public function processCallbackIPN($transaction, $processor)
    {
        $request = \XLite\Core\Request::getInstance();

        \XLite\Module\CDev\Paypal\Main::addLog('processCallbackIPN()', $request->getData());

        $status = $transaction::STATUS_FAILED;

        switch ($this->getIPNVerification()) {

            case self::IPN_DECLINED:

                $status = $transaction::STATUS_FAILED;
                $processor->markCallbackRequestAsInvalid(static::t('IPN verification failed'));

                break;

            case self::IPN_REQUEST_ERROR:

                $status = $transaction::STATUS_PENDING;
                $processor->markCallbackRequestAsInvalid(static::t('IPN HTTP error'));

                break;

            case self::IPN_VERIFIED:

                $backendTransaction = null;

                if (!empty($request->parent_txn_id)) {

                    // Received IPN is related to the backend transaction
                    $ppref = \XLite\Core\Database::getRepo('XLite\Model\Payment\BackendTransactionData')
                        ->findOneBy(
                            array(
                                'name' => 'PPREF',
                                'value' => $request->txn_id,
                            )
                        );

                    if ($ppref) {
                        $backendTransaction = $ppref->getTransaction();
                    }
                }

                switch ($request->payment_status) {

                    case 'Completed':
                    case 'Canceled_Reversal':
                    case 'Processed':

                        $status = $transaction::STATUS_SUCCESS;

                        if (\XLite\Model\Payment\BackendTransaction::TRAN_TYPE_AUTH == $transaction->getType()) {
    
                            if (!isset($backendTransaction)) {
                                $backendTransaction = $this->registerBackendTransaction(
                                    $transaction,
                                    \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_CAPTURE
                                );
                            }

                            $backendTransactionStatus = $transaction::STATUS_SUCCESS;
                        }

                        break;

                    case 'Pending':

                        if (
                            'authorization' == $request->pending_reason
                            && \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_AUTH == $transaction->getType()
                        ) {
                            $status = $transaction::STATUS_SUCCESS;

                            if (isset($backendTransaction)) {
                                $backendTransactionStatus = $transaction::STATUS_SUCCESS;
                            }

                        } else {
                            $status = $transaction::STATUS_PENDING;
                        }

                        break;

                    case 'Expired':

                        if (\XLite\Model\Payment\BackendTransaction::TRAN_TYPE_AUTH == $transaction->getType()) {
                            $status = $transaction::STATUS_FAILED;

                            if (isset($backendTransaction)) {
                                $backendTransactionStatus = $transaction::STATUS_FAILED;
                            }
                        }

                        break;

                    case 'Voided':

                        if (\XLite\Model\Payment\BackendTransaction::TRAN_TYPE_AUTH == $transaction->getType()) {
                            $status = $transaction::STATUS_FAILED;
                        }

                        if (!isset($backendTransaction)) {
                            $backendTransaction = $this->registerBackendTransaction(
                                $transaction,
                                \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_VOID
                            );
                        }

                        $backendTransactionStatus = $transaction::STATUS_SUCCESS;

                        break;

                    case 'Denied':
                    case 'Reversed':

                        $status = $transaction::STATUS_FAILED;

                    case 'Failed':
                    
                        if (isset($backendTransaction)) {
                            $backendTransactionStatus = $transaction::STATUS_FAILED;
                        }

                        break;

                    case 'Refunded':

                        if (!isset($backendTransaction)) {
                            $backendTransaction = $this->registerBackendTransaction(
                                $transaction,
                                \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_REFUND
                            );
                        }

                        $backendTransactionStatus = $transaction::STATUS_SUCCESS;

                        $status = $transaction::STATUS_FAILED;

                        break;

                    default:
                        // No default actions
                }

            default:
                // No default actions
        }

        if ($transaction->getStatus() != $status) {
           $transaction->setStatus($status);
           $transaction->registerTransactionInOrderHistory('callback, IPN');
        }

        if (isset($backendTransactionStatus)) {

            if ($backendTransaction->getStatus() != $backendTransactionStatus) {
                $backendTransaction->setStatus($backendTransactionStatus);
                $backendTransaction->registerTransactionInOrderHistory('callback, IPN');
            }

            $processor->updateInitialBackendTransaction($transaction, $status);

        } elseif (!empty($request->parent_txn_id)) {
            \XLite\Core\OrderHistory::getInstance()->registerTransaction(
                $transaction->getOrder()->getOrderId(),
                sprintf(
                    'IPN received [method: %s, amount: %s, payment status: %s]',
                    $transaction->getPaymentMethod()->getName(),
                    $request->transaction_entity,
                    $request->mc_gross,
                    $request->payment_status
                ),
                $this->getRequestData(),
                'Note: received IPN does not relate to any backend transaction registered with the order. It is possible if you update payment directly on PayPal site or if your customer or PayPal updated the payment.'
            );
        }
    }

    /**
     * getRequestData 
     * 
     * @return array
     */
    protected function getRequestData()
    {
        $result = array();

        foreach ($this->defineSavedData() as $key => $name) {
            if (isset(\XLite\Core\Request::getInstance()->$key)) {
                $result[] = array(
                    'name'  => $key,
                    'value' => \XLite\Core\Request::getInstance()->$key,
                    'label' => $name,
                );
            }
        }

        return $result;
    }

    /**
     * Define saved into transaction data schema
     *
     * @return array
     */
    protected function defineSavedData()
    {
        return array(
            'secureid'       => 'Transaction id',
            'mc_gross'       => 'Payment amount',
            'payment_type'   => 'Payment type',
            'payment_status' => 'Payment status',
            'pending_reason' => 'Pending reason',
            'reason_code'    => 'Reason code',
            'mc_currency'    => 'Payment currency',
            'auth_id'        => 'Authorization ID',
            'auth_status'    => 'Status of authorization',
            'auth_exp'       => 'Authorization expiration date and time',
            'auth_amount'    => 'Authorization amount',
            'payer_id'       => 'Unique customer ID',
            'payer_email'    => 'Customer\'s primary email address',
            'txn_id'         => 'Original transaction ID',
            'parent_txn_id'  => 'Parent transaction ID'
        );
    }

    /**
     * Return URL for IPN verification transaction
     *
     * @return string
     */
    protected function getIPNURL()
    {
        return $this->getFormURL() . '?cmd=_notify-validate';
    }

    /**
     * Get IPN verification status
     *
     * @return boolean TRUE if verification status is received
     */
    protected function getIPNVerification()
    {
        $ipnRequest = new \XLite\Core\HTTP\Request($this->getIPNURL());
        $ipnRequest->body = \XLite\Core\Request::getInstance()->getData();

        $ipnResult = $ipnRequest->sendRequest();

        if ($ipnResult) {
    
            \XLite\Module\CDev\Paypal\Main::addLog('getIPNVerification()', $ipnResult->body);

            $result =  (0 < preg_match('/VERIFIED/i', $ipnResult->body))
                    ? self::IPN_VERIFIED
                    : self::IPN_DECLINED;
        } else {
            $result = self::IPN_REQUEST_ERROR;
        }

        return $result;
    }

    /**
     * Get redirect form URL
     *
     * @return string
     */
    protected function getFormURL()
    {
        return $this->isTestModeEnabled()
            ? 'https://www.sandbox.paypal.com/cgi-bin/webscr'
            : 'https://www.paypal.com/cgi-bin/webscr';
    }

    /**
     * Return TRUE if the test mode is ON
     *
     * @return boolean
     */
    protected function isTestModeEnabled()
    {
        return !empty(\XLite\Core\Request::getInstance()->test_ipn);
    }

    /**
     * Register backend transaction 
     * 
     * @param \XLite\Model\Payment\Transaction $transaction     Payment transaction object
     * @param string                           $transactionType Type of backend transaction
     *  
     * @return \XLite\Model\Payment\BackendTransaction
     */
    protected function registerBackendTransaction($transaction, $transactionType)
    {
        $backendTransaction = $transaction->createBackendTransaction($transactionType);

        $transactionData = $this->getRequestData();
        $transactionData[] = array(
            'name'  => 'PPREF',
            'value' => \XLite\Core\Request::getInstance()->txn_id,
            'label' => 'Unique PayPal transaction ID (PPREF)',
        );

        foreach ($transactionData as $data) {
            $backendTransaction->setDataCell(
                $data['name'],
                $data['value'],
                $data['label']
            );
        }

        return $backendTransaction;
    }
}
