<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* 
*
* @package 
* @access public
* @version $Id$
*/

class XLite_Module_PayPalPro_Model_PaymentMethod_PaypalproExpress extends PaymentMethod // {{{ 
{
	var $configurationTemplate = "modules/PayPalPro/config.tpl";
	
	function handleRequest(&$order) // {{{
	{
		require_once LC_MODULES_DIR . 'PayPalPro' . LC_DS . 'encoded.php';
		return paypalExpressHandleRequest($this,$order);	
	} // }}}
	
	function sendExpressCheckoutRequest(&$order) // {{{
	{
		$pm = XLite_Model_PaymentMethod::factory('paypalpro');
		require_once LC_MODULES_DIR . 'PayPalPro' . LC_DS . 'encoded.php';
		$response = PayPalPro_sendRequest($pm->get("params.pro"),$this->setExpressCheckoutRequest($order,$pm->get("params.pro")));
		$xml = new XLite_Model_XML();
        $response = $xml->parse($response);
        return $response["SOAP-ENV:ENVELOPE"]["SOAP-ENV:BODY"]["_0"]["SETEXPRESSCHECKOUTRESPONSE"];
 	} // }}}
	
	function setExpressCheckoutRequest(&$order,&$payment) // {{{ 
	{
		$cart = $order->get("properties");
		$returnUrl = $this->get("returnUrl");
		$cancelUrl = $this->get("cancelUrl");
		$returnToken = ($order->get("details.token")) ? "<Token>".$order->get("details.token")."</Token>" : "";
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
		$response = PayPalPro_sendRequest($pm->get("params.pro"),$this->getExpressCheckoutRequest($pm->get("params.pro"), $token));
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

	function finishExpressCheckoutRequest(&$order, &$payment) // {{{
	{
		$pm = XLite_Model_PaymentMethod::factory('paypalpro');
		$payment = $pm->get("params.pro");
        $cart = $order->get("properties");
		$invoiceId  = $payment['prefix'].$cart['order_id'];
		$paymentAction = ($payment['type']) ? 'Sale' : 'Authorization';
        $notifyUrl  = $this->get("notifyUrl");
		$token 		= $order->get("details.token");
		$payer_id 	= $order->get("details.payer_id");
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
		$url = $this->xlite->shopURL("cart.php?target=express_checkout&action=retrieve_profile", $this->get("config.Security.customer_security"));
		return $this->prepareUrl($url);
	}	// }}} 

	function getCancelUrl() // {{{ 
	{
		$url = $this->xlite->shopURL("cart.php?target=checkout", $this->get("config.Security.customer_security"));
		return $this->prepareUrl($url);
	} // }}} 

	function getNotifyUrl() // {{{ 
	{
		$url = $this->xlite->shopURL("cart.php?target=callback&action=callback");
		return $this->prepareUrl($url);
	}
	
} // }}}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
