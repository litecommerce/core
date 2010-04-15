{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script language="Javascript">
<!--

function SubmitHandler(formName)
{
	var Element = document.getElementById("submiting_process");
	if (Element) {
		Element.style.display = "";
		Element.innerHTML = "Please wait while connecting to <b>PayPal</b> payment gateway...";
	}
	window.scroll(0, 100000);
    eval("document." + formName + ".submit()");
}

-->
</script>
<span IF="!cart.paymentMethod.params.standard.use_queued"> 
<form action="{cart.paymentMethod.standardUrl:r}" method="POST" name="paypal_standard">
<input type=hidden name=cmd 		value="_ext-enter">
<input type=hidden name=invoice 	value="{cart.paymentMethod.params.standard.prefix:r}{cart.order_id}">
<input type=hidden name=redirect_cmd 	value="_xclick">
<input type=hidden name=mrb 		value="R-2JR83330TB370181P">
<input type=hidden name=pal 		value="RDGQCFJTT6Y6A">
<input type=hidden name=rm 			value=2>
<input type=hidden name=email 	value="{cart.profile.login:r}">
<input type=hidden name=first_name 	value="{cart.profile.billing_firstname:r}">
<input type=hidden name=last_name 	value="{cart.profile.billing_lastname:r}">
<input type=hidden name=address1 	value="{cart.profile.billing_address:r}">
<input type=hidden name=city 		value="{cart.profile.billing_city:r}">
<input type=hidden name=state 		value="{cart.paymentMethod.getBillingState(cart):r}">
<input type=hidden name=country 	value="{cart.profile.billing_country:r}">
<input type=hidden name=zip 		value="{cart.profile.billing_zipcode:r}">
<input type=hidden name=day_phone_a 	value="{cart.paymentMethod.phone(cart,#a#):r}">
<input type=hidden name=day_phone_b 	value="{cart.paymentMethod.phone(cart,#b#):r}">
<input type=hidden name=day_phone_c 	value="{cart.paymentMethod.phone(cart,#c#):r}">
<input type=hidden name=night_phone_a	value="{cart.paymentMethod.phone(cart,#a#):r}">
<input type=hidden name=night_phone_b	value="{cart.paymentMethod.phone(cart,#b#):r}">
<input type=hidden name=night_phone_c	value="{cart.paymentMethod.phone(cart,#c#):r}">
<input type=hidden name=business 	value="{cart.paymentMethod.params.standard.login:r}">
<input type=hidden name=item_name 	value="{cart.paymentMethod.getItemName(cart):r}">
<input type=hidden name=amount 		value="{cart.total}">
<input type=hidden name=currency_code 	value="{cart.paymentMethod.params.standard.currency}">
<input type=hidden name=bn 		value="x-cart">
<input type=hidden name=return 		value="{getShopUrl(#cart.php?target=checkout#,config.Security.customer_security)}&amp;action=return&amp;order_id={cart.order_id:r}">
<input type=hidden name=cancel_return	value="{getShopUrl(#cart.php?target=checkout#,config.Security.customer_security)}">
<input type=hidden name=notify_url	value="{getShopUrl(#cart.php?target=callback#)}&amp;action=callback&amp;order_id={cart.order_id}">
<input type=hidden name=image_url	value="{cart.paymentMethod.params.standard.logo}">

<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".
</p>
<div id="submiting_process">
<widget class="XLite_View_Button" label="Submit order" href="javascript: SubmitHandler('paypal_standard');">
</div>
</form>
</span>

<span IF="cart.paymentMethod.params.standard.use_queued">
<form action="cart.php" method="POST" name="paypal_form">
<input type="hidden" name="target" value="standard_checkout">
<input type="hidden" name="action" value="redirect">
<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<div id="submiting_process">
<widget class="XLite_View_Button" label="Submit order" href="javascript: SubmitHandler('paypal_form');">
</div>
</form>
</span>
