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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* This module allows to purchase an order by bonus points, either entire or
* partly. It allows to enter number of bonus points during checkout. If
* there is no enought points, it makes you choose payment method again to pay
* for the rest of order.
*
* @package Module_Promotion
* @access public
* @version $Id$
*/
class XLite_Module_Promotion_Model_PaymentMethod_BonusPoints extends XLite_Model_PaymentMethod
{	
    public $processorName = "Promotion/bonus points";	
    public $formTemplate = "modules/Promotion/checkout.tpl";
	
    function handleRequest(XLite_Model_Cart $cart)
    {
		$payedByPoints = $_POST["payedByPoints"];
		$details = $cart->get("details");
		if ($cart->getComplex('origProfile.bonusPoints') < $payedByPoints) {
            $details["error"] = "No enought points";
            $cart->set("details", $details);
            $cart->update();
			return self::PAYMENT_FAILURE;
		}
		$totalBonusPoints = $cart->getTotalBonusPoints();
		if ($totalBonusPoints < $payedByPoints) { // too much
            $details["error"] = "Too much bonus points for this order";
            $cart->set("details", $details);
            $cart->update();
			return self::PAYMENT_FAILURE;
		}

		$cart->set("payedByPoints", min($payedByPoints * $this->config->getComplex('Promotion.bonusPointsCost'), $cart->getMaxPayByPoints()));
		$cart->calcTotals();
		if ($cart->get("total") > 0) {
			$cart->set("payment_method", ""); // choose payment method once again
        	$cart->update();
			header("Location: cart.php?target=checkout&mode=paymentMethod");
            return self::PAYMENT_SILENT;
		} else {
        	$cart->set("status", "P");
        	$cart->update();
            return self::PAYMENT_SUCCESS;
		}
    }

	function is($name)
    {
        if ($name == "enabled" && !$this->xlite->is("adminZone")) {
            if ($this->auth->is("logged")) {
                if ($this->auth->getComplex('profile.bonusPoints') == 0) {
                    // no bonus points, no payment method
                    return false;
                }
            } else {
                return false;
            }
        }
        return parent::is($name);
    }

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
