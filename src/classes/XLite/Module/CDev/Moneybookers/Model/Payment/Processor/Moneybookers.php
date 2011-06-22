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

namespace XLite\Module\CDev\Moneybookers\Model\Payment\Processor;

/**
 * Moneybookers payment processor
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class Moneybookers extends \XLite\Model\Payment\Base\Iframe
{
    /**
     * Allowed languages 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $allowedLanguages = array(
        'EN', 'DE', 'ES', 'FR', 'IT', 'PL', 'GR', 'RO', 'RU', 'TR',
        'CN', 'CZ', 'NL', 'DA', 'SV', 'FI',
    );

    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSettingsWidget()
    {
        return 'modules/CDev/Moneybookers/config.tpl';
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
        $id = $this->getSessionId();

        return $id ? $this->getPostURL . '?sid=' . $id : null;
    }

    /**
     * Get Moneybookers session id 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSessionId()
    {
        $data = array(
            'pay_to_email'          => $this->getSetting('email'),
            'language'              => $this->getLanguageCode(),
            'recipient_description' => \XLite\Core\Config::getInstance()->Company->company_name,
            'transaction_id'        => $this->transaction->getTransactionId(),
            'pay_from_email'        => $this->getProfile()->getLogin(),
            'firstname'             => $this->getProfile()->getBillingAddress()->getFirstname(),
            'lastname'              => $this->getProfile()->getBillingAddress()->getLastname(),
            'address'               => $this->getProfile()->getBillingAddress()->getAddress(),
            'postal_code'           => $this->getProfile()->getBillingAddress()->getZipcode(),
            'city'                  => $this->getProfile()->getBillingAddress()->getCity(),
            'country'               => $this->getCountryCode(),
            'amount'                => $this->getOrder()->getCurrency()->roundValueAsInteger($this->transaction->getValue()),
            'currency'              => $this->getCurrencyCode(),
            'status_url'            => $this->getReturnURL('transaction_id'),
            'return_url'            => $this->getReturnURL('transaction_id'),
            'cancel_url'            => $this->getReturnURL('transaction_id'),
            'hide_login'            => 1,
            'prepare_only'          => 1,
        );

        if ($this->getSetting('logo_url')) {
            $data['logo_url'] = $this->getSetting('logo_url');
        }

        $request = \XLite\Core\HTPP\Request($this->getPostURL());
        $request->body = $data;
        $response = $request->sendRequest();

        $id = null;
        if (200 == $response->code && isset($response->cookies['SESSION_ID'])) {
            $id = $response->cookies['SESSION_ID'];
        }

        return $id;
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
     * @since  1.0.0
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured($method)
            && $method->getSetting('email');
    }

    /**
     * Get language code 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLanguageCode()
    {
        $code = strtoupper(\XLite\Core\Session::getInsatnce()->getLanguage()->getCode());
        
        return in_array($code, $this->allowedLanguages) ? $code : 'EN';
    }

    /**
     * Get country code 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCountryCode()
    {
        return strtoupper($this->getProfile()->getBillingAddress()->getCountry()->getCode3());

    }

    /**
     * Get currency code 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCurrencyCode()
    {
        return strtoupper($this->getOrder()->getCurrency()->getCode());
    }

    /**
     * Get post URL 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPostURL()
    {
        return $this->getSetting('test')
            ? 'http://www.moneybookers.com/app/test_payment.pl'
            : 'https://www.moneybookers.com/app/payment.pl';
    }    
}
