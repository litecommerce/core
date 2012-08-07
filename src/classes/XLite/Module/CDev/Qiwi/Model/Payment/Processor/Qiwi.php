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
 * @since     1.0.23
 */

namespace XLite\Module\CDev\Qiwi\Model\Payment\Processor;

/**
 * Qiwi payment processor
 * 
 * @see   ____class_see____
 * @since 1.0.23
 */
class Qiwi extends \XLite\Model\Payment\Base\Iframe
{
    /**
     * IFrame form URL
     */
    const IFRAME_FORM_URL = 'http://w.qiwi.ru/setInetBill.do';

    /**
     * Allowed currencies codes
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $allowedCurrencies = array('RUB', 'USD');

    /**
     * Bill statuses used in API communication
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.23
     */
    protected $billStatuses = array(
        50  => 'Created',
        52  => 'In progress',
        60  => 'Paid',
        150 => 'Cancelled (error on terminal)',
        151 => 'Cancelled (authorization error)',
        160 => 'Cancelled',
        161 => 'Cancelled (expired)',
    );

    /**
     * SOAP calls return codes
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.23
     */
    protected $soapCallsReturnCodes = array(
        13  => 'Server busy',
        150 => 'Authorization error',
        210 => 'Bill not found',
        215 => 'Bill with the same txn-id already exists',
        241 => 'Amount is too low',
        242 => 'Amount is too high',
        278 => 'getBillList() maximum call rate exceeded',
        298 => 'Agent does not exist in the system',
        300 => 'Unknown error',
        330 => 'Encryption error',
        370 => 'Maximum allowed amount of concurrent requests exceeded',
    );

    /**
     * Detect transaction
     * 
     * @return \XLite\Model\Payment\Transaction
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getReturnOwnerTransaction()
    {
        $txn = null;

        if (isset(\XLite\Core\Request::getInstance()->txn_id_name)) {
            $txnIdName = \XLite\Core\Request::getInstance()->txn_id_name;
            if (isset(\XLite\Core\Request::getInstance()->$txnIdName)) {
                $txn = \XLite\Core\Database::getRepo('XLite\Model\Payment\Transaction')
                    ->findOneBy(array('public_id' => \XLite\Core\Request::getInstance()->$txnIdName));
            }
        }

        return $txn;
    }

    /**
     * Get input template
     *
     * @return string|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getInputTemplate()
    {
        return 'modules/CDev/Qiwi/input.tpl';
    }

    /**
     * Get input errors
     *
     * @param array $data Input data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getInputErrors(array $data)
    {
        $errors = parent::getInputErrors($data);

        foreach ($this->getInputDataLabels() as $k => $t) {
            if (!isset($data[$k]) || !$data[$k]) {
                $errors[] = \XLite\Core\Translation::lbl('X field is required', array('field' => $t));
            }
        }

        if (empty($data['qiwi_phone_number']) || !preg_match('/^\d{10}$/', $data['qiwi_phone_number'])) {
            $errors[] = \XLite\Core\Translation::lbl(
                'Please enter 10-digit mobile phone number without country code (with no spaces or hyphens)'
            );
        }

        return $errors;
    }

    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSettingsWidget()
    {
        return 'modules/CDev/Qiwi/config.tpl';
    }

    /**
     * Check - payment method is configured or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured($method)
            && $method->getSetting('login')
            && $method->getSetting('password');
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
        return false;
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

        $client = new \XLite\Module\CDev\Qiwi\Core\QiwiSoapClient();

        $bill = $client->checkBill(
            $this->getSetting('login'),
            $this->getSetting('password'),
            $transaction->getPublicId()
        );

        if (60 == $bill->status) {

            // Paid
            $this->transaction->setStatus($transaction::STATUS_SUCCESS);

        } elseif (in_array($bill->status, array(150, 151, 160, 161))) {

            // Cancelled
            $reason = $bill->status ? $this->billStatuses[$bill->status] : 'Unknown';

            $this->transaction->setNote('Transaction failed. Reason: ' . $reason);
            $this->transaction->setStatus($transaction::STATUS_FAILED);
        }
    }

    /**
     * Get callback owner transaction or null
     *
     * @return \XLite\Model\Payment\Transaction|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCallbackOwnerTransaction()
    {
        $server = new \XLite\Module\CDev\Qiwi\Core\QiwiSoapServer();
        $server->handle();

        $publicTransactionId = $server->getPublicTransactionId();

        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Transaction')
            ->findOneBy(array('public_id' => $publicTransactionId));
    }

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
        parent::processReturn($transaction);

        $client = new \XLite\Module\CDev\Qiwi\Core\QiwiSoapClient();
        $bill = $client->checkBill(
            $this->getSetting('login'),
            $this->getSetting('password'),
            $transaction->getPublicId()
        );

        // Amount checking
        if (isset($bill->amount) && !$this->checkTotal($bill->amount)) {
            $this->transaction->setNote('Bill amount is incorrect');
            $this->transaction->setStatus($transaction::STATUS_FAILED);

        } else {
            if (in_array($bill->status, array(50, 52))) {

                // Created or in progress
                $this->transaction->setStatus($transaction::STATUS_PENDING);

            } elseif (60 == $bill->status) {

                // Paid
                $this->transaction->setStatus($transaction::STATUS_SUCCESS);

            } else {

                $reason = $bill->status ? $this->billStatuses[$bill->status] : 'Unknown';

                $this->transaction->setNote('Transaction failed. Reason: ' . $reason);
                $this->transaction->setStatus($transaction::STATUS_FAILED);
            }
        }
    }

    /**
     * Get iframe form URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getIframeFormURL()
    {
        return static::IFRAME_FORM_URL;
    }

    /**
     * Get iframe data
     *
     * @return string|array URL or POST data
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getIframeData()
    {
        $data = $this->transaction->getData();
        $qiwiPhoneNumber = '';
        foreach ($data as $cell) {
            if ('qiwi_phone_number' === $cell->getName()) {
                $qiwiPhoneNumber = $cell->getValue();
            }
        }

        $this->transaction->setPublicId($this->getSetting('prefix') . $this->transaction->getTransactionId());

        return array(
            'txn_id'    => $this->transaction->getPublicId(),
            'from'      => $this->getSetting('login'),
            'lifetime'  => $this->getSetting('lifetime'),
            'check_agt' => $this->getSetting('check_agt') ? 'true' : 'false',
            'to'        => $qiwiPhoneNumber,
            'summ'      => round($this->transaction->getValue(), 2),
            'com'       => '',
        );
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
            $this->allowedCurrencies
        );
    }

    /**
     * Get input data labels list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getInputDataLabels()
    {
        return array(
            'qiwi_phone_number' => 'Qiwi mobile phone number',
        );
    }

    /**
     * Get input data access levels list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getInputDataAccessLevels()
    {
        return array(
            'qiwi_phone_number' => \XLite\Model\Payment\TransactionData::ACCESS_CUSTOMER,
        );
    }
}
