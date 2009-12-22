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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class for credit card payment.
*
* @package Kernel
* @access public
* @version $Id$
*/
class PaymentMethod_credit_card extends PaymentMethod
{
    var $formTemplate = "checkout/credit_card.tpl";
    var $secure = true;

    function process(&$cart)
    {
        // save CC details to order
        $cart->set("details", $this->cc_info);
		$detailLabels = array(	"cc_number" => "Credit card number",
                 			  	"cc_type" => "Credit card type",
                    			"cc_name" => "Cardholder's name",
                    			"cc_date" => "Expiration date",
				                "cc_cvv2" => "Credit Card Code");

		if($this->cc_info["cc_type"]=='SW'||$this->cc_info["cc_type"]=='SO') {
			$detailLabels["cc_start_date"] = "Start date";
			$detailLabels["cc_issue"] = "Issue no.";
		}

        $cart->set("detailLabels", $detailLabels);
        $cart->set("status", "Q");
        $cart->update();
    }

    function getPaymentInfo()
    {
        return (isset($_POST["cc_info"]) ? $_POST["cc_info"] : '');
    }

    function handleRequest(&$cart)
    {
        $this->cc_info = $this->getPaymentInfo();
        $this->process($cart);
        $status = $cart->get("status");
        if ($status == 'Q' || $status == 'P') {
            return PAYMENT_SUCCESS;
        } else {
            return PAYMENT_FAILURE;
        }
    }

    function getCardTypes()
    {
        $c = func_new("Card");
        return $c->findAll();
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
