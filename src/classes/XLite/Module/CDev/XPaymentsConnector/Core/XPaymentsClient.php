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

namespace XLite\Module\CDev\XPaymentsConnector\Core;

/**
 * XPayments client
 *
 */
class XPaymentsClient extends \XLite\Base\Singleton
{
    const XPC_TRAN_TYPE_SALE          = 'sale';
    const XPC_TRAN_TYPE_AUTH          = 'auth';
    const XPC_TRAN_TYPE_CAPTURE       = 'capture';
    const XPC_TRAN_TYPE_CAPTURE_PART  = 'capturePart';
    const XPC_TRAN_TYPE_CAPTURE_MULTI = 'captureMulti';
    const XPC_TRAN_TYPE_VOID          = 'void';
    const XPC_TRAN_TYPE_VOID_PART     = 'voidPart';
    const XPC_TRAN_TYPE_VOID_MULTI    = 'voidMulti';
    const XPC_TRAN_TYPE_REFUND        = 'refund';
    const XPC_TRAN_TYPE_REFUND_PART   = 'refundPart';
    const XPC_TRAN_TYPE_REFUND_MULTI  = 'refundMulti';
    const XPC_TRAN_TYPE_GET_INFO      = 'getInfo';
    const XPC_TRAN_TYPE_ACCEPT        = 'accept';
    const XPC_TRAN_TYPE_DECLINE       = 'decline';
    const XPC_TRAN_TYPE_TEST          = 'test';

    const REQ_CURL    = 1;
    const REQ_OPENSSL = 2;
    const REQ_DOM     = 4;

    const XPC_SYSERR_CARTID      = 1;
    const XPC_SYSERR_URL         = 2;
    const XPC_SYSERR_PUBKEY      = 4;
    const XPC_SYSERR_PRIVKEY     = 8;
    const XPC_SYSERR_PRIVKEYPASS = 16;

    const XPC_WPP_DP   = 'PayPal WPP Direct Payment';
    const XPC_WPPPE_DP = 'PayPal WPPPE Direct Payment';

    const XPC_API_EXPIRED = 506;
    const XPC_API_VERSION = '1.1';

    // Salt block length
    const XPC_SALT_LENGTH = 32;

    // Salt generator start character code
    const XPC_SALT_BEGIN = 33;

    // Salt generator end character code
    const XPC_SALT_END = 255;

    // Encryption check length
    const XPC_CHUNK_LENGTH = 128;

    // Root-level tag for all XML messages
    const XPC_TAG_ROOT = 'data';

    // Value of the 'type' attribute for list items in XML
    const XPC_TYPE_CELL = 'cell';

    const XPC_MODULE_INFO = 'payment_module';

    const DEFAULT_CHARSET = 'UTF-8';

    /**
     * Paypal dp solutions
     *
     * @var array
     */
    protected $xpcPaypalDpSolutions = array(
        'pro' => self::XPC_WPP_DP, 
        'uk'  => self::XPC_WPPPE_DP
    );

    /**
     * Errors 
     *
     * @var array 
     */
    protected $xpcErrors = array(
        self::XPC_API_EXPIRED => 'To update your X-Payments connector module download the file xpc_api.php from the File Area of your Qualiteam account and copy it to the <xcart_dir>/modules/XPayments_Connector/ directory, replacing the existing file.'
    );

    /**
     * Check - module is configured or not
     *
     * @return boolean
     */
    public function isModuleConfigured()
    {
        return 0 === $this->getModuleSystemErrors();
    }

    /**
     * Make test request to X-Payments
     *
     * @return boolean
     */
    public function requestTest()
    {
        srand();
    
        // Make test request
        list($status, $response) = $this->getApiRequest(
            'connect',
            'test',
            array('testCode' => ($hashCode = strval(rand(0, 1000000)))),
            $this->getRequestTestSchema()
        );
    
        // Compare MD5 hashes
        if ($status) {
            $status = md5($hashCode) === $response['hashCode'];
            if (!$status) {
                $this->getApiError('Test connection data is not valid');
            }
        }
    
        return array(
            'status'   => $status,
            'response' => $response,
        );
    }

