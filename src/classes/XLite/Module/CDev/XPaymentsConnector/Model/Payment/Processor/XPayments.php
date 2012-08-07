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

namespace XLite\Module\CDev\XPaymentsConnector\Model\Payment\Processor;

/**
 * XPayments payment processor
 *
 */
class XPayments extends \XLite\Model\Payment\Base\WebBased
{
    // Payment statuses
    const STATUS_NEW      = 1;
    const STATUS_AUTH     = 2;
    const STATUS_DECLINED = 3;
    const STATUS_CHARGED  = 4;

    /**
     * X-Payments client
     *
     * @var \XLite\Module\CDev\XPaymentsConnector\Core\XPaymentsClient
     */
    protected $client;

    /**
     * Form fields
     *
     * @var array
     */
    protected $formFields = null;

    /**
     * Transaction statuses
     *
     * @var array
     */
    protected $transactionStatuses = array (
        self::STATUS_NEW      => \XLite\Model\Payment\Transaction::STATUS_INITIALIZED,
        self::STATUS_AUTH     => \XLite\Model\Payment\Transaction::STATUS_SUCCESS,
        self::STATUS_DECLINED => \XLite\Model\Payment\Transaction::STATUS_FAILED,
        self::STATUS_CHARGED  => \XLite\Model\Payment\Transaction::STATUS_SUCCESS,
    );

    /**
     * Payment method has settings into Module settings section
     *
     * @return boolean
     */
    public function hasModuleSettings()
    {
        return true;
    }

    /**
     * Returns the list of settings available for this payment processor
     *
     * @return array
     */
    public function getAvailableSettings()
    {
        return array(
            'name',
            'id',
            'sale',
            'auth',
            'capture',
            'capturePart',
            'captureMulti',
            'void',
            'voidPart',
            'voidMulti',
            'refund',
            'refundPart',
            'refundMulti',
            'getInfo',
            'accept',
            'decline',
            'test',
            'authExp',
            'captMinLimit',
            'captMaxLimit',
            'moduleName',
            'settingsHash',
            'useLiteInterface',
        );
    }

    /**
     * Get operation types
     *
     * @return array
     */
    public function getOperationTypes()
    {
        $types = array(
            self::OPERATION_SALE,
            self::OPERATION_AUTH,
            self::OPERATION_CAPTURE,
            self::OPERATION_CAPTURE_PART,
            self::OPERATION_CAPTURE_MULTI,
            self::OPERATION_VOID,
            self::OPERATION_VOID_PART,
            self::OPERATION_VOID_MULTI,
            self::OPERATION_REFUND,
            self::OPERATION_REFUND_PART,
            self::OPERATION_REFUND_MULTI,
        );

        foreach ($types as $k => $v) {
            if (!$this->transaction->getPaymentMethod()->getSetting($v)) {
                unset($types[$k]);
            }
        }    

        return $types;
    }

    /**
     * Process return
     *
     * @param \XLite\Model\Payment\Transaction $transaction Return-owner transaction
     *
     * @return void
     */
    public function processReturn(\XLite\Model\Payment\Transaction $transaction)
    {
        $txnId = \XLite\Core\Request::getInstance()->txnId;
        list($status, $response) = $this->client->requestPaymentInfo($txnId);

        $transactionStatus = $transaction::STATUS_FAILED;

        if ($status) {
            $transaction->setDataCell('xpc_message', 'X-Payments response', $response['message']);

            if ($response['isFraudStatus']) {
                $transaction->setDataCell('xpc_fmf', 'Fraud status', 'blocked');
            }

            if ($response['amount'] != $transaction->getOrder()->getTotal()) {

                // Total wrong
                $transaction->setDataCell('error', 'Error', 'Hacking attempt!');
                $transaction->setDataCell(
                    'errorDescription',
                    'Hacking attempt details',
                    'Total amount doesn\'t match: Order total = '
                    . $transaction->getOrder()->getTotal()
                    . ', X-Payments amount = ' . $response['amount']
                );

            } elseif ($response['currency'] != $transaction->getOrder()->getCurrency()->getCode()) {

                // Currency wrong
                $transaction->setDataCell('error', 'Error', 'Hacking attempt!');
                $transaction->setDataCell(
                    'errorDescription',
                    'Hacking attempt details',
                    'Currency code doesn\'t match: Order currency = '
                    . $transaction->getOrder()->getCurrency()->getCode()
                    . ', X-Payments currency = ' . $response['currency']
                );

            } else {
                $transactionStatus = $this->getTransactionStatusByStatus($updateData['status']);
            }
        }

        if ($transactionStatus) {
            $transaction->setStatus($transactionStatus);
        }

        $this->transaction = $transaction;
    }

    /**
     * Process callback
     *
     * @param \XLite\Model\Payment\Transaction $transaction Callback-owner transaction
     *
     * @return void
     */
    public function processCallback(\XLite\Model\Payment\Transaction $transaction)
    {
        $request = \XLite\Core\Request::getInstance();

        list($status, $updateData) = $this->client->decryptXml($request->updateData);
        if ($status) {
            $updateData = $this->client->convertXmlToHash($updateData);
            $updateData = $updateData['data'];
        }

        if (
            $status
            && $request->txnId
            && $updateData
            && isset($updateData['status'])
        ) {
            $status = $this->getTransactionStatusByStatus($updateData['status']);
            if ($status) {
                $transaction->setStatus($status);
                $this->registerBackendTransaction($transaction, $updateData);
            }
        }
    }

