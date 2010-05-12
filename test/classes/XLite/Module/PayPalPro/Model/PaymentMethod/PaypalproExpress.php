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

/**
 * Paypal express checkout
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_PayPalPro_Model_PaymentMethod_PaypalproExpress extends XLite_Model_PaymentMethod_CreditCard
{
    /**
     * XPath response object
     *
     * @var    DOMXPath
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $xpath = null;

    /**
     * Last HTTPS request error
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $lastRequestError = false;

    /**
     * Configuration template 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $configurationTemplate = 'modules/PayPalPro/config.tpl';

    /**
     * Form template 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $formTemplate = false;

    /**
     * Configuration request handler (controller part)
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleConfigRequest()
    {
        $pm = new XLite_Model_PaymentMethod('paypalpro');
        $pm->handleConfigRequest();
    }

    /**
     * Getter
     * 
     * @param string $name property name
     *  
     * @return mixed
     * @access public
     * @since  3.0.0
     */
    public function get($name)
    {
        if ('params' == $name) {
            $pm = new XLite_Model_PaymentMethod('paypalpro');
            $result = $pm->get('params');

        } else {
            $result = parent::get($name);
        }

        return $result;
    }
 
    /**
     * Handle request 
     * 
     * @param XLite_Model_Cart $cart Cart
     * @param string           $type Call type
     *  
     * @return integer Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest(XLite_Model_Cart $cart, $type = self::CALL_CHECKOUT)
    {
        if (self::CALL_BACK == $type) {
            $pm = new XLite_Module_PayPalPro_Model_PaymentMethod_Paypalpro('paypalpro');
            $result = $pm->processCallback($cart);

        } else {
            $result = parent::handleRequest($cart, $type);
        }

        return $result;
    }

    /**
     * Process cart
     * 
     * @param XLite_Model_Cart $cart Cart
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function process(XLite_Model_Cart $cart)
    {
        if (!$cart->isPayPalProfileRetrieved()) {
            $expressCheckout = new XLite_Controller_Customer_Cart();
            $expressCheckout->callActionExpressCheckout();
            die ();
        }

        $pm = new XLite_Model_PaymentMethod('paypalpro');
        $response = $this->sendRequest(
            $this->getComplex('params.pro'),
            $this->getDoExpressCheckoutPaymentXML($cart, $pm->getComplex('params.pro'))
        );

        if (!$this->parseResponse($response)) {

            $cart->set('status', 'F');
            $cart->setDetailsCell(
                'error',
                'Error',
                'Internal error'
            );
            $cart->setDetailsCell(
                'errorDescription',
                'Description',
                'XML parsing error'
            );

        } elseif ($this->xpath->query('//SOAP-ENV:Envelope/SOAP-ENV:Body/SOAP-ENV:Fault')->length) {

            $responseFault = $this->xpath->query('//SOAP-ENV:Envelope/SOAP-ENV:Body/SOAP-ENV:Fault');

            $cart->set('status', 'F');
            $cart->setDetailsCell(
                'error',
                'Error',
                $this->getXMLResponseValue('base:FaultCode', $responseFault)
                . ' - '
                . $this->getXMLResponseValue('base:FaultString', $responseFault)
            );
            $cart->setDetailsCell(
                'errorDescription',
                'Description',
                $this->getXMLResponseValue('base:Detail', $responseFault)
            );

        } else {

            $response = $this->xpath->query('//SOAP-ENV:Envelope/SOAP-ENV:Body/api:DoExpressCheckoutPaymentResponse')
                ->item(0);

            if (
                !in_array($this->getXMLResponseValue('base:Ack', $response), array('Success', 'SuccessWithWarning'))
            ) {

                $cart->set('status', 'F');
                $cart->setDetailsCell(
                    'error',
                    'Error',
                    $this->getXMLResponseValue('base:Errors/base:ErrorCode', $response)
                    . ': '
                    . $this->getXMLResponseValue('base:Errors/base:ShortMessage', $response)
                );
                $cart->setDetailsCell(
                    'errorDescription',
                    'Description',
                    $this->getXMLResponseValue('base:Errors/base:LongMessage', $response)
                );

            } else {

                $this->processFinalizeResponse($response, $cart);

            }
        }

        $cart->update();
    }

    /**
     * Process transaction finalize response 
     * 
     * @param DOMNode          $response Response body node
     * @param XLite_Model_Cart $cart     Cart
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processFinalizeResponse(DOMNode $response, XLite_Model_Cart $cart)
    {
        $details = $this->xpath->query('base:DoExpressCheckoutPaymentResponseDetails', $response)->item(0);

        $paymentStatus = $this->getXMLResponseValue('base:PaymentInfo/base:PaymentStatus', $details);

        if ('Completed' == $paymentStatus || 'Processed' == $paymentStatus) {

            $cart->set('status', 'P');

        } elseif ('Pending' == $paymentStatus) {

            $cart->set('status', 'Q');
            $cart->setDetailsCell(
                'pending_reason',
                'Pending reason',
                $this->getXMLResponseValue('base:PaymentInfo/base:PendingReason', $details)
            );

        } elseif ('SuccessWithWarning' == $this->getXMLResponseValue('base:Ack', $response)) {
            $cart->set('status', 'Q');
            $cart->setDetailsCell(
                'pending_reason',
                'Pending reason',
                'The transaction is pending.'
                . ' To continue working with the transaction, either accept or decline it'
            );
        }

        $cart->setDetailsCell(
            'txn_id',
            'Transaction ID',
            $this->getXMLResponseValue('base:PaymentInfo/base:TransactionID', $details)
        );
        $cart->setDetailsCell(
            'payment_date',
            'Payment date',
            $this->getXMLResponseValue('base:PaymentInfo/base:PaymentDate', $details)
        );

        $cart->unsetDetailsCell('error');
        $cart->unsetDetailsCell('errorDescription');
    }

    /**
     * Redirect to PayPal Express Checkout 
     * 
     * @param XLite_Model_Cart $cart Cart
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function startExpressCheckout(XLite_Model_Cart $cart)
    {
        $response = $this->sendExpressCheckoutRequest($cart);

        if (
            $response
            && 'Success' == $this->getXMLResponseValue('base:Ack', $response)
            && $this->getXMLResponseValue('api:Token', $response)
        ) {

            $pmpro = new XLite_Model_PaymentMethod('paypalpro');

            $redirect = $pmpro->getComplex('params.pro.mode')
                ? 'https://www.paypal.com'
                : 'https://www.sandbox.paypal.com';

            header(
                'Location: '
                . $redirect
                . '/webscr?cmd=_express-checkout&token='
                . $this->getXMLResponseValue('api:Token', $response)
            );
            die ();
        }

        return false;
    }
 
    /**
     * Send SetExpressCheckout request  
     * 
     * @param XLite_Model_Cart $order Cart
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function sendExpressCheckoutRequest(XLite_Model_Cart $order)
    {
        $pm = new XLite_Model_PaymentMethod('paypalpro');

        $response = $this->sendRequest(
            $pm->getComplex('params.pro'),
            $this->getSetExpressCheckoutXML($order, $pm->getComplex('params.pro'))
        );

        $result = $this->parseResponse($response);

        return $result
            ? $this->xpath->query('//SOAP-ENV:Envelope/SOAP-ENV:Body/api:SetExpressCheckoutResponse')->item(0)
            : null;
    }
    
    /**
     * Get SetExpressCheckout XML request data
     * 
     * @param XLite_Model_Cart $order   Cart
     * @param array            $payment POayment module data
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSetExpressCheckoutXML(XLite_Model_Cart $order, array $payment)
    {
        $cart = $order->get('properties');

        $returnUrl     = $this->getReturnUrl();
        $cancelUrl     = $this->getCancelUrl();
        $returnToken   = $order->isPayPalProfileRetrieved() ? '<Token>' . $order->getDetail('token') . '</Token>' : '';
        $paymentAction = $payment['type'] ? 'Sale' : 'Authorization';

        $signature = $this->getRequestSignature($payment);

        return <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema'>
<soap:Header>
    <RequesterCredentials xmlns="urn:ebay:api:PayPalAPI">
        <Credentials xmlns="urn:ebay:apis:eBLBaseComponents">
            <Username>$payment[login]</Username>
            <ebl:Password xmlns:ebl="urn:ebay:apis:eBLBaseComponents">$payment[password]</ebl:Password>
            $signature
        </Credentials>
    </RequesterCredentials>
</soap:Header>
<soap:Body>
    <SetExpressCheckoutReq xmlns="urn:ebay:api:PayPalAPI">
        <SetExpressCheckoutRequest>
            <Version xmlns="urn:ebay:apis:eBLBaseComponents">1.00</Version>
            <SetExpressCheckoutRequestDetails xmlns="urn:ebay:apis:eBLBaseComponents">
                <OrderTotal currencyID="$payment[currency]">$cart[total]</OrderTotal>
                <ReturnURL>$returnUrl</ReturnURL>
                <CancelURL>$cancelUrl</CancelURL>
                <PaymentAction>$paymentAction</PaymentAction>
                $returnToken
            </SetExpressCheckoutRequestDetails>
        </SetExpressCheckoutRequest>
    </SetExpressCheckoutReq>
</soap:Body>
</soap:Envelope>
EOT;
    }
    
    /**
     * Send GetExpressCheckoutDetails request
     * 
     * @param string $token Token
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function sendExpressCheckoutDetailsRequest($token)
    {
        $pm = new XLite_Model_PaymentMethod('paypalpro');

        $response = $this->sendRequest(
            $pm->getComplex('params.pro'),
            $this->getGetExpressCheckoutDetailsXML($pm->getComplex('params.pro'), $token)
        );

        $result = $this->parseResponse($response);

        return $result
            ? $this->xpath->query('//SOAP-ENV:Envelope/SOAP-ENV:Body/api:GetExpressCheckoutDetailsResponse')->item(0)
            : null;
    }
    
    /**
     * Get GetExpressCheckoutDetails XML request data
     * 
     * @param array  $payment Payment module data
     * @param string $token   Token
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getGetExpressCheckoutDetailsXML(array $payment, $token)
    {
        $signature = $this->getRequestSignature($payment);

        return <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema'>
    <soap:Header>
        <RequesterCredentials xmlns="urn:ebay:api:PayPalAPI">
            <Credentials xmlns="urn:ebay:apis:eBLBaseComponents">
                <Username>$payment[login]</Username>
                <ebl:Password xmlns:ebl="urn:ebay:apis:eBLBaseComponents">$payment[password]</ebl:Password>
                $signature
            </Credentials>
         </RequesterCredentials>
    </soap:Header>
    <soap:Body>
        <GetExpressCheckoutDetailsReq xmlns="urn:ebay:api:PayPalAPI">
            <GetExpressCheckoutDetailsRequest>
                <Version xmlns="urn:ebay:apis:eBLBaseComponents">1.00</Version>
                <Token>$token</Token>
            </GetExpressCheckoutDetailsRequest>
        </GetExpressCheckoutDetailsReq>
  </soap:Body>
</soap:Envelope>
EOT;
    }

    /**
     * Get DoExpressCheckoutPayment XML request data
     * 
     * @param XLite_Model_Cart $order   Cart
     * @param array            $payment Payment module dat:
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDoExpressCheckoutPaymentXML(XLite_Model_Cart $order, array $payment)
    {
        $pm = new XLite_Model_PaymentMethod('paypalpro');

        $payment       = $pm->getComplex('params.pro');
        $cart          = $order->get('properties');
        $invoiceId     = $payment['prefix'] . $cart['order_id'];
        $paymentAction = '1' == $payment['type'] ? 'Sale' : 'Authorization';
        $notifyUrl     = $this->getNotifyUrl($order);
        $token         = $order->getDetail('token');
        $payerId       = $order->getDetail('payer_id');

        $signature = $this->getRequestSignature($payment);

        return <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema'>
  <soap:Header>
        <RequesterCredentials xmlns="urn:ebay:api:PayPalAPI">
            <Credentials xmlns="urn:ebay:apis:eBLBaseComponents">
                <Username>$payment[login]</Username>
                <ebl:Password xmlns:ebl="urn:ebay:apis:eBLBaseComponents">$payment[password]</ebl:Password>
                $signature
              </Credentials>
        </RequesterCredentials>
    </soap:Header>
    <soap:Body>
    <DoExpressCheckoutPaymentReq xmlns="urn:ebay:api:PayPalAPI">
        <DoExpressCheckoutPaymentRequest>
        <Version xmlns="urn:ebay:apis:eBLBaseComponents">1.00</Version>
        <DoExpressCheckoutPaymentRequestDetails xmlns="urn:ebay:apis:eBLBaseComponents">
            <PaymentAction>$paymentAction</PaymentAction>
            <Token>$token</Token>
            <PayerID>$payerId</PayerID>
            <PaymentDetails>
                <OrderTotal currencyID="$payment[currency]">$cart[total]</OrderTotal>
                <ButtonSource>Litecommerce</ButtonSource>
                <NotifyURL>$notifyUrl</NotifyURL>
                <InvoiceID>$invoiceId</InvoiceID>
            </PaymentDetails>
        </DoExpressCheckoutPaymentRequestDetails>
        </DoExpressCheckoutPaymentRequest>
    </DoExpressCheckoutPaymentReq>
</soap:Body>
</soap:Envelope>
EOT;
    }
    
    /**
     * Get return URL
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getReturnUrl()
    {
        $url = $this->xlite->getShopUrl(
            XLite_Core_Converter::buildUrl('express_checkout', 'retrieve_profile'),
            $this->config->Security->customer_security
        );

        return $this->prepareUrl($url);
    }

    /**
     * Send request 
     * 
     * @param array  $payment Payment module parameters
     * @param string $data    XML data
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function sendRequest(array $payment, $data)
    {
        $this->lastRequestError = false;

        $request = new XLite_Model_HTTPS();

        $request->data       = $data;
        if ('C' == $payment['auth_method']) {
            $request->cert = $payment['certificate'];
        }
        $request->method     = 'POST';
        $request->conttype   = 'text/xml';
        $request->urlencoded = true;

        if ($payment['mode']) {
            $request->url = 'C' == $payment['auth_method']
                ? 'https://api.paypal.com:443/2.0/'
                : 'https://api-3t.paypal.com:443/2.0/';

        } else {
            $request->url = 'C' == $payment['auth_method']
                ? 'https://api.sandbox.paypal.com:443/2.0/'
                : 'https://api-aa.sandbox.paypal.com:443/2.0/';
        }

        $request->request();

        if ($request->error) {
            $this->lastRequestError = $request->error;
        }

        return $request->error ? $request->error : $request->response;
    }

    /**
     * Get cancel URL
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCancelUrl()
    {
        $url = $this->xlite->getShopUrl(
            XLite_Core_Converter::buildUrl('checkout', 'paypal_cancel'),
            $this->config->Security->customer_security
        );

        return $this->prepareUrl($url);
    }

    /**
     * Get notify (callback) URL 
     *
     * @param XLite_Model_Cart $cart Cart
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNotifyUrl(XLite_Model_Cart $cart)
    {
        $url = $this->xlite->getShopUrl(
            XLite_Core_Converter::buildUrl(
                'callback',
                'callback',
                array('order_id' => $cart->get('order_id'))
            )
        );

        return $this->prepareUrl($url);
    }

    /**
     * Prepare URL 
     * 
     * @param string $url URL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareUrl($url)
    {
        return htmlspecialchars($url);
    }

    /**
     * Get request signature XML tag
     * 
     * @param array $payment Payment module data
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRequestSignature(array $payment)
    {
        return 'C' != $payment['auth_method'] ? ('<Signature>' . $payment['signature'] . '</Signature>') : '';
    }

    /**
     * Parse response 
     * 
     * @param string $response Response
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseResponse($response)
    {
        $dom = new DOMDocument();
        $result = @$dom->loadXML($response, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_ERR_NONE);
        if ($result) {
            $this->xpath = new DOMXPath($dom);

            $this->xpath->registerNamespace('api', 'urn:ebay:api:PayPalAPI');
            $this->xpath->registerNamespace('base', 'urn:ebay:apis:eBLBaseComponents');
        }

        return $result;
    }

    /**
     * Get response XML node value 
     * 
     * @param string  $query  XPath query
     * @param DOMNode $parent Parent query node
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getXMLResponseValue($query, $parent = null)
    {
        return $parent
            ? $this->xpath->query($query, $parent)->item(0)->nodeValue
            : $this->xpath->query($query)->item(0)->nodeValue;
    }

    /**
     * Getter
     * 
     * @param string $name Property name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __get($name)
    {
        return 'xpath' == $name ? $this->xpath : parent::__get($name);
    }

}
