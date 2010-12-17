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

namespace XLite\Module\CDev\XPaymentsConnector\Model\PaymentMethod;

/**
 * X-Payments-based meta payment method
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XPayment extends \XLite\Model\PaymentMethod\CreditCardWebBased
{
    const REQ_CURL    = 1;
    const REQ_OPENSSL = 2;
    const REQ_DOM     = 4;

    // Salt block length
    const SALT_LENGTH = 32;

    // Salt generator start character code
    const SALT_BEGIN = 33;

    // Salt generator end character code
    const SALT_END = 255;

    // Encryption check length
    const CHUNK_LENGTH = 128;

    // Root-level tag for all XML messages
    const TAG_ROOT = 'data';

    // Value of the "type" attribute for list items in XML
    const TYPE_CELL = 'cell';

    const MODULE_INFO = 'payment_module';

    // Payment statuses
    const NEW_STATUS      = 1;
    const AUTH_STATUS     = 2;
    const DECLINED_STATUS = 3;
    const CHARGED_STATUS  = 4;

    // Payment actions
    const NEW_ACTION         = 1;
    const AUTH_ACTION        = 2;
    const CHARGED_ACTION     = 3;
    const DECLINED_ACTION    = 4;
    const REFUND_ACTION      = 5;
    const PART_REFUND_ACTION = 6;

    // Transaction types
    const TRAN_TYPE_AUTH          = 'auth';
    const TRAN_TYPE_CAPTURE       = 'capture';
    const TRAN_TYPE_CAPTURE_PART  = 'capturePart';
    const TRAN_TYPE_CAPTURE_MULTI = 'captureMulti';
    const TRAN_TYPE_VOID          = 'void';
    const TRAN_TYPE_VOID_PART     = 'voidPart';
    const TRAN_TYPE_VOID_MULTI    = 'voidMulti';
    const TRAN_TYPE_REFUND        = 'refund';
    const TRAN_TYPE_PART_REFUND   = 'refundPart';
    const TRAN_TYPE_REFUND_MULTI  = 'refundMulti';
    const TRAN_TYPE_GET_INFO      = 'getInfo';
    const TRAN_TYPE_ACCEPT        = 'accept';
    const TRAN_TYPE_DECLINE       = 'decline';

    /**
     * Configuration template 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $configurationTemplate = false;

    /**
     * Translated statuses 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $translatedStatuses = array(
        self::CHARGED_ACTION,
        self::DECLINED_ACTION,
        self::REFUND_ACTION,
        self::PART_REFUND_ACTION,
    );

    /**
     * Current cart 
     * 
     * @var    \XLite\Model\Cart
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $cart = null;

    /**
     * Confiuration (cache)
     * 
     * @var    \XLite\Module\CDev\XPaymentsConnector\Model\Configuration
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $conf = null;

    /**
     * Form fields 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $formFields = array();

    /**
     * Constructor
     * 
     * @param mixed $param Parameter OPTIONAL
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($param = null)
    {
        // new fields
        $this->fields['xpc_confid'] = '0';

        parent::__construct($param);
    }

    /**
     * Get form URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFormURL()
    {
        return preg_replace('/\/+$/Ss', '', $this->config->CDev->XPaymentsConnector->xpc_xpayments_url)
            . '/payment.php';
    }

    /**
     * Get form fields 
     *
     * @param \XLite\Model\Cart $cart $cart
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFields(\XLite\Model\Cart $cart)
    {
        return $this->formFields;
    }

    /**
     * Handle request
     *
     * @param \XLite\Model\Cart $cart Cart
     * @param string           $type Call type OPTIONAL
     *
     * @return integer Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest(\XLite\Model\Cart $cart, $type = self::CALL_CHECKOUT)
    {
        $this->cart = $cart;

        if (!$this->getConfiguration()) {

            // Payment method is empty or wrong or configuration is not found

            // TODO - add top message

            $result = self::PAYMENT_FAILURE;

        } elseif (
            self::CALL_CHECKOUT == $type
            && !$this->sendHandshakeRequest()
        ) {
            // Handshake request is failed

            $result = self::PAYMENT_FAILURE;

        } else {

            $result = parent::handleRequest($cart, $type);

            if (self::CALL_BACK == $type) {
                $result = $this->processCallback();
            }
        }

        return $result;
    }

    protected function sendHandshakeRequest()
    {
        $refId = $this->cart->get('order_id');

        // Prepare cart
        $cart = $this->prepareCart($refId);
        if (!$cart) {

            // TODO - add top message

            return false;
        }

        // Data to send to X-Payments
        $data = array(
            'confId'      => intval($this->getConfiguration()->get('confid')),
            'refId'       => $refId,
            'cart'        => $cart,
            'returnUrl'   => \XLite::getInstance()->getShopUrl(
                \XLite\Core\Converter::buildUrl(
                    'xpayments',
                    'return',
                    array('order_id' => $this->cart->get('order_id'))
                )
            ),
            'callbackUrl' => \XLite::getInstance()->getShopUrl(
                \XLite\Core\Converter::buildUrl(
                    'callback',
                    'callback',
                    array('order_id' => $this->cart->get('order_id'))
                )
            ),
        );

        list($status, $response) = $this->request('payment', 'init', $data);

        if ($status && (!isset($response['token']) || !is_string($response['token']))) {
            $this->getAPIError('Transaction token can not found or has wrong type');
            $status = false;
        }

        if ($status) {
            // Set fields for the "Redirect to X-Payments" form
            $this->formFields = array(
                'target' => 'main',
                'action' => 'start',
                'token'  => $response['token'],
            );
        }

        return $status;
    }

    public function processReturn(\XLite\Model\Cart $cart, $txnId, $refId)
    {
        $this->cart = $cart;

        list($status, $response) = $this->requestPaymentInfo($txnId);

        $this->cart->set('xpc_txnid', $txnId);
        $this->cart->setDetailsCell('xpc_txnid', 'X-Payments transaction id', $txnId);

        if (
            $status
            && in_array($response['status'], array(self::AUTH_STATUS, self::CHARGED_STATUS))
        ) {
            $this->cart->setDetailsCell('xpc_message', 'X-Payments response', $response['message']);

            if ($response['isFraudStatus']) {
                $this->cart->setDetailsCell('xpc_fmf', 'Fraud status', 'blocked');
            }

            if ($response['amount'] != $this->cart->get('total')) {

                // Total wrong
                $cart->setDetailsCell('error', 'Error', 'Hacking attempt!');
                $cart->setDetailsCell(
                    'errorDescription',
                    'Hacking attempt details',
                    'Total amount doesn\'t match: Order total = ' . $this->cart->get('total')
                    . ', X-Payments amount = ' . $response['amount']
                );
                $cart->set('status', 'F');
                $cart->update();

            } elseif ($response['currency'] != $this->getCurrency($refId)) {

                // Currency wrong
                $cart->setDetailsCell('error', 'Error', 'Hacking attempt!');
                $cart->setDetailsCell(
                    'errorDescription',
                    'Hacking attempt details',
                    'Currency code doesn\'t match: Order currency = ' . $this->getCurrency($refId)
                    . ', X-Payments currency = ' . $response['currency']
                );
                $cart->set('status', 'F');
                $cart->update();

            } else {

                $cart->set('status', 'P');
                $cart->update();
                $ctrl = new \XLite\Controller\Customer\Checkout();
                $ctrl->callSuccess();
            }

        } else {

            // TODO - add top message

            $status = false;
            $cart->set('status', 'T');
            $cart->update();
        }

        return $status;
    }

    protected function processCallback()
    {
        $request = \XLite\Core\Request::getInstance();

        $result = self::PAYMENT_FAILURE;

        if (
            $request->txnId
            && $request->updateData
            && isset($request->updateData['action'])
        ) {

            $status = false;
            if (in_array($request->updateData['action'], $this->translatedStatuses)) {
                $status = $this->getOrderStatusByAction($request->updateData['action']);
            }

            if ($status) {
                $order = new \XLite\Model\Order();
                foreach ($order->getOrdersByXPCTxnId($request->txnId) as $o) {
                    $o->set('status', $status);
                    $o->update();
                }
            }

            $result = self::PAYMENT_SUCCESS;
        }
    }

    protected function requestPaymentInfo($txn_id, $refresh = false)
    {
        $data = array(
            'txnId'   => $txn_id,
            'refresh' => $refresh ? 1 : 0
        );

        list($status, $response) = $this->request('payment', 'get_info', $data);

        if ($status) {
            if (!is_array($response) || !isset($response['status'])) {
                $this->getAPIError('GetInfo request. Server response has not status');
                $status = false;

            } elseif (!isset($response['message'])) {
                $this->getAPIError('GetInfo request. Server response has not message');
                $status = false;

            } elseif (!isset($response['transactionInProgress'])) {
                $this->getAPIError('GetInfo request. Server response has not transaction progress status');
                $status = false;

            } elseif (!isset($response['isFraudStatus'])) {
                $this->getAPIError('GetInfo request. Server response has not fraud filter status');
                $status = false;

            } elseif (!isset($response['currency']) || strlen($response['currency']) != 3) {
                $this->getAPIError('GetInfo request. Server response has not currency code or currency code has wrong format');
                $status = false;

            } elseif (!isset($response['amount'])) {
                $this->getAPIError('GetInfo request. Server response has not payment amount');
                $status = false;

            } elseif (!isset($response['capturedAmount'])) {
                $this->getAPIError('GetInfo request. Server response has not captured amount');
                $status = false;

            } elseif (!isset($response['capturedAmountAvail'])) {
                $this->getAPIError('GetInfo request. Server response has not available for capturing amount');
                $status = false;

            } elseif (!isset($response['refundedAmount'])) {
                $this->getAPIError('GetInfo request. Server response has not refunded amount');
                $status = false;

            } elseif (!isset($response['refundedAmountAvail'])) {
                $this->getAPIError('GetInfo request. Server response has not available for refunding amount');
                $status = false;

            } elseif (!isset($response['voidedAmount'])) {
                $this->getAPIError('GetInfo request. Server response has not voided amount');
                $status = false;

            } elseif (!isset($response['voidedAmountAvail'])) {
                $this->getAPIError('GetInfo request. Server response has not available for cancelling amount');
                $status = false;

            }
        }

        return array($status, $response);
    }

    public function sendTestRequest()
    {
        srand();

        $hash_code = strval(rand(0, 1000000));

        // Make test request
        list($status, $response) = $this->request(
            'connect',
            'test',
            array('testCode' => $hash_code)
        );

        // Compare MD5 hashes
        if ($status && md5($hash_code) !== $response['hashCode']) {
            $this->getAPIError('Test connection data is not valid');
            $status = false;
        }

        return $status;
    }

    public function requestPaymentMethods()
    {
        $result = array();

        // Call the "api.php?target=payment_confs&action=get" URL
        list($status, $response) = $this->request(
            'payment_confs',
            'get',
            array()
        );

        // Check status
        if ($status && (!isset($response['payment_module']) || !is_array($response['payment_module']))) {
            $status = false;
        }

        return $status ? $response['payment_module'] : false;
    }

    /**
     * Get configuration 
     * 
     * @return \XLite\Module\CDev\XPaymentsConnector\Model\Configuration
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getConfiguration()
    {
        if (is_null($this->conf)) {
            $this->conf = new \XLite\Module\CDev\XPaymentsConnector\Model\Configuration(intval($this->get('xpc_confid')));
            if (!$this->conf->isExists()) {
                $this->conf = null;
            }
        }

        return $this->conf;
    }

    protected function request($target, $action, array $data = array(), array $schema = array())
    {

        // Check requirements
        if (!\XLite\Module\CDev\XPaymentsConnector\Main::isConfigured()) {
            return $this->getAPIError('Module is not configured');
        }

        if (\XLite\Module\CDev\XPaymentsConnector\Main::checkRequirements() != 0) {
            return $this->getAPIError('Check module requirements is failed');
        }

        $data['target'] = $target;
        $data['action'] = $action;

        // Convert array to XML
        $xml = $this->convertHash2XML($data);

        if (!$xml) {
            return $this->getAPIError('Data is not valid');
        }

        // Encrypt
        $xml = $this->encrypt($xml);
        if (!$xml) {
            return $this->getAPIError('Data is not encrypted');
        }

        // HTTPS request
        $post = array(
            'cart_id' => $this->config->CDev->XPaymentsConnector->xpc_shopping_cart_id,
            'request' => $xml
        );

        $https = new \XLite\Model\HTTPS('libcurl');
        $https->url = $this->config->CDev->XPaymentsConnector->xpc_xpayments_url . '/api.php';
        $https->timeout = 15000;
        $https->use_ssl3 = true;
        $https->method = 'POST';
        $https->data = $post;

        $requestResult = $https->request();

        $body = $https->response;
        $headers = $https->responseHeaders;

        // Check raw data
        if (substr($body, 0, 3) !== 'API') {
            return $this->getAPIError(
                'Response is not valid.' . "\n"
                . 'Response headers: ' . var_export($headers, true) . "\n"
                . 'Response: ' . $body . "\n"
            );
        }

        // Decrypt
        list($responseStatus, $response) = $this->decrypt($body);

        if (!$responseStatus) {
            return $this->getAPIError('Response is not decrypted (Error: ' . $response . ')');
        }

        // Convert XML to array
        $response = $this->convertXML2Hash($response);

        if (!is_array($response)) {
            return $this->getAPIError('Unable to convert response into XML');
        }

        // The 'Data' tag must be set in response
        if (!isset($response[self::TAG_ROOT])) {
            return $this->getAPIError('Response does not contain any data');
        }

        // Process errors
        if ($this->processAPIError($response)) {
            return array(false, 'X-Payments internal error');
        }

        return array(true, $response[self::TAG_ROOT]);
    }

    /**
     * Get API error response
     * 
     * @param string $msg Error message
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAPIError($msg)
    {
        $this->logger->log('XPayments connector error: ' . $msg, PEAR_LOG_ERR);

        return array(false, $msg);
    }

    protected function processAPIError(array $response)
    {
        $error = false;

        if (isset($response['error']) && $response['error']) {
            $this->getAPIError(
                'X-Payments error (code: ' . $response['error'] . '): '
                . (isset($response['error_message']) ? $response['error_message'] : 'Unknown')
            );
            $error = true;
        }

        return $error;
    }

    /**
     * Check - force use authorization request or not
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isForceAuth()
    {
        return false;
    }

    protected function prepareCart($refId)
    {
        $userInfo = $this->cart->getProfile();

        $result = array(
            'billingAddress'  => array(
                'email' => $userInfo->get('login'),
            ),
            'shippingAddress' => array(
                'email' => $userInfo->get('login'),
            ),
            'items'           => array(),
            'currency'        => $this->getCurrency($refId),
            'shippingCost'    => 0.00,
            'taxCost'         => 0.00,
            'discount'        => 0.00,
            'totalCost'       => 0.00,
            'description'     => 'Order(s) #' . $refId,
            'merchantEmail'   => $this->config->Company->orders_department,
            'forceTransactionType' => $this->isForceAuth() ? 'A' : '',
            'login'           => $userInfo->get('login'),
        );

        $namePrefixes  = array('billing', 'shipping');
        $addressFields = array('firstname', 'lastname', 'address', 'city', 'state', 'country', 'zipcode', 'name', 'phone', 'fax');

        // Prepare shipping and billing address
        foreach ($namePrefixes as $prefix) {

            $addressIndex = $prefix . 'Address';

            foreach ($addressFields as $field) {
                $result[$addressIndex][$field] = $userInfo->get($prefix . '_' . $field);
            }

        }

        // Set products
        foreach ($this->cart->getItems() as $product) {
            $result['items'][] = array(
                'sku'      => $product->get('sku'),
                'name'     => $product->get('name'),
                'price'    => $product->get('price'),
                'quantity' => $product->get('amount'),
            );
        }

        // Set costs
        $result['shippingCost'] = number_format($this->cart->get('shipping_cost'), 2);
        $result['taxCost'] = number_format($this->cart->get('tax_cost'), 2);
        $result['totalCost'] = number_format($this->cart->get('total'), 2);

        // Get admin email if Orders department email is empty

        return $result;
    }

    protected function getCurrency($refId)
    {
        return 'USD';
    }

    protected function getOrderStatusByAction($action)
    {
        $action = intval($action);

        $cell = false;
        switch ($action) {
            case self::NEW_ACTION:
                $cell = 'xpc_status_new';
                break;

            case self::AUTH_ACTION:
                $cell = 'xpc_status_auth';
                break;

            case self::CHARGED_ACTION:
                $cell = 'xpc_status_charged';
                break;

            case self::DECLINED_ACTION:
                $cell = 'xpc_status_declined';
                break;

            case self::REFUND_ACTION:
                $cell = 'xpc_status_refunded';
                break;

            case self::PART_REFUND_ACTION:
                $cell = 'xpc_status_part_refunded';
                break;
        }

        return ($cell && isset($this->config->CDev->XPaymentsConnector->$cell) && $this->config->CDev->XPaymentsConnector->$cell)
            ? $this->config->XPayments_Connector->$cell
            : false;
    }

    /**
     * Convert hash to XML 
     * 
     * @param array   $data  Hash
     * @param integer $level Parentness level OPTIONAL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function convertHash2XML(array $data, $level = 0)
    {
        $xml = '';

        foreach ($data as $name => $value) {

            if ($this->isAnonymousArray($value)) {
                foreach ($value as $item) {
                    $xml .= $this->writeXMLTag($item, $name, $level, self::TYPE_CELL);
                }
            } else {
                $xml .= $this->writeXMLTag($value, $name, $level);
            }

        }

        return $xml;
    }

    /**
     * Check - argument is plain array or not
     * 
     * @param array $data Array
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isAnonymousArray($data)
    {
        return is_array($data)
            && 1 > count(preg_grep('/^\d+$/', array_keys($data), PREG_GREP_INVERT));
    }

    /**
     * Write XML tag
     * 
     * @param mixed   $data  Data
     * @param string  $name  Tag name
     * @param integer $level Parentness level OPTIONAL
     * @param string  $type  Tag type OPTIONAL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function writeXMLTag($data, $name, $level = 0, $type = '')
    {
        $xml    = '';
        $indent = str_repeat('  ', $level);

        // Open tag
        $xml .= $indent . '<' . $name . (empty($type) ? '' : ' type="' . $type . '"') . '>';

        // Sublevel tags or tag value
        $xml .= is_array($data) ? "\n" . $this->convertHash2XML($data, $level + 1) . $indent : $data;

        // Close tag
        $xml .= '</' . $name . '>' . "\n";

        return $xml;
    }

    protected function convertXML2Hash($xml)
    {
        $data = array();

        while (
            !empty($xml)
            && preg_match('/<([\w\d]+)(?:\s*type=["\'](\w+)["\']\s*)?' . '>(.*)<\/\1>/Us', $xml, $matches)
        ) {

            // Sublevel tags or tag value
            if (self::TYPE_CELL === $matches[2]) {
                $data[$matches[1]][] = $this->convertXML2Hash($matches[3]);

            } else {
                $data[$matches[1]] = $this->convertXML2Hash($matches[3]);
            }

            // Exclude parsed part from XML
            $xml = str_replace($matches[0], '', $xml);

        }

        return empty($data) ? $xml : $data;
    }

    protected function encrypt($data)
    {
        // Preprocess
        srand(time());
        $salt = '';
        for ($i = 0; $i < self::SALT_LENGTH; $i++) {
            $salt .= chr(rand(self::SALT_BEGIN, self::SALT_END));
        }

        $lenSalt = strlen($salt);

        $crcType = 'MD5';
        $crc = md5($data, true);

        $crc = str_repeat(' ', 8 - strlen($crcType)) . $crcType . $crc;
        $lenCRC = strlen($crc);

        $lenData = strlen($data);

        $data = str_repeat('0', 12 - strlen((string)$lenSalt)) . $lenSalt . $salt
            . str_repeat('0', 12 - strlen((string)$lenCRC)) . $lenCRC . $crc
            . str_repeat('0', 12 - strlen((string)$lenData)) . $lenData . $data;

        // Encrypt
        $key = openssl_pkey_get_public($this->config->CDev->XPaymentsConnector->xpc_public_key);
        if (!$key) {
            return false;
        }

        $data = str_split($data, self::CHUNK_LENGTH);
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

    protected function decrypt($data)
    {

        // Decrypt
        $res = openssl_get_privatekey(
            $this->config->CDev->XPaymentsConnector->xpc_private_key,
            $this->config->CDev->XPaymentsConnector->xpc_private_key_password
        );
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
        if (!preg_match('/^\d+$/Ss', $lenCRC) || $lenCRC < 9) {
            return array(false, 'CRC length prefix has wrong format');
        }

        $lenCRC = intval($lenCRC);
        $crcType = trim(substr($data, 12, 8));
        if ($crcType !== 'MD5') {
            return array(false, 'CRC hash is not MD5');
        }
        $crc = substr($data, 20, $lenCRC - 8);

        $data = substr($data, 12 + $lenCRC);

        $lenData = substr($data, 0, 12);
        if (!preg_match('/^\d+$/Ss', $lenData)) {
            return array(false, 'Data block length prefix has wrong format');
        }

        $data = substr($data, 12, intval($lenData));

        $currentCRC = md5($data, true);
        if ($currentCRC !== $crc) {
            return array(false, 'Original CRC and calculated CRC is not equal');
        }

        return array(true, $data);
    }

}
