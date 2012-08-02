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

    /**
     * X-Payments client
     *
     * @var \XLite\Module\CDev\XPaymentsConnector\Core\XPaymentsClient
     */
    protected $client;

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
        return preg_replace('/\/+$/Ss', '', \XLite\Core\Config::getInstance()->CDev->XPaymentsConnector->xpc_xpayments_url)
            . '/payment.php';
    }

    /**
     * Get redirect form fields list
     *
     * @return array
     */
    protected function getFormFields()
    {
        $cart = \XLite\Model\Cart::getInstance();
        $refId = $this->transaction->getTransactionId();

        list($status, $response) = $this->client->requestPaymentInit(
            $this->transaction->getPaymentMethod(),
            $refId,
            $cart,
            true 
        );

        return $status
            ? $response['fields']
            : array();
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
        parent::processReturn($transaction);

		$txnId = \XLite\Core\Request::getInstance()->txnId;
		list($status, $response) = $this->client->requestPaymentInfo($txnId);

		$transaction->setDataCell('xpc_txnid', $txnId, 'X-Payments transaction id');
		$transactionStatus = $transaction::STATUS_FAILED;

        if (
            $status
            && in_array($response['status'], array(self::AUTH_STATUS, self::CHARGED_STATUS))
        ) {
            $this->transaction->setDataCell('xpc_message', 'X-Payments response', $response['message']);

            if ($response['isFraudStatus']) {
                $this->transaction->setDataCell('xpc_fmf', 'Fraud status', 'blocked');
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

				$transactionStatus = $transaction::STATUS_SUCCESS;
            }
		}

		$transaction->setStatus($transactionStatus);
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
     * Process callback
     *
     * @param \XLite\Model\Payment\Transaction $transaction Callback-owner transaction
     *
     * @return void
     */
    public function processCallback(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processCallback($transaction);
		$this->transaction->setStatus($transaction::STATUS_SUCCESS);
	}
}