    /**
     * Get return request owner transaction or null
     *
     * @return \XLite\Model\Payment\Transaction|void
     */
    public function getReturnOwnerTransaction()
    {
        $transactionId = \XLite\Core\Request::getInstance()->refId;

        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Transaction')
            ->find($transactionId);
    }

    /**
     * Get callback request owner transaction or null
     *
     * @return \XLite\Model\Payment\Transaction|void
     */
    public function getCallbackOwnerTransaction()
    {
        $txnId = \XLite\Core\Request::getInstance()->txnId;

        $transaction = null;

        if ($txnId) {
            $transactionData = \XLite\Core\Database::getRepo('XLite\Model\Payment\TransactionData')
                ->findOneBy(array('value' => $txnId, 'name' => 'xpc_txnid'));
            if ($transactionData) {
                $transaction = $transactionData->getTransaction();
 
            }
        }

        return $transaction;
    }

    /**
     * Check - payment method is configured or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured($method)
            && \XLite\Module\CDev\XPaymentsConnector\Core\XPaymentsClient::getInstance()->isModuleConfigured();
    }

    /**
     * Get return type
     *
     * @return string
     */
    public function getReturnType()
    {
        return 'Y' == $this->transaction->getPaymentMethod()->getSetting('useLiteInterface')
            ? static::RETURN_TYPE_CUSTOM
            : parent::getReturnType();
    }

    /**
     * Do custom redirect after customer's return
     *
     * @return void
     */
    public function doCustomReturnRedirect()
    {
        $orderId = $this->transaction->getOrder()->getOrderId();
        $page = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">
function func_redirect(orderId) {
    parent.jQuery('form.place').get(0).setAttribute('action', '?target=checkout&order_id=' + orderId);
    parent.jQuery('form.place input[name="action"]').val('xpc_return');
    parent.jQuery('.bright').click();
}
</script>
</head>
<body onload="javascript: func_redirect('$orderId');">
Please wait while processing the payment details...
</body>
</html>
HTML;

        print ($page);
        exit ();
    }

    /**
     * Constructor
     *
     * @return void
     */
    protected function __construct()
    {
        $this->client = new \XLite\Module\CDev\XPaymentsConnector\Core\XPaymentsClient;
    }

    /**
     * Do initial payment
     *
     * @return string Status code
     */
    protected function doInitialPayment()
    {
        $status = parent::doInitialPayment();
        if (
            static::PROLONGATION == $status
            && 'Y' == $this->transaction->getPaymentMethod()->getSetting('useLiteInterface')
        ) {
            exit ();
        }

        return $status;
    }

    /**
     * Get redirect form URL
     *
     * @return string
     */
    protected function getFormURL()
    {
        $config = \XLite\Core\Config::getInstance()->CDev->XPaymentsConnector;

        return preg_replace('/\/+$/Ss', '', $config->xpc_xpayments_url) . '/payment.php';
    }

    /**
     * Get redirect form fields list
     *
     * @return array
     */
    protected function getFormFields()
    {
        if (
            !is_array($this->formFields)
        ) {
            $cart = \XLite\Model\Cart::getInstance();
            $refId = $this->transaction->getTransactionId();

            list($status, $response) = $this->client->requestPaymentInit(
                $this->transaction->getPaymentMethod(),
                $refId,
                $cart,
                true 
            );

            if ($status) {
                $this->transaction->setDataCell('xpc_txnid', $response['txnId'], 'X-Payments transaction id');
            }

            \XLite\Core\Database::getEM()->flush();
            $this->formFields = $status
                ? $response['fields']
                : array();
        }

        return $this->formFields;
    }

    /**
     * Get transaction status by action 
     *
     * @param integer $status Status
     *
     * @return mixed
     */
    protected function getTransactionStatusByStatus($status)
    {
        $status = intval($status);

        return isset($this->transactionStatuses[$status])
            ? $this->transactionStatuses[$status]
            : null;
    }

    /**
     * Get transaction status by action 
     *
     * @param \XLite\Model\Payment\Transaction $transaction Callback-owner transaction
     * @param array                            $data        Data
     *
     * @return void
     */
    protected function registerBackendTransaction(\XLite\Model\Payment\Transaction $transaction, $data)
    {
        $type = $value = false;
        switch ($data['status']) {
            case static::STATUS_NEW:
                $type = \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_SALE;
                break;

            case static::STATUS_AUTH:
                $type = \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_AUTH;
                break;

            case static::STATUS_DECLINED:
                if (0 == $data['authorized']) {
                    $type = \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_DECLINE;
                } elseif ($data['amount'] == $data['voidedAmount']) {
                    $type = \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_VOID;
                } else {
                    $type = \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_VOID_PART;
                    $value = $data['voidedAmount'];
                }
                break;

            case static::STATUS_CHARGED:
                if ($data['amount'] == $data['capturedAmount']) {
                    $type = \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_CAPTURE;
                } else {
                    $type = \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_CAPTURE_PART;
                    $value = $data['capturedAmount'];
                }
                break;

            default:

        }

        if ($type) {
            $backendTransaction = $transaction->createBackendTransaction($type);
            $backendTransaction->setStatus($transaction::STATUS_SUCCESS);
            if ($value) {
                $backendTransaction->setValue($value);
            }
            $backendTransaction->registerTransactionInOrderHistory('callback');
        }
    }

}
