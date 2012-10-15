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
 * Abstract Paypal (iframe) processor
 *
 */
abstract class APaypal extends \XLite\Model\Payment\Base\Iframe
{
    /**
     * Request types definition
     */
    const REQ_TYPE_CREATE_SECURE_TOKEN = 'CreateSecureToken';
    const REQ_TYPE_CAPTURE             = 'Capture';
    const REQ_TYPE_VOID                = 'Void';
    const REQ_TYPE_CREDIT              = 'Credit';


    /**
     * iframeURL 
     * 
     * @var string
     */
    protected $iframeURL = 'https://payflowlink.paypal.com/';

    /**
     * API test URL 
     * 
     * @var string
     */
    protected $apiTestURL = 'https://pilot-payflowpro.paypal.com/';

    /**
     * API live URL 
     * 
     * @var string
     */
    protected $apiLiveURL = 'https://payflowpro.paypal.com/';

    /**
     * Partner code 
     * 
     * @var string
     */
    protected $partnerCode = 'LiteCommerce';


    /**
     * Cache of SecureTokenID
     * 
     * @var string
     */
    protected $secureTokenId = null;


    /**
     * getSettingsWidget 
     * 
     * @return string
     */
    public function getSettingsWidget()
    {
        return '\XLite\Module\CDev\Paypal\View\PaypalSettings';
    }

    /**
     * Return false to use own submit button on payment method settings form
     * 
     * @return boolean
     */
    public function useDefaultSettingsFormButton()
    {
        return false;
    }

    /**
     * Get allowed backend transactions
     *
     * @return string Status code
     */
    public function getAllowedTransactions()
    {
        return array(
            \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_CAPTURE,
            \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_VOID,
            \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_REFUND,
        );
    }

    /**
     * Get list of payment method settings which can be created
     * 
     * @return array
     */
    public function getAvailableSettings()
    {
        return array(
            'hide_instruction',
        );
    }

    /**
     * Get URL of referral page
     *
     * @return string
     */
    public function getPartnerPageURL(\XLite\Model\Payment\Method $method)
    {
        return \XLite::PRODUCER_SITE_URL . 'partners/paypal.html';
    }

    /**
     * Get URL of referral page
     *
     * @return string
     */
    public function getReferralPageURL(\XLite\Model\Payment\Method $method)
    {
        return $this->referralPageURL . $this->partnerCode;
    }

    /**
     * Prevent enabling Express Checkout if Paypal Standard is already enabled
     * 
     * @param \XLite\Model\Payment\Method $method Payment method object
     *  
     * @return boolean
     */
    public function canEnable(\XLite\Model\Payment\Method $method)
    {
        $result = parent::canEnable($method);

        if ($result && \XLite\Module\CDev\Paypal\Main::PP_METHOD_EC == $method->getServiceName()) {
            $m = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findOneBy(
                array(
                    'service_name' => \XLite\Module\CDev\Paypal\Main::PP_METHOD_PPS,
                )
            );
            $result = !($m && $m->isEnabled()) || $this->isForcedEnabled($method);
        }

        return $result;
    }

    /**
     * Get note with explanation why payment method can not be enabled
     * 
     * @param \XLite\Model\Payment\Method $method Payment method object
     *  
     * @return string
     */
    public function getForbidEnableNote(\XLite\Model\Payment\Method $method)
    {
        $result = parent::getForbidEnableNote($method);

        if (\XLite\Module\CDev\Paypal\Main::PP_METHOD_EC == $method->getServiceName()) {
            $result = 'This payment method cannot be enabled together with PayPal Payments Standard method';
        }

        return $result;
    }

