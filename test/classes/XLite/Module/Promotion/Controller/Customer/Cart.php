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
* Module_Promotion_Dialog_cart description.
*
* @package Module_Promotion
* @access public
* @version $Id$
*/
class XLite_Module_Promotion_Controller_Customer_Cart extends XLite_Controller_Customer_Cart implements XLite_Base_IDecorator
{	
	public $discountCouponResult = false;

	function handleRequest()
	{
		$discountCoupon = $this->cart->get("DC");
		if ($discountCoupon)
			if (!$discountCoupon->checkCondition($this->cart)) {
				$dc = $this->cart->get("DC");
            	$this->session->set("couponFailed", $dc->get("coupon"));
	            $this->cart->set("DC", null); // remove coupon
	            $this->updateCart();
				$this->redirect("cart.php?target=checkout&mode=couponFailed");
	            return;
			}
		
        if ($this->get("target") == 'cart') {
		    $this->session->set("bonusListDisplayed", null);
        }
		parent::handleRequest();
	}
	
    function action_discount_coupon_delete()
	{
		$this->cart->set("DC", null);
        $this->updateCart();
	}
	
    function action_discount_coupon()
    {
        $this->coupon = addSlashes(trim($this->coupon));
		$this->discountCouponResult = $this->cart->validateDiscountCoupon($this->coupon);
		$dc = new XLite_Module_Promotion_Model_DiscountCoupon();
		$found = $dc->find("coupon='".$this->coupon."' AND order_id='0'");
        if ($this->discountCouponResult||!$dc->checkCondition($this->cart)) {
            $this->valid = false;
            // show error message
            $this->session->set("couponFailed", $dc->get("coupon"));
            $this->redirect("cart.php?target=checkout&mode=couponFailed");
            return;
        }

        if($found) {
            $this->cart->set("DC", $dc);
            $this->updateCart();
        } else {
            $this->doDie("Internal error: DC not found");
        }
    }
	
	function isShowDCForm()
	{
		return is_null($this->cart->get("DC")) && !$this->cart->is("empty") && $this->config->getComplex('Promotion.allowDC');
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
