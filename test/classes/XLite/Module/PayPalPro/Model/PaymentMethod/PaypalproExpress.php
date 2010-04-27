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
class XLite_Module_PayPalPro_Model_PaymentMethod_PaypalproExpress
extends XLite_Module_PayPalPro_Model_PaymentMethod_PayPalProBase
{
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
     *  
     * @return integer Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0.0
     */
    public function handleRequest(XLite_Model_Cart $cart)
    {
        $request = new XLite_Model_HTTPS();

        if (!$cart->isPayPalProfileRetrieved()) {
            $expressCheckout = new XLite_Controller_Customer_Cart();
            $expressCheckout->callActionExpressCheckout();
            die();
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

        } elseif (
            'Success' !== $this->getXMLResponseValue('//SOAP-ENV:Envelope/SOAP-ENV:Body/api:DoExpressCheckoutPaymentResponse/base:Ack')
        ) {

            $response = $this->xpath->query('//SOAP-ENV:Envelope/SOAP-ENV:Body/api:DoExpressCheckoutPaymentResponse')->item(0);

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

            $response = $this->xpath->query('//SOAP-ENV:Envelope/SOAP-ENV:Body/api:DoExpressCheckoutPaymentResponse')->item(0);
            $details = $this->xpath->query('base:DoExpressCheckoutPaymentResponseDetails', $response)->item(0);
    
            switch ($this->getXMLResponseValue('base:PaymentInfo/base:PaymentStatus', $details)) {
                case 'Completed':
                case 'Processed': 
                    $cart->set('status', 'P');
                    break;

                case 'Pending': 
                    $cart->set('status', 'Q');
                    $cart->setDetailsCell(
                        'pending_reason',
                        'Pending reason',
                        $this->getXMLResponseValue('base:PaymentInfo/base:PendingReason', $details)
                    );
                    break;

                default:
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

        $cart->update();

        return in_array($cart->get('status'), array('Q', 'P'))
            ? self::PAYMENT_SUCCESS
            : self::PAYMENT_FAILURE; 
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
            die();
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
        $notifyUrl     = $this->getNotifyUrl();
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
}
