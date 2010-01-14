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

/**
* Dialog_vlcheckout description.
*
* @package Module_VerisignLink
* @access public
*/

class XLite_Module_VerisignLink_Controller_Customer_Vlcheckout extends XLite_Controller_Customer_Checkout
{	
    public $registerForm = null;

    function init()
    {
    	if ($_REQUEST["target"] == "vlcheckout") {
    		$this->registerForm = new XLite_Base();
    	}

    	parent::init();

        // go to cart view if cart is empty
		if ($this->target == "vlcheckout" && $this->cart->is("empty")) {
			$this->redirect("cart.php?target=cart");
			return;
		}

		$this->redirect_VerisignLink();
    }

	function redirect_VerisignLink()
	{
		$this->cart->set("status", "Q");
		$this->cart->update();

        $pm = $this->cart->get("PaymentMethod");

?>
<HTML>
<BODY onload="javascript: document.frm.submit();">
<FORM name="frm" action="https://payments.verisign.com/payflowlink" method="POST">
<INPUT type=hidden name="login" value="<?php echo $this->cart->get("paymentMethod.params.login"); ?>">
<INPUT type=hidden name="partner" value="<?php echo $this->cart->get("paymentMethod.params.partner"); ?>">
<INPUT type=hidden name="amount" value="<?php echo $this->cart->get("total"); ?>">
<INPUT type=hidden name=type value=S>
<INPUT type=hidden name=orderform value=true>
<INPUT type=hidden name=method value=cc>
<INPUT type=hidden name=echodata value=true>
<INPUT type=hidden name=showconfirm value=false>
<INPUT type=hidden name=name value="<?php echo $this->cart->get("profile.billing_firstname"); ?> <?php echo $this->cart->get("profile.billing_lastname"); ?>">
<INPUT type=hidden name=nametoship value="<?php echo $this->cart->get("profile.shipping_firstname"); ?> <?php echo $this->cart->get("profile.shipping_lastname"); ?>">
<INPUT type=hidden name=email value="<?php echo $this->cart->get("profile.login"); ?>">
<INPUT type=hidden name=phone value="<?php echo $this->cart->get("profile.billing_phone"); ?>">
<INPUT type=hidden name=emailtoship value="<?php echo $this->cart->get("profile.login"); ?>">
<INPUT type=hidden name=phonetoship value="<?php echo $this->cart->get("profile.shipping_phone"); ?>">
<INPUT type=hidden name="invoice" value="<?php echo $pm->getOrderId($this->cart); ?>">
<INPUT type=hidden name=address value="<?php echo $this->cart->get("profile.billing_address"); ?>">
<INPUT type=hidden name=addresstoship value="<?php echo $this->cart->get("profile.shipping_address"); ?>">
<INPUT type=hidden name=city value="<?php echo $this->cart->get("profile.billing_city"); ?>">
<INPUT type=hidden name=citytoship value="<?php echo $this->cart->get("profile.shipping_city"); ?>">
<INPUT type=hidden name=country value="<?php echo $this->cart->get("profile.billingCountry.code"); ?>">
<INPUT type=hidden name=countrytoship value="<?php echo $this->cart->get("profile.shippingCountry.code"); ?>">
<INPUT type=hidden name=state value="<?php echo $this->cart->get("profile.billingState.code"); ?>">
<INPUT type=hidden name=statetoship value="<?php echo $this->cart->get("profile.shippingState.code"); ?>">
<INPUT type=hidden name=zip value="<?php echo $this->cart->get("profile.billing_zipcode"); ?>">
<INPUT type=hidden name=ziptoship value="<?php echo $this->cart->get("profile.shipping_zipcode"); ?>">
<CENTER>Please wait while connecting to <b>Verisign PayFlow Link</b> payment gateway...</CENTER>
</FORM>
</BODY>
</HTML>
<?php
		$this->success();
		$this->session->writeClose();
		exit;
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