    /**
     * Get payment info
     *
     * @param integer $txn_id  ransaction id
     * @param boleean $refresh Refresh OPTIONAL
     *
     * @return array Operation status & payment data array
     */
    function requestPaymentInfo($txn_id, $refresh = false)
    {
        $data = array(
            'txnId' => $txn_id,
            'refresh' => $refresh ? 1 : 0
        );
    
        list($status, $response) = $this->getApiRequest('payment', 'get_info', $data);
    
        if ($status) {
            if (!is_array($response) || !isset($response['status'])) {
                $this->getApiError('GetInfo request. Server response has not status');
                $status = false;
    
            } elseif (!isset($response['message'])) {
                $this->getApiError('GetInfo request. Server response has not message');
                $status = false;
    
            } elseif (!isset($response['transactionInProgress'])) {
                $this->getApiError('GetInfo request. Server response has not transaction progress status');
                $status = false;
    
            } elseif (!isset($response['isFraudStatus'])) {
                $this->getApiError('GetInfo request. Server response has not fraud filter status');
                $status = false;
    
            } elseif (!isset($response['currency']) || strlen($response['currency']) != 3) {
                $this->getApiError('GetInfo request. Server response has not currency code or currency code has wrong format');
                $status = false;
    
            } elseif (!isset($response['amount'])) {
                $this->getApiError('GetInfo request. Server response has not payment amount');
                $status = false;
    
            } elseif (!isset($response['capturedAmount'])) {
                $this->getApiError('GetInfo request. Server response has not captured amount');
                $status = false;
    
            } elseif (!isset($response['capturedAmountAvail'])) {
                $this->getApiError('GetInfo request. Server response has not available for capturing amount');
                $status = false;
    
            } elseif (!isset($response['refundedAmount'])) {
                $this->getApiError('GetInfo request. Server response has not refunded amount');
                $status = false;
    
            } elseif (!isset($response['refundedAmountAvail'])) {
                $this->getApiError('GetInfo request. Server response has not available for refunding amount');
                $status = false;
    
            } elseif (!isset($response['voidedAmount'])) {
                $this->getApiError('GetInfo request. Server response has not voided amount');
                $status = false;
    
            } elseif (!isset($response['voidedAmountAvail'])) {
                $this->getApiError('GetInfo request. Server response has not available for cancelling amount');
                $status = false;
    
            }
        }
    
        return array($status, $response);
    }

    /**
     * Get list of available payment configurations from X-Payments 
     *
     * @return array
     */
    public function requestPaymentMethods()
    {
        list($status, $response) = $this->getApiRequest(
            'payment_confs',
            'get',
            array(),
            $this->getRequestPaymentMethodsSchema()
        );

        if ($status) {
            if (!isset($response['payment_module']) || !is_array($response['payment_module'])) {
                $status = array();

            } else {
                $status = $response['payment_module'];
            }
        }

        return $status;
    }

