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
class XLite_Module_PayPalPro_Model_PaymentMethod_Paypalpro extends XLite_Model_PaymentMethod_CreditCard
{
	// Pending reasons array // {{{	 
	 public $pendingReasons = array(
		'echeck' => 'The payment is pending because it was made by an eCheck, which has not yet cleared',
		'multi_currency' => 'You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment',
		'intl' => 'The payment is pending because you, the merchant, hold an international account and do not have a withdrawal method.  You must manually accept or deny this payment from your Account Overview',
		'verify' => 'The payment is pending because you, the merchant, are not yet verified. You must verify your account before you can accept this payment',
		'address' => 'The payment is pending because your customer did not include a confirmed shipping address and you, the merchant, have your Payment Receiving Preferences set such that you want to manually accept or deny each of these payments.  To change your preference, go to the Preferences section of your Profile',
		'upgrade' => 'The payment is pending because it was made via credit card and you, the merchant, must upgrade your account to Business or Premier status in order to receive the funds',
		'unilateral' => 'The payment is pending because it was made to an email address that is not yet registered or confirmed',
		'other' => 'The payment is pending for some reason. For more information, contact PayPal customer service'
	 	); // }}}

		// avsResponses and cvvResponses // {{{	
	public $avsResponses = array(
		"A"	=> "Address only (no ZIP)",
		"B" => "Address only (no ZIP)",
		"C" => "None",
		"D" => "Address and Postal Code",
		"E" => "Not allowed for MOTO (Internet/Phone) transactions",
		"F" => "Address and Postal Code",
		"G" => "Global Unavailable",
		"I" => "International Unavailable", 
		"N" => "No", 
		"P" => "Postal Code only (no Address)",
		"R" => "Retry",
		"S" => "Service not supported",
		"U" => "Unavailable",
		"W" => "Whole ZIP",
	   	"X" => "Exact match",
		"Y"	=> "Yes",
		"Z" => "ZIP");			
	public $cvvResponses = array ( 
		"M" => "Match",
		"N" => "Not match",
		"P" => "Not processed",
		"S" => "Service not supported",
		"U" => "Unavailable",
		"X" => "No response");	
	// }}} 																			 

  	// properties // {{{	  
   	public $configurationTemplate = "modules/PayPalPro/config.tpl";	
	public $processorName = "PayPal Pro";	
	public $phone = array(); // }}}

	function checkServiceURL()
	{
		require_once LC_MODULES_DIR . 'PayPalPro' . LC_DS . 'encoded.php';
		Payment_method_paypalpro_checkServiceURL($this);
	}

	function process($order) // {{{ 
	{
		require_once LC_MODULES_DIR . 'PayPalPro' . LC_DS . 'encoded.php';
		Payment_method_paypalpro_process($this,$order);
	} // }}} 

	function getFormTemplate() // {{{ 
	{
		return ($this->getComplex('params.solution') == 'standard') ? "modules/PayPalPro/standard_checkout.tpl" : $this->formTemplate;
	} // }}}
		
	function getPhone($cart, $type = "a") // {{{  
	{
		if (empty($this->phone)) {
			$phone = preg_replace('/[ ()-]/',"",$cart->getComplex('profile.billing_phone'));
            $isUS = ($cart->getComplex('profile.billing_country') == "US");
            $this->phone['a'] = $isUS ? substr($phone, -10, -7) : "";
            $this->phone['b'] = $isUS ? substr($phone, -7, -4) : $phone;
            $this->phone['c'] = $isUS ? substr($phone, -4) : "";
		}
		return $this->phone[$type];		
	} // }}}

	function getItemName($cart) // {{{ 
	{
		return $this->config->getComplex('Company.company_name'). " order #".$cart->get("order_id");
	} // }}}

	function getIpAddress() // {{{
	{
		 return !empty($_ENV['REMOTE_ADDR']) ? $_ENV['REMOTE_ADDR'] : "127.0.0.1";

	} // }}}

	function getCreditCardType() // {{{ 
	{
		switch ($this->cc_info['cc_type']) {
			case "VISA" : return 'Visa';
			case "MC" 	: return 'MasterCard';
			case "AMEX" : return 'Amex';
			case "DISC"	: return 'Discover';
        }
	} // }}}	
	
	function getBillingState($order)
	{
		$billingState = $order->getComplex('profile.billingState.code');
		if (empty($billingState)) {
			return "International";
		}

		$country = $order->getComplex('profile.billing_country');
		$billingState = ($country == "US" || $country == "CA") ? "code" : "state";
		return $order->get("profile.billingState." . $billingState);
	}

