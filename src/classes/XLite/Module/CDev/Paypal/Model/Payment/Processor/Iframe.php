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
abstract class Iframe extends \XLite\Model\Payment\Base\Iframe
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
        return \XLite\Core\Config::getInstance()->CDev->Paypal->test;
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

        if ($this->isTestMode()) {
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
            \XLite\Core\Config::getInstance()->CDev->Paypal->prefix
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
    protected function doCapture()
    {
        $result = false;

        $responseData = $this->doRequest(self::REQ_TYPE_CAPTURE);

        if (!empty($responseData)) {

            if ('0' == $responseData['RESULT']) {
                $result = true;
            }
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
    protected function doVoid()
    {
        $result = false;

        $responseData = $this->doRequest(self::REQ_TYPE_VOID);

        if (!empty($responseData)) {

            if ('0' == $responseData['RESULT']) {
                $result = true;
            }
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
    protected function doCredit()
    {
        $result = false;

        $responseData = $this->doRequest(self::REQ_TYPE_CREDIT);

        if (!empty($responseData)) {

            if ('0' == $responseData['RESULT']) {
                $result = true;
            }
        }

        return $result;
    }


    /**
     * Do HTTPS request to Paypal server with data set depended on $requestType.
     * Returns an array represented a parsed response from Paypal
     * 
     * @param string $requestType Type of request 
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function doRequest($requestType)
    {
        $responseData = array();

        $params = $this->getRequestParams($requestType);

        $request = new \XLite\Core\HTTP\Request($this->getPostURL());
        $request->body = $params;
        $request->verb = 'POST';
        $response = $request->sendRequest();

        if (200 == $response->code && !empty($response->body)) {
            $responseData = $this->getParsedResponse($response->body);
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
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequestParams($requestType)
    {
        $methodName = 'get' . $requestType . 'RequestParams';

        $postData = $this->$methodName() + $this->getCommonParams();

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
            'VENDOR' => \XLite\Core\Config::getInstance()->CDev->Paypal->vendor,
            'USER' => \XLite\Core\Config::getInstance()->CDev->Paypal->user ?: \XLite\Core\Config::getInstance()->CDev->Paypal->vendor,
            'PWD' => \XLite\Core\Config::getInstance()->CDev->Paypal->pwd,
            'PARTNER' => \XLite\Core\Config::getInstance()->CDev->Paypal->partner ?: 'Paypal',
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
        $postData = array(
            'CREATESECURETOKEN' => 'Y',
            'SECURETOKENID'     => $this->getSecureTokenId(),
            'TRXTYPE'           => \XLite\Core\Config::getInstance()->CDev->Paypal->transaction_type,
            'AMT'               => $this->getOrder()->getCurrency()->roundValue($this->transaction->getValue()),
            'ITEMAMT'           => $this->getLineItems($lineItems),
            'BILLTOFIRSTNAME'   => $this->getProfile()->getBillingAddress()->getFirstname(),
            'BILLTOLASTNAME'    => $this->getProfile()->getBillingAddress()->getLastname(),
            'BILLTOSTREET'      => $this->getProfile()->getBillingAddress()->getStreet(),
            'BILLTOCITY'        => $this->getProfile()->getBillingAddress()->getCity(),
            'BILLTOSTATE'       => $this->getProfile()->getBillingAddress()->getState()->getCode(),
            'BILLTOZIP'         => $this->getProfile()->getBillingAddress()->getZipcode(),
            'BILLTOCOUNTRY'     => strtoupper($this->getProfile()->getBillingAddress()->getCountry()->getCode3()),
            'ERRORURL'          => urldecode($this->getReturnURL(null, true, true)),
            'RETURNURL'         => urldecode($this->getReturnURL(null, true)),
            'RETURNURLMETHOD'   => 'POST',
            'CANCELURL'         => urldecode($this->getReturnURL(null, true, true)),
            // 'NOTIFYURL', // For Express Checkout only
            // 'HDRIMG', // The URL for an image to be used as the header image for the PayPal Express Checkout pages
            // 'PAYFLOWCOLOR', // The secondary gradient color for the order summary section of the PayPal Express Checkout pages
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
            'ADDROVERRIDE'      => 'N',
            'NOSHIPPING'        => 1,
            'SILENTPOST'        => 'TRUE',
            'SILENTPOSTURL'     => urldecode($this->getCallbackURL(null, true)),
            // 'SILENTPOSTRETURNURL' => $this->getCallbackURL(null, true),
            'FORCESILENTPOST'   => 'FALSE',
            'DISABLERECEIPT'    => 'TRUE', // Warning! If set this to 'FALSE' Paypal will redirect buyer to cart.php without target, txnId and other service parameters
            'CURRENCY'          => $this->getCurrencyCode(),
        );

        $postData = $postData + $lineItems;

        return $postData;
    }

    /**
     * Get array of params for CREATESECURETOKEN request (ordered products part)
     *
     * @param &$lineItems Reference to an array of ordered items
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLineItems(&$lineItems)
    {
        $lineItems = array();
        $itemsSubtotal = 0;

        $items = $this->getOrder()->getItems();

        if (!empty($items) && is_array($items)) {
            $index = 0;
            foreach ($items as $item) {
                $lineItems['L_COST' . $index] = $this->getOrder()->getCurrency()->roundValue($item->getPrice());
                $lineItems['L_NAME' . $index] = $item->getProduct()->getTranslation()->name;
                $lineItems['L_SKU' . $index] = $item->getProduct()->getSku();
                $lineItems['L_QTY' . $index] = $item->getAmount();
                $itemsSubtotal = $lineItems['L_COST' . $index] * $lineItems['L_QTY' . $index];
                $index ++;
            }
        }

        return $itemsSubtotal;
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
    protected function getCaptureRequestParams()
    {
        $params = array(
            'TRXTYPE' => 'D',
            'ORIGID'  => $this->getTransaction()->getData('PNREF'),
            'AMT'     => $this->getTransaction()->getValue(),
            'CAPTURECOMPLETE' => 'Y', // For Paypal Payments Advanced only
        );

        return $params;
    }

    /**
     * Return array of parameters for 'VOID' request 
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getVoidRequestParams()
    {
        $params = array(
            'TRXTYPE' => 'V',
            'ORIGID'  => $this->getTransaction()->getData('PNREF'),
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
    protected function getCreditRequestParams()
    {
        $params = array(
            'TRXTYPE' => 'C',
            'ORIGID'  => $this->getTransaction()->getData('PNREF'),
            'AMT'     => $this->getTransaction()->getValue(),
        );

        return $params;
    }
}
