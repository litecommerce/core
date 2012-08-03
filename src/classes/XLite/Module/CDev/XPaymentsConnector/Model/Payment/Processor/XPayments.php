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
    const NEW_STATUS      = 1;
    const AUTH_STATUS     = 2;
    const DECLINED_STATUS = 3;
    const CHARGED_STATUS  = 4;

    // Payment actions
    const NEW_ACTION         = 1;
    const AUTH_ACTION        = 2;
    const CHARGED_ACTION     = 3;
    const DECLINED_ACTION    = 4;
    const REFUND_ACTION      = 5;
    const PART_REFUND_ACTION = 6;

    // Transaction types
    const TRAN_TYPE_AUTH          = 'auth';
    const TRAN_TYPE_CAPTURE       = 'capture';
    const TRAN_TYPE_CAPTURE_PART  = 'capturePart';
    const TRAN_TYPE_CAPTURE_MULTI = 'captureMulti';
    const TRAN_TYPE_VOID          = 'void';
    const TRAN_TYPE_VOID_PART     = 'voidPart';
    const TRAN_TYPE_VOID_MULTI    = 'voidMulti';
    const TRAN_TYPE_REFUND        = 'refund';
    const TRAN_TYPE_PART_REFUND   = 'refundPart';
    const TRAN_TYPE_REFUND_MULTI  = 'refundMulti';
    const TRAN_TYPE_GET_INFO      = 'getInfo';
    const TRAN_TYPE_ACCEPT        = 'accept';
    const TRAN_TYPE_DECLINE       = 'decline';
  
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
                $transactionStatus = $this->getTransactionStatusByAction($response['status']);
            }
        }

        if ($transactionStatus) {
            $transaction->setStatus($transactionStatus);
        }
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

            $status = $this->getTransactionStatusByAction($updateData['status']);

            if ($status) {
                $transaction->setStatus($status);
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
     * Constructor
     *
     * @return void
     */
    protected function __construct()
    {
        $this->client = new \XLite\Module\CDev\XPaymentsConnector\Core\XPaymentsClient;
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

            $this->formFields =  $status
                ? $response['fields']
                : array();
        }

        return $this->formFields;
    }


    /**
     * Get transaction status by action 
     *
     * @return mixed
     */
    protected function getTransactionStatusByAction($action)
    {
        $action = intval($action);
        $config = \XLite\Core\Config::getInstance()->CDev->XPaymentsConnector;

        $cell = false;
        switch ($action) {
            case self::NEW_ACTION:
                $cell = 'xpc_status_new';
                break;

            case self::AUTH_ACTION:
                $cell = 'xpc_status_auth';
                break;

            case self::CHARGED_ACTION:
                $cell = 'xpc_status_charged';
                break;

            case self::DECLINED_ACTION:
                $cell = 'xpc_status_declined';
                break;

            case self::REFUND_ACTION:
                $cell = 'xpc_status_refunded';
                break;

            case self::PART_REFUND_ACTION:
                $cell = 'xpc_status_part_refunded';
                break;

            default:
        }

        return ($cell && isset($config->$cell) && $config->$cell)
            ? $config->$cell
            : false;
    }


}