	function getShippingState($order)
	{
		$shippingState = $order->getComplex('profile.shippingState.code');
		if (empty($shippingState)) {
			return "International";
		}

		$country = $order->getComplex('profile.shipping_country');
		$shippingState = ($country == "US" || $country == "CA") ? "code" : "state";
		return $order->get("profile.shippingState." . $shippingState);
	}

	function getDirectPaymentRequest($order) // {{{
	{
		$profile 	= $order->getComplex('profile.properties');
		$card	 	= $this->cc_info;
		$cart	 	= $order->get("properties");
		$payment	= $this->get("params");
		$payment	= $payment['pro'];	
		$notifyUrl	= $this->xlite->getShopUrl("cart.php?target=callback&action=callback");
		$invoiceId	= $payment['prefix'].$cart['order_id'];
		
		$paymentAction 		= ($payment['type']) ? 'Sale' : 'Authorization';
		$ipAddress			= $this->get("ipAddress");
		$billingState       = $this->getBillingState($order);
		$shippingState       = $this->getShippingState($order);
		$card['cc_month'] 	= substr($card['cc_date'],0,2);
        $card['cc_year']  	= 2000 + substr($card['cc_date'],2,2);
		$card['cc_type']	= $this->getCreditCardType();

		$s_name = "";
		if (!empty($profile['shipping_firstname'])) {
			$s_name = $profile['shipping_firstname'];
		} elseif (!empty($profile['billing_firstname'])) {
			$s_name = $profile['billing_firstname'];
		}

		if (!empty($profile['shipping_lastname'])) {
			$s_name .= (empty($s_name) ? "" : " ").$profile['shipping_lastname'];
		} elseif (!empty($profile['billing_lastname'])) {
			$s_name .= (empty($s_name) ? "" : " ").$profile['billing_lastname'];
		}

		if (!empty($s_name)) {
			$s_name = substr($s_name, 0, 32);
		}

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
    <DoDirectPaymentReq xmlns="urn:ebay:api:PayPalAPI">
      <DoDirectPaymentRequest>
        <Version xmlns="urn:ebay:apis:eBLBaseComponents">1.00</Version>
        <DoDirectPaymentRequestDetails xmlns="urn:ebay:apis:eBLBaseComponents">
          <PaymentAction>$paymentAction</PaymentAction>
          <PaymentDetails>
            <OrderTotal currencyID="$payment[currency]">$cart[total]</OrderTotal>
            <ButtonSource>Litecommerce PayPalPro Payment</ButtonSource>
            <NotifyURL>$notifyUrl</NotifyURL>
            <InvoiceID>$invoiceId</InvoiceID>
			<ShipToAddress>
              <Name>$s_name</Name>
              <Street1>$profile[shipping_address]</Street1>
              <CityName>$profile[shipping_city]</CityName>
              <StateOrProvince>$shippingState</StateOrProvince>
              <Country>$profile[shipping_country]</Country>
              <PostalCode>$profile[shipping_zipcode]</PostalCode>
            </ShipToAddress>
          </PaymentDetails>
          <CreditCard>
            <CreditCardType>$card[cc_type]</CreditCardType>
            <CreditCardNumber>$card[cc_number]</CreditCardNumber>
            <ExpMonth>$card[cc_month]</ExpMonth>
            <ExpYear>$card[cc_year]</ExpYear>
            <CardOwner>
              <PayerStatus>verified</PayerStatus>
              <Payer>$profile[login]</Payer>
              <PayerName>
                <FirstName>$profile[billing_firstname]</FirstName>
                <LastName>$profile[billing_lastname]</LastName>
              </PayerName>
              <PayerCountry>$profile[billing_country]</PayerCountry>
              <Address>
                <Street1>$profile[billing_address]</Street1>
                <CityName>$profile[billing_city]</CityName>
                <StateOrProvince>$billingState</StateOrProvince>
                <Country>$profile[billing_country]</Country>
                <PostalCode>$profile[billing_zipcode]</PostalCode>
              </Address>
            </CardOwner>
            <CVV2>$card[cc_cvv2]</CVV2>
          </CreditCard>
          <IPAddress>$ipAddress</IPAddress>
        </DoDirectPaymentRequestDetails>
      </DoDirectPaymentRequest>
    </DoDirectPaymentReq>
  </soap:Body>
</soap:Envelope>
EOT;
	} // }}} 

	function getStandardUrl() // {{{ 
	{
		return	$this->getComplex('params.standard.mode') ? "https://www.paypal.com/cgi-bin/webscr" : "https://www.sandbox.paypal.com/cgi-bin/webscr";
	} // }}} 	
} // }}}
