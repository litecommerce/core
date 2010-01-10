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

class XLite_Module_PHPCyberSource_Model_PaymentMethod_PhpcybersourceCc extends XLite_Model_PaymentMethod_CreditCard 
{
	var $configurationTemplate 	= "modules/PHPCyberSource/config.tpl";
	var $hasConfigurationForm	= true;
	var $processorName			= "PHPCyberSource";
	var $responses 				= array(
		101	=> "The request is missing one or more required fields.",					
		102	=> "One or more fields in the request contains invalid data.",
		150	=> "General system failure.",
		151	=> "The request was received, but there was a server timeout. This error does not include timeouts between the client and the server.",				
		152	=> "The request was received but there was a service timeout.",	
		200	=> "The request was declined by CyberSource because it did not pass the eFunds ID Verification check.",	
		220 => "The processor declined the request based on a general issue with the customers account.",
		221	=> "The customer matched an entry on the processors negative file.",
		222	=> "The customers bank account is frozen.",
		233	=> "The processor declined the request based on an issue with the request itself.",
		234	=> "There is a problem with your CyberSource merchant configuration.",
		236	=> "Processor failure.",
		241	=> "The request ID is invalid for the follow-on request.",
		250	=> "The request was received, but there was a timeout at the payment processor."); 		

	function process(&$cart) // {{{ 
	{
		require_once LC_MODULES_DIR . 'PHPCyberSource' . LC_DS . 'encoded.php';
		return PaymentMethod_cybersource_process($this, $cart);
	} // }}}

	function createAuthRequest(&$cart) // {{{ 
	{
		$profile  = $cart->get("profile.properties");
		$shippingState 	= $cart->get("profile.shippingState.code");
		$billingState	= $cart->get("profile.billingState.code");

		$order 	  = $cart->get("properties");
		$params	  = $this->get("params");
		$proxy	  = $this->get("proxy");
		$card	  = $this->cc_info;
		$card['cc_month'] = substr($card['cc_date'],0,2);
		$card['cc_year'] = 2000 + substr($card['cc_date'],2,2);
		$merchantReferenceCode = $params['prefix'].$cart->get("order_id");
		$discount = !is_null($cart->get("discount")) ? $cart->get("discount") : 0;
		!empty($proxy) ? $ip = $proxy : $ip = $this->get("ip");

		$request = <<<EOT
<?xml version='1.0' encoding='utf-8'?>
<requestMessage xmlns="urn:schemas-cybersource-com:transaction-data-1.13">
	<merchantReferenceCode>$merchantReferenceCode</merchantReferenceCode>
	<billTo>
		<firstName>$profile[billing_firstname]</firstName>
		<lastName>$profile[billing_lastname]</lastName>
		<street1>$profile[billing_address]</street1>
		<city>$profile[billing_city]</city>
		<state>$billingState</state>
		<postalCode>$profile[billing_zipcode]</postalCode>
		<country>$profile[billing_country]</country>
		<phoneNumber>$profile[billing_phone]</phoneNumber>
		<email>$profile[login]</email>
		<ipAddress>$ip</ipAddress>
	</billTo>
	<shipTo>
		<firstName>$profile[shipping_firstname]</firstName>
		<lastName>$profile[shipping_lastname]</lastName>
		<street1>$profile[shipping_address]</street1>
		<city>$profile[shipping_city]</city>
		<state>$shippingState</state>
		<postalCode>$profile[shipping_zipcode]</postalCode>
		<country>$profile[shipping_country]</country>
		</shipTo>
	<purchaseTotals>
		<currency>$params[currency]</currency>
		<discountAmount>$discount</discountAmount> 
		<taxAmount>$order[tax]</taxAmount>
		<grandTotalAmount>$order[total]</grandTotalAmount> 
		<freightAmount>$order[shipping_cost]</freightAmount>
	</purchaseTotals>
	<card>
		<accountNumber>$card[cc_number]</accountNumber>
		<expirationMonth>$card[cc_month]</expirationMonth>
		<expirationYear>$card[cc_year]</expirationYear>
		<cvNumber>$card[cc_cvv2]</cvNumber>
	</card>
	<ccAuthService run='true'>
	</ccAuthService>
</requestMessage>

EOT;
		return $request;
	} // }}}