    /**
     * Return true if current method is EC and PPA or PFL are enabled 
     * 
     * @param \XLite\Model\Payment\Method $method Payment method object
     *  
     * @return boolean
     */
    public function isForcedEnabled(\XLite\Model\Payment\Method $method)
    {
        $result = parent::isForcedEnabled($method);

        if (!$result && \XLite\Module\CDev\Paypal\Main::PP_METHOD_EC == $method->getServiceName()) {
            $parentMethod = $this->getParentMethod();
            $result = isset($parentMethod);
        }

        return $result;
    }

    /**
     * Get note with explanation why payment method was forcibly enabled
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getForcedEnabledNote(\XLite\Model\Payment\Method $method)
    {
        $result = parent::getForcedEnabledNote($method);

        if (!$result && \XLite\Module\CDev\Paypal\Main::PP_METHOD_EC == $method->getServiceName()) {
            $parentMethod = $this->getParentMethod();
            if (isset($parentMethod)) {
                $result = 'Must be enabled as you use PayPal Payments Advanced or PayPal Payflow Link';
            }
        }

        return $result;
    }

    /**
     * Do something when payment method is enabled 
     * 
     * @return void
     */
    public function enableMethod(\XLite\Model\Payment\Method $method)
    {
        $methods = array(
            \XLite\Module\CDev\Paypal\Main::PP_METHOD_PPA,
            \XLite\Module\CDev\Paypal\Main::PP_METHOD_PFL,
        );

        if (in_array($method->getServiceName(), $methods)) {
            $m = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findOneBy(
                array(
                    'service_name' => \XLite\Module\CDev\Paypal\Main::PP_METHOD_EC,
                )
            );
            if ($m) {
                $m->setAdded(true);
                $m->setEnabled(true);
            }
        }
    }

    /**
     * Get payment method which forced enabling of Express Checkout
     * 
     * @return \XLite\Model\Payment\Method
     */
    public function getParentMethod()
    {
        $result = null;

        $relatedMethods = array(
            \XLite\Module\CDev\Paypal\Main::PP_METHOD_PPA,
            \XLite\Module\CDev\Paypal\Main::PP_METHOD_PFL,
        );

        foreach ($relatedMethods as $rm) {
    
            $m = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findOneBy(
                array(
                    'service_name' => $rm,
                )
            );
    
            if ($m && $m->isEnabled()) {
                $result = $m;
                break;
            }
        }

        return $result;
    }

    /**
     * Get return type of the iframe-method: html redirect with destroying an iframe
     *
     * @return string
     */
    public function getReturnType()
    {
        return self::RETURN_TYPE_HTML_REDIRECT_WITH_IFRAME_DESTROYING;
    }

    /**
     * Get initial transaction type (used when customer places order)
     *
     * @param \XLite\Model\Payment\Method $method Payment method object OPTIONAL
     *
     * @return string
     */
    public function getInitialTransactionType($method = null)
    {
        return 'A' == ($method ? $method->getSetting('transaction_type') : $this->getSetting('transaction_type'))
            ? \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_AUTH
            : \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_SALE;
    }

