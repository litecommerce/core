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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Class description.
*
* @package $Package$
* @version $Id$
*/
class XLite_Module_GoogleCheckout_Controller_Customer_Gcheckout extends XLite_Controller_Customer_Checkout
{
    function init()
    {
    	$this->registerForm = new XLite_Base();
    	parent::init();
    }

    function handleRequest()
    {
		$gacObject = new XLite_Module_GoogleCheckout_View_GoogleAltCheckout();
        $gacObject->initGoogleData();

        if ($this->action != "checkout" || !isset($gacObject->GCMerchantID)) {
            $this->redirect("cart.php?target=cart");
            return;
        }
        parent::handleRequest();
    }


    function action_checkout()
    {
		// redirect to cart if not allowed for GoogleCheckout
		if (!$this->cart->is("googleAllowPay")) {
			$this->redirect("cart.php?target=cart");
			return;
		}

		if ($this->xlite->get("gcheckout_remove_discounts")) {
			if ($this->xlite->get("PromotionEnabled")) {
				$this->cart->setDC(null);
				$this->cart->set("payedByPoints", 0);
			}

			if ($this->xlite->get("GiftCertificatesEnabled")) {
				$this->cart->setGC(null);
			}
		} else {
			if ($this->xlite->get("PromotionEnabled")) {
				if (!$this->cart->is("googleMeetDiscount")) {
					$this->cart->setDC(null);
				}
			}
		}

		$pm = XLite_Model_PaymentMethod::factory('google_checkout');
		$this->cart->setPaymentMethod($pm);
		$this->updateCart();

		$result = $pm->sendGoogleCheckoutRequest($this->cart);

		if (isset($result["CHECKOUT-REDIRECT"]) && isset($result["CHECKOUT-REDIRECT"]["REDIRECT-URL"]) ) {
			$url = $result["CHECKOUT-REDIRECT"]["REDIRECT-URL"];
			// when PHP5 is used with libxml 2.7.1, HTML entities are stripped from any XML content
			// this is a workaround for https://qa.mandriva.com/show_bug.cgi?id=43486
            if (strpos($url, "shoppingcartshoppingcart") !== false) {
            	$url = str_replace("shoppingcartshoppingcart", "shoppingcart&shoppingcart", $url);
            }
		    $this->set("silent", true);
		    $this->xlite->done();
            header("Location: " . $url);
            exit;
		} else {
			$this->set("valid", false);
			if (isset($result["ERROR"]) && isset($result["ERROR"]["ERROR-MESSAGE"]) ) {
				$this->set("googleError", $result["ERROR"]["ERROR-MESSAGE"]);
			}
		}
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