	function createCaptureRequest(&$cart,$authResponse) // {{{
	{
		$params = $this->get("params");
		$discount = !is_null($cart->get("discount")) ? $cart->get("discount") : 0;
		$authRequestID = $authResponse['C:REPLYMESSAGE']['C:REQUESTID'];
		$order = $cart->get("properties");
 		$merchantReferenceCode = $params['prefix'].$cart->get("order_id");
 
		$request =<<<EOT
<?xml version='1.0' encoding='utf-8'?>
	<requestMessage xmlns='urn:schemas-cybersource-com:transaction-data-1.13'>
	<merchantReferenceCode>$merchantReferenceCode</merchantReferenceCode>
	<purchaseTotals>
		<currency>$params[currency]</currency>
		<discountAmount>$discount</discountAmount>
		<taxAmount>$order[tax]</taxAmount>
		<grandTotalAmount>$order[total]</grandTotalAmount>
		<freightAmount>$order[shipping_cost]</freightAmount>
	</purchaseTotals>
	<ccCaptureService run='true'>
		<authRequestID>$authRequestID</authRequestID>
	</ccCaptureService>
	</requestMessage>
	
EOT;
		return $request;
	} // }}} 

    function getProxy() // {{{
    {
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (!empty($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (!empty($_SERVER["HTTP_FORWARDED"])){
            return $_SERVER["HTTP_FORWARDED"];
        } elseif (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_COMING_FROM"])) {
            return $_SERVER["HTTP_X_COMING_FROM"];
        } elseif (!empty($_SERVER["HTTP_COMING_FROM"])) {
            return $_SERVER["HTTP_COMING_FROM"];
        } else {
            return '';
        }
    } // }}}
	
    function getIp() // {{{
    {
        return $_SERVER["REMOTE_ADDR"];
    } // }}}

	function runAuth(&$cart,&$config) // {{{ 
	{
    	$request    = array();
	    $response   = array();
	
	    $request[CYBS_SK_XML_DOCUMENT] = $this->createAuthRequest($cart);
	    $status = cybs_run_transaction($config, $request, $response);

	    $xml = new XLite_Model_XML();

	    $response = $xml->parse($response[CYBS_SK_XML_DOCUMENT]);
	    if ($status == CYBS_S_OK && $response['C:REPLYMESSAGE']['C:DECISION'] == 'ACCEPT') {
	        $cart->set("details.transaction",$response['C:REPLYMESSAGE']['C:REQUESTID'].':'.$response['C:REPLYMESSAGE']['C:REASONCODE']);
	        $cart->set("detailLabels.transaction","RequestID");
   	        $cart->set("details.error",null);
	        $cart->set("detailLabels.error","");
	        $cart->set("status","Q");
	    } else {
  	        $cart->set("details.transaction",$response['C:REPLYMESSAGE']['C:REQUESTID'].':'.$response['C:REPLYMESSAGE']['C:REASONCODE']);
	        $cart->set("detailLabels.transaction","RequestID");
	        $cart->set("details.error",$response['C:REPLYMESSAGE']['C:REASONCODE']."-".$this->responses[$response['C:REPLYMESSAGE']['C:REASONCODE']]);
	        $cart->set("detailLabels.error","Reason code");
	        $cart->set("status","F");
	    } 
	    $cart->update();

	    return $response;
	} // }}}

	 function runCapture(&$cart,&$config,$authResponse) // {{{
	{
		$request    = array();
        $response   = array();
    
        $request[CYBS_SK_XML_DOCUMENT] = $this->createCaptureRequest($cart,$authResponse);
        $status = cybs_run_transaction($config, $request, $response);

   	    $xml = new XLite_Model_XML();
	    $response = $xml->parse($response[CYBS_SK_XML_DOCUMENT]);

        if ($status == CYBS_S_OK && $response['C:REPLYMESSAGE']['C:DECISION'] == 'ACCEPT') {
            $cart->set("details.transaction",$response['C:REPLYMESSAGE']['C:REQUESTID'].':'.$response['C:REPLYMESSAGE']['C:REASONCODE']);
            $cart->set("detailLabels.transaction","RequestID");
   	        $cart->set("details.error",null);
	        $cart->set("detailLabels.error","");
            $cart->set("status","P");
        } else {
            $cart->set("details.transaction",$response['C:REPLYMESSAGE']['C:REQUESTID'].':'.$response['C:REPLYMESSAGE']['C:REASONCODE']);
            $cart->set("detailLabels.transaction","RequestID");
            $cart->set("details.error",$response['C:REPLYMESSAGE']['C:REASONCODE']."-".$this->responses[$response['C:REPLYMESSAGE']['C:REASONCODE']]);
            $cart->set("detailLabels.error","Reason Code");
            $cart->set("status","F");
        } 
        $cart->update();

        return $response;
	} // }}}

} // }}} 


// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
