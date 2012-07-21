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

namespace XLite\Module\CDev\Paypal\Model\Payment\Processor;

/**
 * Abstract Paypal (iframe) processor
 *
 * @see   ____class_see____
 * @since 1.0.0
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
     * @var   string
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $iframeURL = 'https://payflowlink.paypal.com/';

    /**
     * API test URL 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $apiTestURL = 'https://pilot-payflowpro.paypal.com/';

    /**
     * API live URL 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $apiLiveURL = 'https://payflowpro.paypal.com/';

    /**
     * Cache of SecureTokenID
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $secureTokenId = null;


    /**
     * getSettingsWidget 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.1.0
     */
    public function getSettingsWidget()
    {
        return '\XLite\Module\CDev\Paypal\View\PaypalSettings';
    }

    public function getPaypalMethodCode()
    {
        return self::PAYPAL_PAYMENT_METHOD_CODE;
    }

    /**
     * Get allowed backend transactions
     *
     * @return string Status code
     * @see    ____func_see____
     * @since  1.0.0
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
     * Get return type of the iframe-method: html redirect with destroying an iframe
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getReturnType()
    {
        return self::RETURN_TYPE_HTML_REDIRECT_WITH_IFRAME_DESTROYING;
    }

    protected function isSuccessResponse($response)
    {
        $result = in_array($response['PENDINGREASON'], array('none', 'completed'));

        if (!$result) {
            $result = ('authorization' == $response['PENDINGREASON'] && \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_AUTH == $this->transaction->getType());
        }

        return $result;
    }

    /**
     * Get initial transaction type (used when customer places order)
     *
     * @param \XLite\Model\Payment\Method $method Payment method object
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getInitialTransactionType($method = null)
    {
        return 'A' == ($method ? $method->getSetting('transaction_type') : $this->getSetting('transaction_type'))
            ? \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_AUTH
            : \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_SALE;
    }


    /**
     * Get post URL 
     * 
     * @param string $postURL URL OPTIONAL
     * @param array  $params  Array of URL parameters OPTIONAL
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getAPIURL()
    {
        return $this->isTestMode() ? $this->apiTestURL : $this->apiLiveURL;
    }

    /**
     * Return true if module is in test mode
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isTestMode()
    {
        return 'Y' == $this->getSetting('test');
    }

    /**
     * Get URL of the page to display within iframe
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getIframeSize()
    {
        return array(600, 500);
    }

    /**
     * Returns the list of iframe URL arguments
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function generateSecureTokenId()
    {
        return md5(time() + rand(1000, 99999));
    }

    /**
     * Do CREATESECURETOKEN request and get SECURETOKEN from Paypal 
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
                \XLite\Core\TopMessage::getInstance()->addError('Transaction failure. Paypal response: ' . $responseData['RESPMSG']);
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
     * @see    ____func_see____
     * @since  1.0.0
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
                \XLite\Core\TopMessage::getInstance()->addError('Transaction failure. Paypal response: ' . $responseData['RESPMSG']);
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
     * @see    ____func_see____
     * @since  1.0.0
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

                \XLite\Core\TopMessage::getInstance()->addInfo('Payment have been refunded successfully');

            } else {
                \XLite\Core\TopMessage::getInstance()->addError('Transaction failure. Paypal response: ' . $responseData['RESPMSG']);
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
     * @param \XLite\Model\Payment\BackendTransaction $transaction Backend transaction object
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function doRequest($requestType, $transaction = null, $customParams = array())
    {
        $responseData = array();

        if (!isset($this->transaction)) {
            $this->transaction = $transaction;
        }

        $params = $this->getRequestParams($requestType, $transaction, $customParams);

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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @param \XLite\Model\Payment\BackendTransaction $transaction Backend transaction object
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequestParams($requestType, $transaction = null, $customParams = array())
    {
        $methodName = 'get' . $requestType . 'RequestParams';

        $postData = $this->$methodName($transaction, $customParams) + $this->getCommonRequestParams();

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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCreateSecureTokenRequestParams()
    {
        $shippingModifier = $this->getOrder()->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING');

        $postData = array(
            'CREATESECURETOKEN' => 'Y',
            'SECURETOKENID'     => $this->getSecureTokenId(),
            'TRXTYPE'           => $this->getSetting('transaction_type'),
            'AMT'               => $this->getOrder()->getCurrency()->roundValue($this->transaction->getValue()),
            'BILLTOFIRSTNAME'   => $this->getProfile()->getBillingAddress()->getFirstname(),
            'BILLTOLASTNAME'    => $this->getProfile()->getBillingAddress()->getLastname(),
            'BILLTOSTREET'      => $this->getProfile()->getBillingAddress()->getStreet(),
            'BILLTOCITY'        => $this->getProfile()->getBillingAddress()->getCity(),
            'BILLTOSTATE'       => $this->getProfile()->getBillingAddress()->getState()->getCode(),
            'BILLTOZIP'         => $this->getProfile()->getBillingAddress()->getZipcode(),
            'BILLTOCOUNTRY'     => strtoupper($this->getProfile()->getBillingAddress()->getCountry()->getCode3()),
            'ERRORURL'          => urldecode($this->getReturnURL(null, true)),
            'RETURNURL'         => urldecode($this->getReturnURL(null, true)),
            'CANCELURL'         => urldecode($this->getReturnURL(null, true, true)),
            'RETURNURLMETHOD'   => 'POST', // Set the return method for approved transactions (RETURNURL)
            'URLMETHOD'         => 'POST', // Set the return method for cancelled and failed transactions (ERRORURL, CANCELURL)
            'TEMPLATE'          => 'MINLAYOUT', // This enables an iframe layout
            'INVNUM'            => $this->getOrder()->getOrderId(),
            'BILLTOPHONENUM'    => $this->getProfile()->getBillingAddress()->getPhone(),
            'BILLTOEMAIL'       => $this->getProfile()->getLogin(),
            'SHIPTOPHONENUM'    => $this->getProfile()->getShippingAddress()->getPhone(),
            'SHIPTOFIRSTNAME'   => $this->getProfile()->getShippingAddress()->getFirstname(),
            'SHIPTOLASTNAME'    => $this->getProfile()->getShippingAddress()->getLastname(),
            'SHIPTOSTREET'      => $this->getProfile()->getShippingAddress()->getStreet(),
            'SHIPTOCITY'        => $this->getProfile()->getShippingAddress()->getCity(),
            'SHIPTOSTATE'       => $this->getProfile()->getShippingAddress()->getState()->getCode(),
            'SHIPTOZIP'         => $this->getProfile()->getShippingAddress()->getZipcode(),
            'SHIPTOCOUNTRY'     => $this->getProfile()->getShippingAddress()->getCountry()->getCode3(),
            'SHIPTOEMAIL'       => $this->getProfile()->getLogin(),
            'ADDROVERRIDE'      => 'Y',
            'NOSHIPPING'        => $shippingModifier && $shippingModifier->canApply() ? '1' : '0',
            'SILENTPOST'        => 'TRUE',
            'SILENTPOSTURL'     => urldecode($this->getCallbackURL(null, true)),
            // 'SILENTPOSTRETURNURL' => $this->getCallbackURL(null, true),
            'FORCESILENTPOST'   => 'FALSE',
            'DISABLERECEIPT'    => 'TRUE', // Warning! If set this to 'FALSE' Paypal will redirect buyer to cart.php without target, txnId and other service parameters
            'CURRENCY'          => $this->getCurrencyCode(),
        );

        $postData = $postData + $this->getLineItems();

        return $postData;
    }

    /**
     * Get array of params for CREATESECURETOKEN request (ordered products part)
     *
     * @param $cart Cart object
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLineItems($cart = null)
    {
        $lineItems = array();

        $itemsSubtotal  = 0;
        $itemsTaxAmount = 0;

        $obj = isset($cart) ? $cart : $this->getOrder();

        $items = $obj->getItems();

        //var_dump(is_array($items));

        if (!empty($items)) {
            $index = 0;

            foreach ($items as $item) {
                $lineItems['L_COST' . $index] = $obj->getCurrency()->roundValue($item->getPrice());
                $lineItems['L_NAME' . $index] = $item->getProduct()->getTranslation()->name;
                $lineItems['L_SKU' . $index] = $item->getProduct()->getSku();
                $lineItems['L_QTY' . $index] = $item->getAmount();
                //$lineItems['L_TAXAMT' . $index] = $this->getOrder()->getCurrency()->roundValue($item->getTaxValue());
                $itemsSubtotal += $lineItems['L_COST' . $index] * $lineItems['L_QTY' . $index];
                //$itemsTaxAmount += $lineItems['L_TAXAMT' . $index] * $lineItems['L_QTY' . $index];
                $index ++;
            }

            $lineItems += array('ITEMAMT' => $itemsSubtotal);

            if ($itemsTaxAmount > 0) {
                $lineItems += array('TAXAMT'  => $itemsTaxAmount);
            }
        }

        return $lineItems;
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
     * Return array of parameters for 'CAPTURE' request 
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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

    protected function getReferenceIdField()
    {
        return 'PNREF';
    }

    /**
     * Get reference ID of parent transaction
     * (e.g. get PNREF of AUTH transaction for request a CAPTURE transaction)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
                        if (\XLite\Model\Payment\BackendTransaction::TRAN_TYPE_CAPTURE == $bt->getType() && \XLite\Model\Payment\Transaction::STATUS_SUCCESS == $bt->getStatus()) {
                            $referenceId = $bt->getDataCell($this->getReferenceIdField())->getValue();
                            break;
                        }
                    }
                }

        }

        return $referenceId;
    }

    /**
     * Return array of parameters for 'VOID' request 
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * Update status of backend transaction related to an initial payment transaction
     * 
     * @param \XLite\Model\Payment\Transaction $transaction Payment transaction
     * @param string                           $status      Transaction status
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.1.0
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
     * Define saved into transaction data schema
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
            && $method->getSetting('vendor')
            && $method->getSetting('pwd')
            && $this->isMerchantCountryAllowed();
    }

    /**
     * Return true if merchant country is allowed for this payment method
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function processCallback(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processCallback($transaction);

        $request = \XLite\Core\Request::getInstance();

        if (!$request->isPost()) {
            // Callback request must be POST
            $this->markCallbackRequestAsInvalid(static::t('Request type must be POST'));

        } elseif (!isset($request->RESULT)) {
            // RESULT parameter must be presented in all callback requests
            $this->markCallbackRequestAsInvalid(static::t('\'RESULT\' argument not found'));

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
        }
    }
}