    /**
     * Send request to X-Payments to initialize new payment
     *
     * @param \XLite\Model\Payment\Method $paymentMethod Payment method
     * @param integer                     $refId         Transaction ID
     * @param \XLite\Model\Cart           $cart          Shopping cart info
     * @param boolean                     $forceAuth     Force enable AUTH mode
     *
     * @return array
     */
    public function requestPaymentInit(\XLite\Model\Payment\Method $paymentMethod, $refId, \XLite\Model\Cart $cart, $forceAuth)
    {
        $config = \XLite\Core\Config::getInstance()->CDev->XPaymentsConnector;
    
        // Prepare cart
        $preparedCart = $this->prepareCart($cart, $refId, $forceAuth);
    
        if (!$cart) {
            return $this->getApiError('Unable to prepare cart data');
        }
    
        // Data to send to X-Payments
        $data = array(
            'confId'      => intval($paymentMethod->getSetting('id')),
            'refId'       => $refId,
            'cart'        => $preparedCart,
            'language'    => 'en',
            'returnUrl'   => \XLite::getInstance()->getShopUrl(
                \XLite\Core\Converter::buildUrl(
                    'payment_return',
                    'return',
                    array('order_id' => $cart->getOrderId())
                )
            ),
            'callbackUrl' => \XLite::getInstance()->getShopUrl(
                \XLite\Core\Converter::buildUrl(
                    'callback',
                    'callback',
                    array('order_id' => $cart->getOrderId())
                )
            ),

        );

        list($status, $response) = $this->getApiRequest(
            'payment',
            'init',
            $data,
            $this->getRequestInitSchema()
        );
    
        // The main entry in the response is the 'token'
        if (
            $status
            && (
                !isset($response['token'])
                || !is_string($response['token'])
            )
        ) {
    
            $this->getApiError('Transaction token is not found or has a wrong type');
    
            $status = false;
    
        }
    
        if ($status) {
    
            // Use the default URL if X-Payments did not return one
            if (substr($config->xpc_xpayments_url, -1) == '/') {
                $config->xpc_xpayments_url = substr($config->xpc_xpayments_url, 0, -1);
            }
    
            // Set fields for the "Redirect to X-Payments" form
            $response = array(
                'txnId'       => $response['txnId'],
                'module_name' => $paymentMethod->getSetting('moduleName'),
                'url'         => $config->xpc_xpayments_url . '/payment.php',
                'fields'      => array(
                    'target' => 'main',
                    'action' => 'start',
                    'token'  => $response['token'],
                ),
            );
    
        } else {
    
            $response = array(
                'detailed_error_message' => isset($response['error_message'])
                                                ?  $response['error_message']
                                                : (is_string($response) ? $response : 'Unknown'),
    
            );
    
        }
    
        return array($status, $response);
    }
    
    /**
     * Prepare shopping cart data
     *
     * @param \XLite\Model\Cart $cart      X-Cart shopping cart
     * @param integer           $refId     Transaction ID
     * @param boolean           $forceAuth Force enable AUTH mode
     *
     * @return array
     */
    protected function prepareCart(\XLite\Model\Cart $cart, $refId, $forceAuth)
    {
        $config = \XLite\Core\Config::getInstance()->CDev->XPaymentsConnector;

        $profile = $cart->getProfile();
    
        $result = array(
            'login'                => $profile->getLogin() . ' (User ID #' . $profile->getProfileId() . ')',
            'billingAddress'       => array(),
            'shippingAddress'      => array(),
            'items'                => array(),
            'currency'             => $cart->getCurrency()->getCode(),
            'shippingCost'         => 0.00,
            'taxCost'              => 0.00,
            'discount'             => 0.00,
            'totalCost'            => 0.00,
            'description'          => 'Order(s) #' . $cart->getOrderId(),
            'merchantEmail'        => \XLite\Core\Config::getInstance()->Company->orders_department,
            'forceTransactionType' => $forceAuth ? 'A' : '',
        );
    
        $namePrefixes = array(
            'billing',
            'shipping',
        );
    
        $addressFields = array(
            'firstname' => '',
            'lastname'  => '',
            'address'   => '',
            'city'      => '',
            'state'     => 'N/A',
            'country'   => '',
            'zipcode'   => '',
            'phone'     => '',
            'fax'       => '',
            'company'   => '',
        );
    
        // Prepare shipping and billing address
        foreach ($namePrefixes as $type) {
    
            $addressIndex = $type . 'Address';
    
            foreach ($addressFields as $field => $defValue) {
                $method = 'address' == $field ? 'street' : $field;
                $result[$addressIndex][$field] = (
                    $profile->$addressIndex
                    && method_exists($profile->$addressIndex, 'get' . $method)
                    && $profile->$addressIndex->$method
                )
                    ? (
                        is_object($profile->$addressIndex->$method)
                            ? $profile->$addressIndex->$method->getCode()
                            : $profile->$addressIndex->$method
                    )
                    : $defValue;
            }

            $result[$addressIndex]['email'] = $profile->getLogin();    
        }

        // Set items
        if ($cart->getItems()) {
    
            foreach ($cart->getItems() as $item) {
                $result['items'][] = array(
                    'sku'      => $item->getSku(),
                    'name'     => $item->getName(),
                    'price'    => $item->getPrice(),
                    'quantity' => $item->getAmount(),
                );
            }
    
        }

        // Set costs
        $result['shippingCost'] = round($cart->getSurchargesSubtotal(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, false), 2);
        $result['taxCost']      = round($cart->getSurchargesSubtotal(\XLite\Model\Base\Surcharge::TYPE_TAX, false), 2);
        $result['totalCost']    = round($cart->getTotal(), 2);
        $result['discount']     = round($cart->getSurchargesSubtotal(\XLite\Model\Base\Surcharge::TYPE_DISCOUNT, false), 2);
    
        return $result;
    }

