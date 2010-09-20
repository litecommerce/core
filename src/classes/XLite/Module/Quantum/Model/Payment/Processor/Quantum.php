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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\Quantum\Model\Payment\Processor;

/**
 * QuantumGateway QGWdatabase Engine payment processor
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Quantum extends \XLite\Model\Payment\Base\WebBased
{
    /**
     * Get settings widget or template 
     * 
     * @return string Widget class name or template path
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSettingsWidget()
    {
        return 'modules/Quantum/config.tpl';
    }

    /**
     * Get redirect form URL 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFormURL()
    {
        return 'https://secure.quantumgateway.com/cgi/qgwdbe.php';
    }

    /**
     * Get redirect form fields list
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFormFields()
    {
        return array(
            'gwlogin'                  => $this->getSetting('login'),
            'post_return_url_approved' => $this->getReturnURL('ID'),
            'post_return_url_declined' => $this->getReturnURL('ID'),
            'ID'                       => $this->transaction->getTransactionId(),
            'amount'                   => $this->transaction->getValue(),
            'BADDR1'                   => $this->getProfile()->get('billing_address'),
            'BZIP1'                    => $this->getProfile()->get('billing_zipcode'),

            'FNAME'       => $this->getProfile()->get('billing_firstname'),
            'LNAME'       => $this->getProfile()->get('billing_lastname'),
            'BCITY'       => $this->getProfile()->get('billing_city'),
            'BSTATE'      => $this->getProfile()->get('billing_state'),
            'BCOUNTRY'    => $this->getProfile()->get('billing_country'),
            'BCUST_EMAIL' => $this->getProfile()->get('login'),

            'SFNAME'   => $this->getProfile()->get('shipping_firstname'),
            'SLNAME'   => $this->getProfile()->get('shipping_lastname'),
            'SADDR1'   => $this->getProfile()->get('shipping_address'),
            'SCITY'    => $this->getProfile()->get('shipping_city'),
            'SSTATE'   => $this->getProfile()->get('shipping_state'),
            'SZIP1'    => $this->getProfile()->get('shipping_zipcode'),
            'SCOUNTRY' => $this->getProfile()->get('shipping_country'),

            'PHONE'               => $this->getProfile()->get('billing_phone'),
            'trans_method'        => 'CC',
            'ResponseMethod'      => 'POST',
            'cust_id'             => $this->getProfile()->get('login'),
            'customer_ip'         => $this->getClientIP(),
            'invoice_num'         => $this->getOrder()->getOrderId(),
            'invoice_description' => $this->getInvoiceDescription(),
            'MAXMIND'             => '1',
        );
    }

    /**
     * Process return
     * 
     * @param \XLite\Model\Payment\Transaction $transaction Return-owner transaction
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured()
            && $method->getSetting('login');
    }

    /**
     * Define saved into transaction data schema
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function logRedirect(array $list)
    {
        $list = $this->maskCell($list, 'gwlogin');

        parent::logRedirect($list);
    }

}
