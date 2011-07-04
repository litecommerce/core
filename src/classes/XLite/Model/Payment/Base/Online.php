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
 * @since     1.0.0
 */

namespace XLite\Model\Payment\Base;

/**
 * Abstract online (gateway-based) processor
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Online extends \XLite\Model\Payment\Base\Processor
{
    /**
     * Default return transaction id field name
     */
    const RETURN_TXN_ID = 'txnId';


    /**
     * Return response type
     */
    const RETURN_TYPE_HTTP_REDIRECT = 'http';
    const RETURN_TYPE_HTML_REDIRECT = 'html';
    const RETURN_TYPE_CUSTOM        = 'custom';


    /**
     * Process return
     *
     * @param \XLite\Model\Payment\Transaction $transaction Return-owner transaction
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function processReturn(\XLite\Model\Payment\Transaction $transaction)
    {
        $this->transaction = $transaction;

        $this->logReturn(\XLite\Core\Request::getInstance()->getData());
    }

    /**
     * Get return type
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getReturnType()
    {
        return self::RETURN_TYPE_HTTP_REDIRECT;
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
        $this->transaction = $transaction;

        $this->logCallback(\XLite\Core\Request::getInstance()->getData());
    }

    /**
     * Get callback reqeust owner transaction or null
     *
     * @return \XLite\Model\Payment\Transaction|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCallbackOwnerTransaction()
    {
        return null;
    }

    /**
     * Get client IP
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getClientIP()
    {
        $result = null;

        if (
            isset($_SERVER['REMOTE_ADDR'])
            && preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/Ss', $_SERVER['REMOTE_ADDR'])
        ) {
            $result = $_SERVER['REMOTE_ADDR'];
        }

        return $result;
    }

    /**
     * Get invoice description
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getInvoiceDescription()
    {
        return 'Order #' . $this->getSetting('prefix') . $this->getOrder()->getOrderId()
            . '; transaction: ' . $this->transaction->getTransactionId();
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
        return array();
    }

    /**
     * Save request data into transaction
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function saveDataFromRequest()
    {
        foreach ($this->defineSavedData() as $key => $name) {
            if (isset(\XLite\Core\Request::getInstance()->$key)) {
                $this->setDetail($key, \XLite\Core\Request::getInstance()->$key, $name);
            }
        }
    }

    /**
     * Array cell mask
     *
     * @param array  $list Array
     * @param string $name CEll key
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function maskCell(array $list, $name)
    {
        if (isset($list[$name])) {
            $list[$name] = str_repeat('*', strlen($list[$name]));
        }

        return $list;
    }

    /**
     * Log return request
     *
     * @param array $list Request data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function logReturn(array $list)
    {
        \XLite\Logger::getInstance()->log(
            $this->transaction->getPaymentMethod()->getServiceName() . ' payment gateway : return' . PHP_EOL
            . 'Data: ' . var_export($list, true),
            LOG_DEBUG
        );
    }

    /**
     * Log callback
     *
     * @param array $list Callback data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function logCallback(array $list)
    {
        \XLite\Logger::getInstance()->log(
            $this->transaction->getPaymentMethod()->getServiceName() . ' payment gateway : callback' . PHP_EOL
            . 'Data: ' . var_export($list, true),
            LOG_DEBUG
        );
    }

    /**
     * Get transactionId-based return URL
     *
     * @param string  $fieldName TransactionId field name OPTIONAL
     * @param boolean $withId    Add to URL transaction id or not OPTIONAL
     * @param boolean $asCancel  Mark URL as cancel action
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getReturnURL($fieldName = self::RETURN_TXN_ID, $withId = false, $asCancel = false)
    {
        $query = array(
            'txn_id_name' => $fieldName ?: self::RETURN_TXN_ID,
        );

        if ($withId) {
            $query[$query['txn_id_name']] = $this->transaction->getTransactionId();
        }

        if ($asCancel) {
            $query['cancel'] = 1;
        }
        return \XLite::getInstance()->getShopURL(
            \XLite\Core\Converter::buildURL('payment_return', '', $query),
            true
        );
    }

    /**
     * Get transactionId-based callback URL
     *
     * @param string  $fieldName TransactionId field name OPTIONAL
     * @param boolean $withId    Add to URL transaction id or not OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCallbackURL($fieldName = self::RETURN_TXN_ID, $withId = false)
    {
        $query = array(
            'txn_id_name' => $fieldName ?: self::RETURN_TXN_ID,
        );

        if ($withId) {
            $query[$query['txn_id_name']] = $this->transaction->getTransactionId();
        }

        return \XLite::getInstance()->getShopURL(
            \XLite\Core\Converter::buildURL('callback', '', $query),
            true
        );
    }

    /**
     * Check total (transaction total and total from gateway response)
     *
     * @param float $total Total from gateway response
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkTotal($total)
    {
        $result = true;

        if ($total && $this->transaction->getValue() != $total) {
            $msg = 'Total amount doesn\'t match. Transaction total: ' . $this->transaction->getValue()
                . '; payment gateway amount: ' . $total;
            $this->setDetail(
                'total_checking_error',
                $msg,
                'Hacking attempt'
            );

            $result = false;
        }

        return $result;
    }

    /**
     * Check currency (order currency and transaction response currency)
     *
     * @param string $currency Transaction response currency code
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkCurrency($currency)
    {
        $result = true;

        if ($currency && $this->transaction->getOrder()->getCurrency()->getCode() != $currency) {
            $msg = 'Currency code doesn\'t match. Order currency: '
                . $this->transaction->getOrder()->getCurrency()->getCode()
                . '; payment gateway currency: ' . $currency;
            $this->setDetail(
                'currency_checking_error',
                $msg,
                'Hacking attempt details'
            );

            $result = false;
        }

        return $result;
    }


}