    /**
     * Make X-Payments API request
     *
     * @param string $target Request target
     * @param string $action Request action
     * @param array  $data   Request data OPTIONAL
     * @param array  $schema Request schem OPTIONAL
     *
     * @return array
     */
    public function getApiRequest($target, $action, $data = array(), $schema = array())
    {
        $config = \XLite\Core\Config::getInstance()->CDev->XPaymentsConnector;
    
        // Check requirements
        if (!$this->isModuleConfigured()) {
            return $this->getApiError('Module is not configured');
        }
    
        if (0 !== $this->checkRequirements()) {
            return $this->getApiError('Check module requirements is failed');
        }
    
        $data['target'] = $target;
        $data['action'] = $action;
    
        // send API version
        $data['api_version'] = static::XPC_API_VERSION;
    
        // Convert array to XML
        $xml = $this->convertHashToXml($data);
    
        if (!$xml) {
            return $this->getApiError('Data is not valid');
        }
    
        // Encrypt
        $xml = $this->encryptXml($xml);
    
        if (!$xml) {
            return $this->getApiError('Data is not encrypted');
        }
    
        // HTTPS request
        $post = array(
            'cart_id' => $config->xpc_shopping_cart_id,
            'request' => $xml,
        );
    
        $this->getCurlHeadersCollector(false);
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $config->xpc_xpayments_url . '/api.php');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15000);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, 'getCurlHeadersCollector'));
    
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    
        if (!empty(\XLite\Core\Config::getInstance()->Security->https_proxy)) {
            // uncomment this line if you need proxy tunnel
            // curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, true);
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($ch, CURLOPT_PROXY, \XLite\Core\Config::getInstance()->Security->https_proxy);
        }
    
        // insecure key is supported by curl since version 7.10
        $version = curl_version();
    
        if (is_array($version)) {
            $version = 'libcurl/' . $version['version'];
        }
    
        if (preg_match('/libcurl\/([^ $]+)/Ss', $version, $m)) {
            $parts = explode('.', $m[1]);
            if (7 < $parts[0] || (7 == $parts[0] && 10 <= $parts[1])) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
            }
        }
    
        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
    
        $headers = $this->getCurlHeadersCollector(true);
    
        curl_close($ch);
    
        // Check raw data
        if (substr($body, 0, 3) !== 'API') {
    
            $this->getApiError(
                'Response is not valid.\nResponse headers: ' 
                . var_export($headers, true) . '\nResponse: ' . $body . '\n'
            );
    
            return array(false, 'Response is not valid.<br />Check logs.');
    
        }
    
        // Decrypt
        list($responseStatus, $response) = $this->decryptXml($body);
    
        if (!$responseStatus) {
            return $this->getApiError('Response is not decrypted (Error: ' . $response . ')');
        }
    
        // Validate XML
        if (!empty($schema) && !$this->validateXmlAgainstSchema($response, $schema, $error)) {
            return $this->getApiError('XML in response has a wrong format. Additional info: "' . $error . '"');
        }
    
        // Convert XML to array
        $response = $this->convertXmlToHash($response);
    
        if (!is_array($response)) {
            return $this->getApiError('Unable to convert response into XML');
        }
    
        // The 'Data' tag must be set in response
        if (!isset($response[static::XPC_TAG_ROOT])) {
            return $this->getApiError('Response does not contain any data');
        }
    
        $response = $response[static::XPC_TAG_ROOT];
    
        // Process errors
        $error = $this->processApiError($response);
    
        if ($error) {
            return array(
                null,
                array(
                    'status'        => 0,
                    'message'       => $error,
                    'error_message' => '' == $response['is_error_message'] ? '' : $response['error_message'],
                )
            );
        }
    
        return array(true, $response);
    }

    /**
     * Get X-Payments Connector configuration errors
     *
     * @return integer
     */
    protected function getModuleSystemErrors()
    {
        $config = \XLite\Core\Config::getInstance()->CDev->XPaymentsConnector;
    
        $failed = 0;
    
        // Check shopping cart id
        if (
            empty($config->xpc_shopping_cart_id) 
            || !preg_match('/^[\da-f]{32}$/Ss', $config->xpc_shopping_cart_id)
        ) {
            $failed |= static::XPC_SYSERR_CARTID;
        }
    
        // Check URL
        if (
            empty($config->xpc_xpayments_url)
            || (function_exists('is_url') && !is_url($config->xpc_xpayments_url))
        ) {
            $failed |= static::XPC_SYSERR_URL;
        }
    
        $parsedURL = @parse_url($config->xpc_xpayments_url);
    
        if (
            !$parsedURL 
            || !isset($parsedURL['scheme']) 
            || !in_array($parsedURL['scheme'], array( 'https', 'http')) 
        ) {
            $failed |= static::XPC_SYSERR_URL;
        }
    
        // Check public key
        if (empty($config->xpc_public_key)) {
            $failed |= static::XPC_SYSERR_PUBKEY;
        }
    
        // Check private key
        if (empty($config->xpc_private_key)) {
            $failed |= static::XPC_SYSERR_PRIVKEY;
        }
    
        // Check private key password
        if (empty($config->xpc_private_key_password)) {
            $failed |= static::XPC_SYSERR_PRIVKEYPASS;
        }
    
        return $failed;
    }

    /**
     * Check module requirements
     *
     * @return integer
     */
    protected function checkRequirements()
    {
        $code = 0;

        if (!function_exists('curl_init')) {
            $code = $code | static::REQ_CURL;
        }

        if (
            !function_exists('openssl_pkey_get_public') || !function_exists('openssl_public_encrypt')
            || !function_exists('openssl_get_privatekey') || !function_exists('openssl_private_decrypt')
            || !function_exists('openssl_free_key')
        ) {
            $code = $code | static::REQ_OPENSSL;
        }

        if (!class_exists('DOMDocument')) {
            $code = $code | static::REQ_DOM;
        }

        return $code;
    }

    /**
     * Format and log API errors
     *
     * @param string $msg Error message
     *
     * @return array
     */
    protected function getApiError($msg)
    {
        \XLite\Logger::getInstance()->log($msg, LOG_ERR);

        return array(false, $msg);
    }
    
    /**
     * Check if passed variable is an array with numeric keys
     *
     * @param array $data Data to check
     *
     * @return boolean
     */
    protected function isAnonymousArray($data)
    {
        return is_array($data) 
            && (1 > count(preg_grep('/^\d+$/', array_keys($data), PREG_GREP_INVERT)));
    }
    
    /**
     * Convert hash array to XML
     *
     * @param array   $data  Hash array
     * @param integer $level Current recursion level OPTIONAL
     *
     * @return string
     */
    protected function convertHashToXml($data, $level = 0)
    {
        $xml = '';
    
        foreach ($data as $name => $value) {
    
            if ($this->isAnonymousArray($value)) {
                foreach ($value as $item) {
                    $xml .= $this->writeXmlTag($item, $name, $level, static::XPC_TYPE_CELL);
                }
            } else {
                $xml .= $this->writeXmlTag($value, $name, $level);
            }
    
        }
    
        return $xml;
    }
    
    /**
     * Write XML tag for current level
     *
     * @param mixed   $data  Node content
     * @param string  $name  Node name
     * @param integer $level Current recursion level OPTIONAL
     * @param string  $type  Value for 'type' attribute OPTIONAL
     *
     * @return string
     */
    protected function writeXmlTag($data, $name, $level = 0, $type = '')
    {
        $xml    = '';
        $indent = str_repeat('  ', $level);
    
        // Open tag
        $xml .= $indent . '<' . $name . (empty($type) ? '' : ' type="' . $type . '"') . '>';
        // Sublevel tags or tag value
        $xml .= is_array($data) 
            ? "\n" . $this->convertHashToXml($data, $level + 1) . $indent 
            : $this->convertLocalToUtf8($data);

        // Close tag
        $xml .= '</' . $name . '>' . "\n";
    
        return $xml;
    }
    
    /**
     * Convert local string ti UTF-8
     *
     * @param string $string  Request data
     * @param string $charset Charset OPTIONAL
     *
     * @return string
     */
    protected function convertLocalToUtf8($string, $charset = null)
    {
        if (is_null($charset)) {
            $charset = static::DEFAULT_CHARSET;
        }
    
        $charset = strtolower(trim($charset));
    
        if (function_exists('utf8_encode') && 'iso-8859-1' == $charset) {
            $string = utf8_encode($string);
    
        } elseif (function_exists('iconv')) {
            $string = iconv($charset, 'utf-8', $string);
    
        } else {
    
            $len = strlen($string);
            $data = '';
            for ($i = 0; $i < $len; $i++) {
                $c = ord(substr($string, $i, 1));
                if (!(22 > $c || 127 < $c)) {
                    $data .= substr($string, $i, 1);
                }
            }
    
            $string = $data;
        }
    
        return $string;
    }
    
    /**
     * Encrypt data (RSA)
     *
     * @param string $data Request data
     *
     * @return string
     */
    protected function encryptXml($data)
    {
        $config = \XLite\Core\Config::getInstance()->CDev->XPaymentsConnector;
    
        // Preprocess
        srand(time());
        $salt = '';
        for ($i = 0; $i < static::XPC_SALT_LENGTH; $i++) {
            $salt .= chr(rand(static::XPC_SALT_BEGIN, static::XPC_SALT_END));
        }
    
        $lenSalt = strlen($salt);
    
        $crcType = 'MD5';
        $crc = $this->makeMd5Raw($data);
    
        $crc = str_repeat(' ', 8 - strlen($crcType)) . $crcType . $crc;
        $lenCRC = strlen($crc);
    
        $lenData = strlen($data);
    
        $data = str_repeat('0', 12 - strlen((string)$lenSalt)) . $lenSalt . $salt
            . str_repeat('0', 12 - strlen((string)$lenCRC)) . $lenCRC . $crc
            . str_repeat('0', 12 - strlen((string)$lenData)) . $lenData . $data;
    
        // Encrypt
        $key = openssl_pkey_get_public($config->xpc_public_key);
        if (!$key) {
            return false;
        }
    
        $data = str_split($data, static::XPC_CHUNK_LENGTH);
        $crypttext = null;
        foreach ($data as $k => $chunk) {
            if (!openssl_public_encrypt($chunk, $crypttext, $key)) {
                return false;
            }
    
            $data[$k] = $crypttext;
        }
    
        // Postprocess
        $data = array_map('base64_encode', $data);
    
        return 'API' . implode("\n", $data);
    }
    
    
    /**
     * Make MD5 hash in raw format
     *
     * @param string $data Data
     *
     * @return string
     */
    protected function makeMd5Raw($data)
    {
        $crc = md5($data);
        $str = '';
        for ($i = 0; $i < 32; $i += 2) {
            $str .= chr(hexdec(substr($crc, $i, 2)));
        }
    
        return $str;
    }
    
    /**
     * CURL headers collector callback
     *
     * @return mixed 
     */
    protected function getCurlHeadersCollector()
    {
        static $headers = '';
    
        $args = func_get_args();
    
        if (count($args) == 1) {
    
            $return = '';
    
            if ($args[0] == true) {
                $return = $headers;
            }
    
            $headers = '';
    
            return $return;
        }
    
        if (trim($args[1]) != '') {
            $headers .= $args[1];
        }
    
        return strlen($args[1]);
    
    }
    
    /**
     * Validate received XML
     *
     * @param string $xml    XML to validate
     * @param string $schema XML schema
     * @param string &$error Error message
     *
     * @return boolean
     */
    protected function validateXmlAgainstSchema($xml, $schema, &$error)
    {
        // We use DOMDocument object to validate XML againest schema
        $dom = new \DOMDocument;
        $dom->loadXML($xml);
    
        // Add common schema elements
        $schema = '
<xsd:schema xmlns:xsd="http://www.w3.org/2001/XMLSchema">

 <xsd:element name="' . static::XPC_TAG_ROOT . '">

  <xsd:complexType>
   <xsd:sequence>

    ' . $schema . '
    <xsd:element name="error" type="xsd:string"/>
    <xsd:element name="error_message" type="xsd:string"/>
    <xsd:element name="is_error_message" type="xsd:string" minOccurs="0"/>

   </xsd:sequence>
  </xsd:complexType>

 </xsd:element>

</xsd:schema>';
    
        // Validate XML againest schema
        $result = @$dom->schemaValidateSource($schema);
    
        return $result;
    }
    
    /**
     * Decrypt (RSA)
     *
     * @param string $data Encrypted data
     *
     * @return string
     */
    protected function decryptXml($data)
    {
        $config = \XLite\Core\Config::getInstance()->CDev->XPaymentsConnector;
    
        // Decrypt
        $res = openssl_get_privatekey($config->xpc_private_key, $config->xpc_private_key_password);
        if (!$res) {
            return array(false, 'Private key is not initialized');
        }
    
        $data = substr($data, 3);
    
        $data = explode("\n", $data);
        $data = array_map('base64_decode', $data);
        foreach ($data as $k => $s) {
            if (!openssl_private_decrypt($s, $newsource, $res)) {
                return array(false, 'Can not decrypt chunk');
            }
    
            $data[$k] = $newsource;
        }
    
        openssl_free_key($res);
    
        $data = implode('', $data);
    
        // Postprocess
        $lenSalt = substr($data, 0, 12);
        if (!preg_match('/^\d+$/Ss', $lenSalt)) {
            return array(false, 'Salt length prefix has wrong format');
        }
    
        $lenSalt = intval($lenSalt);
        $data = substr($data, 12 + intval($lenSalt));
    
        $lenCRC = substr($data, 0, 12);
        if (!preg_match('/^\d+$/Ss', $lenCRC) || 9 > $lenCRC) {
            return array(false, 'CRC length prefix has wrong format');
        }
    
        $lenCRC = intval($lenCRC);
        $crcType = trim(substr($data, 12, 8));
        if ('MD5' !== $crcType) {
            return array(false, 'CRC hash is not MD5');
        }
        $crc = substr($data, 20, $lenCRC - 8);
    
        $data = substr($data, 12 + $lenCRC);
    
        $lenData = substr($data, 0, 12);
        if (!preg_match('/^\d+$/Ss', $lenData)) {
            return array(false, 'Data block length prefix has wrong format');
        }
    
        $data = substr($data, 12, intval($lenData));
    
        $currentCRC = $this->makeMd5Raw($data);
        if ($currentCRC !== $crc) {
            return array(false, 'Original CRC and calculated CRC is not equal');
        }
    
        return array(true, $data);
    }
    
    
    /**
     * Process API response errors
     *
     * @param array $response Response data
     *
     * @return boolean
     */
    protected function processApiError($response)
    {
        $error = false;
    
        if (isset($response['error']) && $response['error']) {
    
            $error = 'X-Payments error (code: ' . $response['error'] . '): '
                . (isset($response['error_message']) ? $response['error_message'] : 'Unknown')
                . (isset($this->xpcErrors[$response['error']]) ? $this->xpcErrors[$response['error']] : '');
    
            $this->getApiError($error);
        }
    
        return $error;
    }
    
    /**
     * Convert XML to hash array
     *
     * @param string $xml XML string
     *
     * @return array|string
     */
    protected function convertXmlToHash($xml)
    {
        $data = array();
    
        while (!empty($xml) && preg_match('/<([\w\d]+)(?:\s*type=["\'](\w+)["\']\s*)?>(.*)<\/\1>/Us', $xml, $matches)) {
    
            // Sublevel tags or tag value
            if (static::XPC_TYPE_CELL === $matches[2]) {
                $data[$matches[1]][] = $this->convertXmlToHash($matches[3]);
            } else {
                $data[$matches[1]] = $this->convertXmlToHash($matches[3]);
            }
    
            // Exclude parsed part from XML
            $xml = str_replace($matches[0], '', $xml);
    
        }
    
        return empty($data) ? $xml : $data;
    }

    /**
     * Return validation schema for test request
     *
     * @return string
     */
    protected function getRequestTestSchema()
    {
        return '
<xsd:element name="hashCode" minOccurs="0">

 <xsd:simpleType>
  <xsd:restriction base="xsd:string">

   <xsd:maxLength value="32"/>
   <xsd:minLength value="32"/>

  </xsd:restriction>
 </xsd:simpleType>

</xsd:element>';
    }

    /**
     * Return validation schema for the "init payment" action
     *
     * @return string
     */
    protected function getRequestInitSchema()
    {
        return '
<xsd:element name="token" minOccurs="0">

 <xsd:simpleType>
  <xsd:restriction base="xsd:string">

   <xsd:maxLength value="32"/>
   <xsd:minLength value="32"/>

  </xsd:restriction>
 </xsd:simpleType>

</xsd:element>
<xsd:element name="txnId" minOccurs="0">

 <xsd:simpleType>
  <xsd:restriction base="xsd:string">

   <xsd:maxLength value="32"/>
   <xsd:minLength value="32"/>

  </xsd:restriction>
 </xsd:simpleType>

</xsd:element>';
    }

    /**
     * Return validation schema for test request
     *
     * @return string
     */
    protected function getRequestPaymentMethodsSchema()
    {
        return '
<xsd:element name="' . static::XPC_MODULE_INFO . '" minOccurs="0" maxOccurs="unbounded">
 <xsd:complexType>
  <xsd:sequence>

   <xsd:element name="name" type="xsd:string"/>

   <xsd:element name="id" type="xsd:positiveInteger"/>

   <xsd:element name="transactionTypes">
    <xsd:complexType>
     <xsd:sequence>
       <xsd:element name="' . static::XPC_TRAN_TYPE_SALE . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_AUTH . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_CAPTURE . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_CAPTURE_PART . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_CAPTURE_MULTI . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_VOID . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_VOID_PART . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_VOID_MULTI . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_REFUND . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_REFUND_PART . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_REFUND_MULTI . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_GET_INFO . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_ACCEPT . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_DECLINE . '" type="xsd:boolean" default="0"/>
       <xsd:element name="' . static::XPC_TRAN_TYPE_TEST . '" type="xsd:boolean" default="0"/>
     </xsd:sequence>
    </xsd:complexType>
   </xsd:element>

   <xsd:element name="authCaptureInfo">
    <xsd:complexType>
     <xsd:sequence>
       <xsd:element name="authExp" type="xsd:nonNegativeInteger"/>
       <xsd:element name="captMinLimit" type="xsd:string"/>
       <xsd:element name="captMaxLimit" type="xsd:string"/>
     </xsd:sequence>
    </xsd:complexType>
   </xsd:element>

   <xsd:element name="moduleName" type="xsd:string"/>

   <xsd:element name="settingsHash" type="xsd:string"/>

  </xsd:sequence>

  <xsd:attribute name="type" type="xsd:string"/>

 </xsd:complexType>
</xsd:element>';
    }
}
