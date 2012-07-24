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
 * @see   ____class_see____
 * @since 1.0.1
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
     * @see    ____func_see____
     * @since  1.1.0
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
     * @see    ____func_see____
     * @since  1.0.0
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

                        if (
                            isset($backendTransaction)
                            && \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_CAPTURE == $backendTransaction->getType()
                        ) {
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

                        if (
                            isset($backendTransaction)
                            && \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_VOID == $backendTransaction->getType()
                        ) {
                            $backendTransactionStatus = $transaction::STATUS_SUCCESS;
                        }

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

                        if (
                            isset($backendTransaction)
                            && \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_REFUND == $backendTransaction->getType()
                        ) {
                            $backendTransactionStatus = $transaction::STATUS_SUCCESS;
                        }

                        $status = $transaction::STATUS_FAILED;

                        break;

                    default:
                        // No default actions
                }

            default:
                // No default actions
        }

        $transaction->setStatus($status);

        if (isset($backendTransactionStatus)) {
            $backendTransaction->setStatus($backendTransactionStatus);
            $processor->updateInitialBackendTransaction($transaction, $status);
        }
    }

    /**
     * Return URL for IPN verification transaction
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function getIPNURL()
    {
        return $this->getFormURL() . '?cmd=_notify-validate';
    }

    /**
     * Get IPN verification status
     *
     * @return boolean TRUE if verification status is received
     * @see    ____func_see____
     * @since  1.0.1
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
        return !empty(\XLite\Core\Request::getInstance()->test_ipn);
    }
}