    /**
     * Update status of backend transaction related to an initial payment transaction
     * 
     * @param \XLite\Model\Payment\Transaction $transaction Payment transaction
     * @param string                           $status      Transaction status
     *  
     * @return void
     */
    public function updateInitialBackendTransaction(\XLite\Model\Payment\Transaction $transaction, $status)
    {
        $backendTransaction = $transaction->getInitialBackendTransaction();

        if (isset($backendTransaction)) {
            $backendTransaction->setStatus($status);
            $this->saveDataFromRequest($backendTransaction);            
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
            && $method->getSetting('vendor')
            && $method->getSetting('pwd')
            && $this->isMerchantCountryAllowed();
    }

    /**
     * Return true if merchant country is allowed for this payment method
     *
     * @return boolean
     */
    public function isMerchantCountryAllowed()
    {
        return in_array(
            \XLite\Core\Config::getInstance()->Company->location_country,
            $this->getAllowedMerchantCountries()
        );
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

        \XLite\Module\CDev\Paypal\Main::addLog(
            'processReturn',
            \XLite\Core\Request::getInstance()->getData()
        );

        if (\XLite\Core\Request::getInstance()->cancel) {
            $this->setDetail(
                'status',
                'Payment transaction is cancelled',
                'Status'
            );
            $this->transaction->setNote('Payment transaction is cancelled');
            $this->transaction->setStatus($transaction::STATUS_FAILED);
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
        parent::processCallback($transaction);

        $request = \XLite\Core\Request::getInstance();


        if (!$request->isPost()) {
            // Callback request must be POST
            $this->markCallbackRequestAsInvalid(static::t('Request type must be POST'));

        } elseif (!isset($request->RESULT)) {

            if (\XLite\Module\CDev\Paypal\Model\Payment\Processor\PaypalIPN::getInstance()->isCallbackIPN()) {
                // If callback is IPN request from Paypal
                \XLite\Module\CDev\Paypal\Model\Payment\Processor\PaypalIPN::getInstance()
                    ->processCallbackIPN($transaction, $this);

            } else {
                // RESULT parameter must be presented in all callback requests
                $this->markCallbackRequestAsInvalid(static::t('\'RESULT\' argument not found'));
            }

        } else {

            $this->setDetail(
                'status',
                isset($request->RESPMSG) ? $request->RESPMSG : 'Unknown',
                'Status'
            );

            $this->saveDataFromRequest();

            if ('0' === $request->RESULT) {
                // Transaction successful if RESULT == '0'
                $status = $transaction::STATUS_SUCCESS;

            } else {
                $status = $transaction::STATUS_FAILED;
            }

            // Amount checking
            if (isset($request->AMT) && !$this->checkTotal($request->AMT)) {
                $status = $transaction::STATUS_FAILED;
            }

            \XLite\Module\CDev\Paypal\Main::addLog(
                'processCallback',
                array(
                    'request' => $request,
                    'status' => $status
                )
            );

            $this->transaction->setStatus($status);

            $this->updateInitialBackendTransaction($this->transaction, $status);

            $this->transaction->registerTransactionInOrderHistory('callback');
        }
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
     * Return true if Paypal response is a success transaction response 
     * 
     * @param array $response Response data
     *  
     * @return boolean
     */
    protected function isSuccessResponse($response)
    {
        $result = in_array($response['PENDINGREASON'], array('none', 'completed'));

        if (!$result) {
            $result = (
                'authorization' == $response['PENDINGREASON']
                && \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_AUTH == $this->transaction->getType()
            );
        }

        return $result;
    }


    /**
     * Get post URL 
     * 
     * @param string $postURL URL OPTIONAL
     * @param array  $params  Array of URL parameters OPTIONAL
     *  
     * @return string
     */
    protected function getPostURL($postURL = null, $params = array())
    {
        $apiURL = isset($postURL) ? $postURL : $this->getAPIURL();

        $args = !empty($params) ? '?' . implode('&', $params) : '';

        return $apiURL . $args;
    }

    /**
     * getAPIURL 
     * 
     * @return string
     */
    protected function getAPIURL()
    {
        return $this->isTestMode($this->transaction->getPaymentMethod()) ? $this->apiTestURL : $this->apiLiveURL;
    }

    /**
     * Return true if module is in test mode
     *
     * @param \XLite\Model\Payment\Method $method Payment method object
     *
     * @return boolean
     */
    public function isTestMode(\XLite\Model\Payment\Method $method)
    {
        return 'Y' == $method->getSetting('test');
    }

    /**
     * Get URL of the page to display within iframe
     *
     * @return string
     */
    protected function getIframeData()
    {
        $token = $this->getSecureToken();

        $result = $token ? $this->getPostURL($this->iframeURL, $this->getIframeParams($token)) : null;

        \XLite\Module\CDev\Paypal\Main::addLog(
            'getIframeData()',
            $result
        );

        return $result;
    }

    /**
     * Get iframe size 
     * 
     * @return array
     */
    protected function getIframeSize()
    {
        return array(610, 512);
    }

    /**
     * Returns the list of iframe URL arguments
     *
     * @return array
     */
    protected function getIframeParams($token)
    {
        $params = array(
            'SECURETOKEN=' . $token,
            'SECURETOKENID=' . $this->getSecureTokenId(),
        );

        if ($this->isTestMode($this->transaction->getPaymentMethod())) {
            $params[] = 'MODE=TEST';
        }

        return $params;
    }

    /**
     * Get SecureTokenId
     *
     * @return string
     */
    protected function getSecureTokenId()
    {
        if (!isset($this->secureTokenId)) {

            // Get secure token from transaction data

            if (!isset($this->secureTokenId)) {
                $this->secureTokenId = $this->generateSecureTokenId();
            }
        }

        return $this->secureTokenId;
    }

    /**
     * Generate random string for SecureTokenId
     *
     * @return string
     */
    protected function generateSecureTokenId()
    {
        return md5(time() + rand(1000, 99999));
    }

    /**
     * Do CREATESECURETOKEN request and get SECURETOKEN from Paypal 
     *
     * @return string
     */
    protected function getSecureToken()
    {
        $token = null;

        $this->transaction->setPublicId(
            $this->getSetting('prefix')
            . $this->transaction->getTransactionId()
        );

        $responseData = $this->doRequest(self::REQ_TYPE_CREATE_SECURE_TOKEN);

        if (!empty($responseData)) {

            if ($responseData['SECURETOKENID'] != $this->getSecureTokenId()) {
                // It seems, a hack attempt detected, log this

            } elseif (!empty($responseData['SECURETOKEN'])) {
                $token = $responseData['SECURETOKEN'];
            }
        }

        return $token;
    }

    /**
     * Do 'CAPTURE' request on Authorized transaction.
     * Returns true on success or false on failure
     *
     * @return boolean
     */
    protected function doCapture(\XLite\Model\Payment\BackendTransaction $transaction)
    {
        $result = false;

        $responseData = $this->doRequest(self::REQ_TYPE_CAPTURE, $transaction);

        if (!empty($responseData)) {

            $status = \XLite\Model\Payment\Transaction::STATUS_FAILED;

            if ('0' == $responseData['RESULT']) {
                $result = true;
                $status = \XLite\Model\Payment\Transaction::STATUS_SUCCESS;
                $transaction->getPaymentTransaction()->getOrder()->setStatus(\XLite\Model\Order::STATUS_PROCESSED);

                \XLite\Core\TopMessage::getInstance()->addInfo('Payment have been captured successfully');

            } else {
                \XLite\Core\TopMessage::getInstance()
                    ->addError('Transaction failure. PayPal response: ' . $responseData['RESPMSG']);
            }

            $transaction->setStatus($status);
            $transaction->update();

        }

        return $result;
    }

    /**
     * Do 'VOID' request.
     * Returns true on success or false on failure
     *
     * @return boolean
     */
    protected function doVoid(\XLite\Model\Payment\BackendTransaction $transaction)
    {
        $result = false;

        $responseData = $this->doRequest(self::REQ_TYPE_VOID, $transaction);

        if (!empty($responseData)) {

            $status = \XLite\Model\Payment\Transaction::STATUS_FAILED;

            if ('0' == $responseData['RESULT']) {
                $result = true;
                $status = \XLite\Model\Payment\Transaction::STATUS_SUCCESS;
                $transaction->getPaymentTransaction()->getOrder()->setStatus(\XLite\Model\Order::STATUS_DECLINED);

                \XLite\Core\TopMessage::getInstance()->addInfo('Payment have been voided successfully');

            } else {
                \XLite\Core\TopMessage::getInstance()
                    ->addError('Transaction failure. PayPal response: ' . $responseData['RESPMSG']);
            }

            $transaction->setStatus($status);
            $transaction->update();
        }

        return $result;
    }

    /**
     * Do 'CREDIT' request.
     * Returns true on success or false on failure
     *
     * @return boolean
     */
    protected function doRefund(\XLite\Model\Payment\BackendTransaction $transaction)
    {
        $result = false;

        $responseData = $this->doRequest(self::REQ_TYPE_CREDIT, $transaction);

        if (!empty($responseData)) {

            $status = \XLite\Model\Payment\Transaction::STATUS_FAILED;

            if ('0' == $responseData['RESULT']) {
                $result = true;
                $status = \XLite\Model\Payment\Transaction::STATUS_SUCCESS;
                $transaction->getPaymentTransaction()->getOrder()->setStatus(\XLite\Model\Order::STATUS_DECLINED);

                \XLite\Core\TopMessage::getInstance()->addInfo('Payment have been refunded successfully');

            } else {
                \XLite\Core\TopMessage::getInstance()
                    ->addError('Transaction failure. PayPal response: ' . $responseData['RESPMSG']);
            }

            $transaction->setStatus($status);
            $transaction->update();
        }

        return $result;
    }


    /**
     * Do HTTPS request to Paypal server with data set depended on $requestType.
     * Returns an array represented a parsed response from Paypal
     * 
     * @param string                                  $requestType Type of request 
     * @param \XLite\Model\Payment\BackendTransaction $transaction Backend transaction object OPTIONAL
     *  
     * @return array
     */
    protected function doRequest($requestType, $transaction = null)
    {
        $responseData = array();

        if (!isset($this->transaction)) {
            $this->transaction = $transaction;
        }

        $params = $this->getRequestParams($requestType, $transaction);

        $request = new \XLite\Core\HTTP\Request($this->getPostURL());
        $request->body = $params;
        $request->verb = 'POST';
        $response = $request->sendRequest();

        if (200 == $response->code && !empty($response->body)) {

            $responseData = $this->getParsedResponse($response->body);

            if (!empty($transaction) && !empty($responseData)) {
                $this->saveFilteredData($responseData, $transaction);
            }
        }

        \XLite\Module\CDev\Paypal\Main::addLog(
            'doRequest',
            array(
                'requestType'    => $requestType,
                'request'        => $request->body,
                'response'       => $response,
                'parsedResponse' => $responseData,
            )
        );

        return $responseData;
    }

    /**
     * Parse response from Paypal and return result as an array
     * e.g. the response "RESULT=0&SECURETOKEN=3DbhdANpkkkOZ8byxZtaRCQQ7&SECURETOKENID=82248f5c934f88466ab95965118f5ef1&RESPMSG=Approved"
     * will return an array:
     * array(
     *   "RESULT"        => "0",
     *   "SECURETOKEN"   => "3DbhdANpkkkOZ8byxZtaRCQQ7",
     *   "SECURETOKENID" => "82248f5c934f88466ab95965118f5ef1",
     *   "RESPMSG"       => "Approved",
     * );
     *
     * @return array
     */
    protected function getParsedResponse($response)
    {
        $result = array();

        $rows = explode('&', $response);

        if (is_array($rows)) {
            foreach ($rows as $row) {
                list($key, $value) = explode('=', $row); 
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Get array of params for CREATESCURETOKEN request
     *
     * @param string                                  $requestType Request type
     * @param \XLite\Model\Payment\BackendTransaction $transaction Backend transaction object OPTIONAL
     *
     * @return array
     */
    protected function getRequestParams($requestType, $transaction = null)
    {
        $methodName = 'get' . $requestType . 'RequestParams';

        // Get request params specific for request type 
        $postData = $this->$methodName($transaction) + $this->getCommonRequestParams();

        $data = array();

        foreach ($postData as $k => $v) {
            $data[] = sprintf('%s[%d]=%s', $k, strlen($v), $v);
        }

        $data = implode('&', $data);

        return $data;
    }

    /**
     * Get array of common params for all requests
     *
     * @return array
     */
    protected function getCommonRequestParams()
    {
        return array(
            'VENDOR' => $this->getSetting('vendor'),
            'USER' => $this->getSetting('user') ?: $this->getSetting('vendor'),
            'PWD' => $this->getSetting('pwd'),
            'PARTNER' => $this->getSetting('partner') ?: 'Paypal',
            'BUTTONSOURCE' => 'Qualiteam_Cart_LC_PHS',
            'VERBOSITY' => 'HIGH',
        );
    }

    /**
     * Get array of parameters for CREATESECURETOKEN request
     *
     * @return array
     */
    protected function getCreateSecureTokenRequestParams()
    {
        $order = $this->getOrder();

        $shippingModifier = $order->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING');

        if ($shippingModifier && $shippingModifier->canApply()) {
            $noShipping = '0';
            $freightAmt = $order->getCurrency()->roundValue(
                $order->getSurchargeSumByType(\XLite\Model\Base\Surcharge::TYPE_SHIPPING)
            );

        } else {
            $noShipping = '1';
            $freightAmt = 0;
        }

        $postData = array(
            'CREATESECURETOKEN' => 'Y',
            'SECURETOKENID'     => $this->getSecureTokenId(),
            'TRXTYPE'           => $this->getSetting('transaction_type'),
            'AMT'               => $order->getCurrency()->roundValue($this->transaction->getValue()),
            'BILLTOFIRSTNAME'   => $this->getProfile()->getBillingAddress()->getFirstname(),
            'BILLTOLASTNAME'    => $this->getProfile()->getBillingAddress()->getLastname(),
            'BILLTOSTREET'      => $this->getProfile()->getBillingAddress()->getStreet(),
            'BILLTOCITY'        => $this->getProfile()->getBillingAddress()->getCity(),
            'BILLTOSTATE'       => $this->getProfile()->getBillingAddress()->getState()->getCode(),
            'BILLTOZIP'         => $this->getProfile()->getBillingAddress()->getZipcode(),
            'BILLTOCOUNTRY'     => strtoupper($this->getProfile()->getBillingAddress()->getCountry()->getCode()),
            'ERRORURL'          => urldecode($this->getReturnURL(null, true)),
            'RETURNURL'         => urldecode($this->getReturnURL(null, true)),
            'CANCELURL'         => urldecode($this->getReturnURL(null, true, true)),
            'NOTIFYURL'         => $this->getCallbackURL(null, true),
            'RETURNURLMETHOD'   => 'POST', // Set the return method for approved transactions (RETURNURL)
            'URLMETHOD'         => 'POST', // Set the return method for cancelled and failed transactions (ERRORURL, CANCELURL)
            'TEMPLATE'          => 'MINLAYOUT', // This enables an iframe layout
            'INVNUM'            => $order->getOrderId(),
            'BILLTOPHONENUM'    => $this->getProfile()->getBillingAddress()->getPhone(),
            'BILLTOEMAIL'       => $this->getProfile()->getLogin(),
            'ADDROVERRIDE'      => '1',
            'NOSHIPPING'        => $noShipping,
            'FREIGHTAMT'        => $freightAmt,
            'HANDLINGAMT'       => 0,
            'INSURANCEAMT'      => 0,
            'SILENTPOST'        => 'TRUE',
            'SILENTPOSTURL'     => urldecode($this->getCallbackURL(null, true)),
            'FORCESILENTPOST'   => 'FALSE',
            'DISABLERECEIPT'    => 'TRUE', // Warning! If set this to 'FALSE' Paypal will redirect buyer to cart.php without target, txnId and other service parameters
            'CURRENCY'          => $this->getCurrencyCode(),
        );

        if ('1' != $noShipping) {
            $postData += array(
                'SHIPTOPHONENUM'    => $this->getProfile()->getShippingAddress()->getPhone(),
                'SHIPTOFIRSTNAME'   => $this->getProfile()->getShippingAddress()->getFirstname(),
                'SHIPTOLASTNAME'    => $this->getProfile()->getShippingAddress()->getLastname(),
                'SHIPTOSTREET'      => $this->getProfile()->getShippingAddress()->getStreet(),
                'SHIPTOCITY'        => $this->getProfile()->getShippingAddress()->getCity(),
                'SHIPTOSTATE'       => $this->getProfile()->getShippingAddress()->getState()->getCode(),
                'SHIPTOZIP'         => $this->getProfile()->getShippingAddress()->getZipcode(),
                'SHIPTOCOUNTRY'     => $this->getProfile()->getShippingAddress()->getCountry()->getCode(),
                'SHIPTOEMAIL'       => $this->getProfile()->getLogin(),
            );
        }

        $postData = $postData + $this->getLineItems();

        return $postData;
    }

    /**
     * Get array of params for CREATESECURETOKEN request (ordered products part)
     *
     * @param \XLite\Model\Cart $cart Cart object OPTIONAL
     *
     * @return string
     */
    protected function getLineItems($cart = null)
    {
        $lineItems = array();

        $itemsSubtotal  = 0;
        $itemsTaxAmount = 0;

        $obj = isset($cart) ? $cart : $this->getOrder();

        $items = $obj->getItems();

        if (!empty($items)) {
            $index = 0;

            // Prepare data about ordered products

            foreach ($items as $item) {
                $lineItems['L_COST' . $index] = $obj->getCurrency()->roundValue($item->getPrice());
                $lineItems['L_NAME' . $index] = $item->getProduct()->getTranslation()->name;
                if ($item->getProduct()->getSku()) {
                    $lineItems['L_SKU' . $index] = $item->getProduct()->getSku();
                }
                $lineItems['L_QTY' . $index] = $item->getAmount();
                $itemsSubtotal += $lineItems['L_COST' . $index] * $lineItems['L_QTY' . $index];
                $index += 1;
            }

            // Prepare data about discount

            $discount = $obj->getCurrency()->roundValue(
                $obj->getSurchargeSumByType(\XLite\Model\Base\Surcharge::TYPE_DISCOUNT)
            );

            if (0 != $discount) {
                $lineItems['L_COST' . $index] = $discount;
                $lineItems['L_NAME' . $index] = 'Discount';
                $lineItems['L_QTY' . $index] = 1;
                $itemsSubtotal += $discount;
            }

            $lineItems += array('ITEMAMT' => $itemsSubtotal);

            // Prepare data about summary tax cost

            $taxCost = $obj->getCurrency()->roundValue(
                $obj->getSurchargeSumByType(\XLite\Model\Base\Surcharge::TYPE_TAX)
            );

            if (0 < $taxCost) {
                $lineItems['L_TAXAMT' . $index] = $taxCost;
                $lineItems['TAXAMT'] = $taxCost;
            }
        }

        return $lineItems;
    }

    /**
     * Get currency code
     *
     * @return string
     */
    protected function getCurrencyCode()
    {
        return strtoupper($this->getOrder()->getCurrency()->getCode());
    }

    /**
     * Return array of parameters for 'CAPTURE' request 
     *
     * @return array
     */
    protected function getInquiryRequestParams()
    {
        $params = array(
            'TRXTYPE'       => 'I',
            'SECURETOKEN'   => $this->getSecureToken(),
            'SECURETOKENID' => $this->getSecureTokenId(),
            'VERBOSITY'     => 'HIGH',
        );

        return $params;
    }

    /**
     * Return array of parameters for 'CAPTURE' request 
     *
     * @return array
     */
    protected function getCaptureRequestParams(\XLite\Model\Payment\BackendTransaction $transaction)
    {
        $params = array(
            'TRXTYPE' => 'D',
            'ORIGID'  => $this->getTransactionReferenceId($transaction),
            'AMT'     => $transaction->getPaymentTransaction()->getOrder()->getCurrency()->roundValue($transaction->getValue()),
            'CAPTURECOMPLETE' => 'Y', // For Paypal Payments Advanced only
        );

        return $params;
    }

    /**
     * Get reference ID field name for backend transactions
     * 
     * @return string
     */
    protected function getReferenceIdField()
    {
        return 'PNREF';
    }

    /**
     * Get reference ID of parent transaction
     * (e.g. get PNREF of AUTH transaction for request a CAPTURE transaction)
     *
     * @param \XLite\Model\Payment\BackendTransaction $backendTransaction Backend transaction object
     *
     * @return string
     */
    protected function getTransactionReferenceId(\XLite\Model\Payment\BackendTransaction $backendTransaction)
    {
        $referenceId = null;

        $paymentTransaction = $backendTransaction->getPaymentTransaction();

        switch ($backendTransaction->getType()) {

            case \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_CAPTURE:
            case \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_VOID:

                if (\XLite\Model\Payment\BackendTransaction::TRAN_TYPE_AUTH == $paymentTransaction->getType()) {
                    $referenceId = $paymentTransaction->getDataCell($this->getReferenceIdField())->getValue();
                }

                break;

            case \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_REFUND:

                if (\XLite\Model\Payment\BackendTransaction::TRAN_TYPE_SALE == $paymentTransaction->getType()) {
                    $referenceId = $paymentTransaction->getDataCell($this->getReferenceIdField())->getValue();

                } elseif ($paymentTransaction->isCaptured()) {

                    foreach ($paymentTransaction->getBackendTransactions() as $bt) {

                        if (
                            \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_CAPTURE == $bt->getType()
                            && \XLite\Model\Payment\Transaction::STATUS_SUCCESS == $bt->getStatus()
                        ) {
                            $referenceId = $bt->getDataCell($this->getReferenceIdField())->getValue();
                            break;
                        }
                    }
                }

                break;

            default:
                // No default actions
        }

        return $referenceId;
    }

    /**
     * Return array of parameters for 'VOID' request 
     *
     * @return array
     */
    protected function getVoidRequestParams(\XLite\Model\Payment\BackendTransaction $transaction)
    {
        $params = array(
            'TRXTYPE' => 'V',
            'ORIGID'  => $this->getTransactionReferenceId($transaction),
        );

        return $params;
    }

    /**
     * Return array of parameters for 'CREDIT' request 
     *
     * @return array
     */
    protected function getCreditRequestParams(\XLite\Model\Payment\BackendTransaction $transaction)
    {
        $params = array(
            'TRXTYPE' => 'C',
            'ORIGID'  => $this->getTransactionReferenceId($transaction),
            'AMT'     => $transaction->getPaymentTransaction()->getOrder()->getCurrency()->roundValue($transaction->getValue()),
        );

        return $params;
    }

    /**
     * Define saved into transaction data schema
     *
     * @return array
     */
    protected function defineSavedData()
    {
        $data = parent::defineSavedData();

        $data['TRANSTIME'] = 'Transaction timestamp';
        $data['PNREF']     = 'Unique Payflow transaction ID (PNREF)'; 
        $data['PPREF']     = 'Unique PayPal transaction ID (PPREF)'; // PPA and PL
        $data['TYPE']      = 'Transaction type'; // PL
        $data['TRXTYPE']   = 'Transaction type'; // PPA and EC
        $data['RESULT']    = 'Transaction result code (RESULT)';
        $data['RESPMSG']   = 'Transaction result message (RESPMSG)';

        $data['CORRELATIONID'] = 'Tracking ID'; // PPA and EC
        $data['FEEAMT']        = 'Transaction fee'; // EC
        $data['PENDINGREASON'] = 'Pending reason'; // EC

        return $data;
    }
}
