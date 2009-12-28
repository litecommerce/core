<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
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
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* PaymentMethod paypal. This implementation complies the following 
* documentation: <br>
* IPN Manual:
*   https://www.paypal.com/html/ipn.pdf <br>
* Buy Now Button Manual:
*   https://www.paypal.com/html/single_item.pdf <br>
* Multiple Currencies: 
*   https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_intro-outside
*
* @package Module_PayPal
* @access public
* @version $Id$
*/
class XLite_Module_PayPal_Model_PaymentMethod_Paypal extends XLite_Model_PaymentMethod
{
    var $pendingReasons = array(
        'echeck' => 'The payment is pending because it was made by an eCheck, which has not yet cleared',
        'multi_currency' => 'You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment',
        'intl' => 'The payment is pending because you, the merchant, hold an international account and do not have a withdrawal method.  You must manually accept or deny this payment from your Account Overview',
        'verify' => 'The payment is pending because you, the merchant, are not yet verified. You must verify your account before you can accept this payment',
        'address' => 'The payment is pending because your customer did not include a confirmed shipping address and you, the merchant, have your Payment Receiving Preferences set such that you want to manually accept or deny each of these payments.  To change your preference, go to the Preferences section of your Profile',
        'upgrade' => 'The payment is pending because it was made via credit card and you, the merchant, must upgrade your account to Business or Premier status in order to receive the funds',
        'unilateral' => 'The payment is pending because it was made to an email address that is not yet registered or confirmed',
        'other' => 'The payment is pending for some reason. For more information, contact PayPal customer service'
        );

    var $configurationTemplate = "modules/PayPal/config.tpl";
    var $formTemplate = "modules/PayPal/checkout.tpl";
    var $processorName = "PayPal";

    function handleRequest(&$order)
    {
        require_once "modules/PayPal/encoded.php";
        PaymentMethod_paypal_handleRequest($this, $order);
    }

    function parsePhone($profile)
    {
        $phone = $profile->get("billing_phone");
        $phone = preg_replace("/[ ()-]/", "", $phone);
        return $phone;
    }
    
    function getDayPhoneA($profile) 
    {
        $phone = $this->parsePhone($profile);
        return substr($phone, -10,-7);
    }

    function getDayPhoneB($profile) 
    {
        $phone = $this->parsePhone($profile);
        return substr($phone, -7, -4);
    }

    function getDayPhoneC($profile) 
    {
        $phone = $this->parsePhone($profile);
        return substr($phone, -4);
    }

    function getItemName($order)
    {
        return $this->config->get("Company.company_name") . " order #" . $order->get("order_id");
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
