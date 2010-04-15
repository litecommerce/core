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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_PayPalPro_Model_PaymentMethod_PaypalproExpress extends XLite_Model_PaymentMethod 
{	
	public $configurationTemplate = "modules/PayPalPro/config.tpl";
	
	function handleRequest($order) // {{{
	{
		require_once LC_MODULES_DIR . 'PayPalPro' . LC_DS . 'encoded.php';
		return paypalExpressHandleRequest($this,$order);	
	} // }}}
	
	function sendExpressCheckoutRequest($order) // {{{
	{
		$pm = XLite_Model_PaymentMethod::factory('paypalpro');
		require_once LC_MODULES_DIR . 'PayPalPro' . LC_DS . 'encoded.php';
		$response = PayPalPro_sendRequest($pm->getComplex('params.pro'),$this->setExpressCheckoutRequest($order,$pm->getComplex('params.pro')));
		$xml = new XLite_Model_XML();
        $response = $xml->parse($response);
        return $response["SOAP-ENV:ENVELOPE"]["SOAP-ENV:BODY"]["_0"]["SETEXPRESSCHECKOUTRESPONSE"];
 	} // }}}
	
	function setExpressCheckoutRequest($order,&$payment) // {{{ 
	{
		$cart = $order->get("properties");
		$returnUrl = $this->get("returnUrl");
		$cancelUrl = $this->get("cancelUrl");
		$returnToken = ($order->getComplex('details.token')) ? "<Token>".$order->getComplex('details.token')."</Token>" : "";
		$paymentAction = ($payment['type']) ? 'Sale' : 'Authorization';

    	return <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
<soap:Header>
	<RequesterCredentials xmlns="urn:ebay:api:PayPalAPI">
		<Credentials xmlns="urn:ebay:apis:eBLBaseComponents">
			<Username>$payment[login]</Username>
			<ebl:Password xmlns:ebl="urn:ebay:apis:eBLBaseComponents">$payment[password]</ebl:Password>
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
	}	// }}}  
	
	function sendExpressCheckoutDetailsRequest(&$token) // {{{ 
	{
		$pm = XLite_Model_PaymentMethod::factory('paypalpro');
		require_once LC_MODULES_DIR . 'PayPalPro' . LC_DS . 'encoded.php';
		$response = PayPalPro_sendRequest($pm->getComplex('params.pro'),$this->getExpressCheckoutRequest($pm->getComplex('params.pro'), $token));
		$xml = new XLite_Model_XML();
	    $response = $xml->parse($response);
		return $response["SOAP-ENV:ENVELOPE"]["SOAP-ENV:BODY"]["_0"]["GETEXPRESSCHECKOUTDETAILSRESPONSE"];
		
	} // }}}
	
	function getExpressCheckoutRequest(&$payment, &$token) // {{{
	{
		return <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
	<soap:Header>
		<RequesterCredentials xmlns="urn:ebay:api:PayPalAPI">
			<Credentials xmlns="urn:ebay:apis:eBLBaseComponents">
	        <Username>$payment[login]</Username>
	        <ebl:Password xmlns:ebl="urn:ebay:apis:eBLBaseComponents">$payment[password]</ebl:Password>
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
	}	// }}} 

	function finishExpressCheckoutRequest($order, &$payment) // {{{
	{
		$pm = XLite_Model_PaymentMethod::factory('paypalpro');
		$payment = $pm->getComplex('params.pro');
        $cart = $order->get("properties");
		$invoiceId  = $payment['prefix'].$cart['order_id'];
		$paymentAction = ($payment['type']) ? 'Sale' : 'Authorization';
        $notifyUrl  = $this->get("notifyUrl");
		$token 		= $order->getComplex('details.token');
		$payer_id 	= $order->getComplex('details.payer_id');
		return <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <soap:Header>
    	<RequesterCredentials xmlns="urn:ebay:api:PayPalAPI">
        <Credentials xmlns="urn:ebay:apis:eBLBaseComponents">
	        <Username>$payment[login]</Username>
	        <ebl:Password xmlns:ebl="urn:ebay:apis:eBLBaseComponents">$payment[password]</ebl:Password>
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
			<PayerID>$payer_id</PayerID>
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
	} // }}}
	
	function prepareUrl($url)
	{
		$url = htmlspecialchars($url);

		return $url;
	}

	function getReturnUrl() // {{{ 
	{
		$url = $this->xlite->getShopUrl("cart.php?target=express_checkout&action=retrieve_profile", $this->getComplex('config.Security.customer_security'));
		return $this->prepareUrl($url);
	}	// }}} 

	function getCancelUrl() // {{{ 
	{
		$url = $this->xlite->getShopUrl("cart.php?target=checkout", $this->getComplex('config.Security.customer_security'));
		return $this->prepareUrl($url);
	} // }}} 

	function getNotifyUrl() // {{{ 
	{
		$url = $this->xlite->getShopUrl("cart.php?target=callback&action=callback");
		return $this->prepareUrl($url);
	}
	
} // }}}
