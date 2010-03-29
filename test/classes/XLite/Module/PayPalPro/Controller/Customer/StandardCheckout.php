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
* 
*
* @package
* @access public
* @version $Id$
*/
class XLite_Module_PayPalPro_Controller_Customer_StandardCheckout extends XLite_Controller_Customer_Checkout implements XLite_Base_IDecorator
{	
	public $registerForm = null;

	public function __construct(array $params)
	{
		parent::__construct($params);
		if (($_REQUEST["target"] == "checkout") && (isset($_REQUEST["paypal_result"]) && $_REQUEST["paypal_result"] == "cancel")) {
			$this->cart = XLite_Model_Cart::getInstance();
			if ($this->cart->get("status") == "Q") {
				// revert back to I if the payment is cancelled at the PayPal side
				$this->cart->set("status", "I");
				$this->updateCart();
			}
		}
	}

	function init() // {{{
	{
    	if ($_REQUEST["target"] == "standard_checkout") {
			$this->registerForm = new XLite_Base();
		}
		parent::init();									  
	} // }}}
	
	function action_redirect() // {{{ 
	{
    	$itemsBeforeUpdate = $this->cart->get("itemsFingerprint");
		$this->cart->set("status", "Q");
        $this->updateCart();
    	$itemsAfterUpdate = $this->cart->get("itemsFingerprint");
		if ($this->get("absence_of_product") || $this->cart->isEmpty() || $itemsAfterUpdate != $itemsBeforeUpdate) {
			$this->cart->set("status", "I");
			$this->updateCart();
			$this->session->set("order_id", $this->cart->get("order_id"));
			$this->set("absence_of_product", true);
			$this->set("returnUrl", "cart.php?target=cart");
			$this->redirect();
			return;
		}
		$pm = $this->cart->get("PaymentMethod");
		$profile = $this->cart->get("profile");
?>
<HTML>
<BODY onload="javascript: document.paypal_form.submit();">
<FORM name="paypal_form" action="<?php echo $pm->get("standardUrl"); ?>" method="POST">
<INPUT type=hidden name=cmd value="_ext-enter">
<INPUT type=hidden name=invoice value="<?php echo $pm->getComplex('params.standard.prefix'); ?><?php echo $this->cart->get("order_id"); ?>">
<INPUT type=hidden name=redirect_cmd value="_xclick">
<INPUT type=hidden name=mrb value="R-2JR83330TB370181P">
<INPUT type=hidden name=pal value="RDGQCFJTT6Y6A">
<INPUT type=hidden name=rm value="2">
<INPUT type=hidden name="email" value="<?php echo $profile->get("login"); ?>">
<INPUT type=hidden name=first_name value="<?php echo $profile->get("billing_firstname"); ?>">
<INPUT type=hidden name=last_name value="<?php echo $profile->get("billing_lastname"); ?>">
<INPUT type=hidden name=address1 value="<?php echo $profile->get("billing_address"); ?>">
<INPUT type=hidden name=city value="<?php echo $profile->get("billing_city"); ?>">
<INPUT type=hidden name=state value="<?php echo $pm->getBillingState($this->cart); ?>">
<INPUT type=hidden name=country value="<?php echo $profile->get("billing_country"); ?>">
<INPUT type=hidden name=zip value="<?php echo $profile->get("billing_zipcode"); ?>">
<INPUT type=hidden name=day_phone_a value="<?php echo $pm->getPhone($this->cart,"a"); ?>">
<INPUT type=hidden name=day_phone_b value="<?php echo $pm->getPhone($this->cart,"b"); ?>">
<INPUT type=hidden name=day_phone_c value="<?php echo $pm->getPhone($this->cart,"c"); ?>">
<INPUT type=hidden name=night_phone_a value="<?php echo $pm->getPhone($this->cart,"a"); ?>"> 
<INPUT type=hidden name=night_phone_b value="<?php echo $pm->getPhone($this->cart,"b"); ?>">
<INPUT type=hidden name=night_phone_c value="<?php echo $pm->getPhone($this->cart,"c"); ?>">
<INPUT type=hidden name=business value="<?php echo $pm->getComplex('params.standard.login'); ?>">
<INPUT type=hidden name=item_name value="<?php echo $pm->getItemName($this->cart); ?>">
<INPUT type=hidden name=amount value="<?php echo $this->cart->get("total"); ?>">
<INPUT type=hidden name=currency_code value="<?php echo $pm->getComplex('params.standard.currency'); ?>">
<INPUT type="hidden" name="bn" value="x-cart">
<INPUT type=hidden name=return value="<?php echo $this->getShopUrl("cart.php?target=checkout", $this->getComplex('config.Security.customer_security')); ?>&action=return&order_id=<?php echo $this->cart->get("order_id"); ?>">
<INPUT type=hidden name=cancel_return value="<?php echo $this->getShopUrl("cart.php?target=checkout", $this->getComplex('config.Security.customer_security')); ?>&paypal_result=cancel">
<INPUT type=hidden name=notify_url value="<?php echo $this->getShopUrl("cart.php?target=callback"); ?>&action=callback&order_id=<?php echo $this->cart->get("order_id"); ?>">
<INPUT type=hidden name=image_url value="<?php echo $pm->getComplex('params.standard.logo'); ?>">
<CENTER>Please wait while connecting to <b>PayPal</b> payment gateway...</CENTER>
</FORM>
</BODY>
</HTML>
<?php
		exit;		
	} // }}} 

    function isSecure() // {{{ 
    {
		return $this->getComplex('config.Security.customer_security');
	} // }}} 
						
} // }}} 

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
