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

namespace XLite\Module\CDev\Quantum\Model\Payment\Processor;

/**
 * QuantumGateway QGWdatabase Engine payment processor
 *
 * Find the latest API document here:
 * http://www.quantumgateway.com/files/QGWdbeAPI.pdf
 *
 */
class Quantum extends \XLite\Model\Payment\Base\WebBased
{
    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     */
    public function getSettingsWidget()
    {
        return 'modules/CDev/Quantum/config.tpl';
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

        $request = \XLite\Core\Request::getInstance();

        if ($request->isPost() && isset($request->trans_result)) {
            
            $status = 'APPROVED' == $request->trans_result
                ? $transaction::STATUS_SUCCESS
                : $transaction::STATUS_FAILED;

            $this->saveDataFromRequest();

            // Amount checking
            if (isset($request->amount) && !$this->checkTotal($request->amount)) {
                $status = $transaction::STATUS_FAILED;
            }

            if (isset($request->decline_reason)) {
                $this->transaction->setNote($request->decline_reason);
            }

            // MD5 hash checking
            if ($status == $transaction::STATUS_SUCCESS && isset($request->md5_hash)) {

                $hash = md5(
                    strval($this->getSetting('hash'))
                    . $this->getSetting('login')
                    . $request->transID
                    . $request->amount
                );

                if ($hash != $request->md5_hash) {
                    $status = $transaction::STATUS_FAILED;
                    $this->setDetail('hash_checking', 'failed', 'MD5 hash checking');
                }
            }

            $this->transaction->setStatus($status);
        }
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
            && $method->getSetting('login');
    }

    /**
     * Get payment method admin zone icon URL
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getAdminIconURL(\XLite\Model\Payment\Method $method)
    {
        return true;
    }


    /**
     * Get redirect form URL
     *
     * @return string
     */
    protected function getFormURL()
    {
        return 'https://secure.quantumgateway.com/cgi/qgwdbe.php';
    }

    /**
     * Get redirect form fields list
     *
     * @return array
     */
    protected function getFormFields()
    {
        $billingAddress = $this->getProfile()->getBillingAddress();

        $fields = array(
            'gwlogin'                  => $this->getSetting('login'),
            'post_return_url_approved' => $this->getReturnURL('ID'),
            'post_return_url_declined' => $this->getReturnURL('ID'),
            'ID'                       => $this->transaction->getTransactionId(),
            'amount'                   => $this->transaction->getValue(),
            'BADDR1'                   => $billingAddress->getStreet(),
            'BZIP1'                    => $billingAddress->getZipcode(),

            'FNAME'       => $billingAddress->getFirstname(),
            'LNAME'       => $billingAddress->getLastname(),
            'BCITY'       => $billingAddress->getCity(),
            'BSTATE'      => $billingAddress->getState()->getState(),
            'BCOUNTRY'    => $billingAddress->getCountry() ? $billingAddress->getCountry()->getCode() : '',
            'BCUST_EMAIL' => $this->getProfile()->getLogin(),

            'PHONE'               => $billingAddress->getPhone(),
            'trans_method'        => 'CC',
            'ResponseMethod'      => 'POST',
            'cust_id'             => $this->getProfile()->getLogin(),
            'customer_ip'         => $this->getClientIP(),
            'invoice_num'         => $this->getOrder()->getOrderId(),
            'invoice_description' => $this->getInvoiceDescription(),
            'MAXMIND'             => '1',
        );

        if ($shippingAddress = $this->getProfile()->getShippingAddress()) {

            $fields += array(
                'SFNAME'    => $shippingAddress->getFirstname(),
                'SLNAME'    => $shippingAddress->getLastname(),
                'SADDR1'    => $shippingAddress->getStreet(),
                'SCITY'     => $shippingAddress->getCity(),
                'SSTATE'    => $shippingAddress->getState()->getState(),
                'SZIP1'     => $shippingAddress->getZipcode(),
                'SCOUNTRY'  => $shippingAddress->getCountry()
                    ? $shippingAddress->getCountry()->getCode()
                    : '',
            );
        }

        return $fields;
    }

    /**
     * Define saved into transaction data schema
     *
     * @return array
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
     */
    protected function logRedirect(array $list)
    {
        $list = $this->maskCell($list, 'gwlogin');

        parent::logRedirect($list);
    }
}
