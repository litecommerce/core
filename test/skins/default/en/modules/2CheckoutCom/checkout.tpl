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
<form action="{cart.paymentMethod.params.url}" method="POST" name="2checkout_form" IF="cart.paymentMethod.params.version=#1#">

<input type="hidden" name="secureNumber" value="{cart.paymentMethod.createSecureNumber(cart)}">
<input type="hidden" name="x_invoice_num" value="{cart.order_id}">
<input type="hidden" name="x_login" value="{cart.paymentMethod.params.login:r}">
<input type="hidden" name="x_amount" value="{cart.total}">
<input type="hidden" name="x_First_Name" value="{cart.profile.billing_firstname:r}">
<input type="hidden" name="x_Last_Name" value="">
<input type="hidden" name="x_Phone" value="{cart.profile.billing_phone:r}">
<input type="hidden" name="x_Email" value="{cart.profile.login:r}">
<input type="hidden" name="x_Address" value="{cart.profile.billing_address:r}">
<input type="hidden" name="x_City" value="{cart.profile.billing_city:r}">
<input type="hidden" name="x_State" value="{cart.profile.billingState.code:r}">
<input type="hidden" name="x_Zip" value="{cart.profile.billing_zipcode:r}">
<input type="hidden" name="x_Country" value="{cart.profile.billingCountry.country:r}">
<input type="hidden" name="x_Ship_To_Address" value="{cart.profile.shipping_address:r}">
<input type="hidden" name="x_Ship_To_City" value="{cart.profile.shipping_city:r}">
<input type="hidden" name="x_Ship_To_Country" value="{cart.profile.shipping_country:r}">
<input type="hidden" name="x_Ship_To_First_Name" value="{cart.profile.shipping_firstname:r}">
<input type="hidden" name="x_Ship_To_Last_Name" value="{cart.profile.shipping_lastname:r}">
<input type="hidden" name="x_Ship_To_State" value="{cart.profile.shippingState.code:r}">
<input type="hidden" name="x_Ship_To_Zip" value="{cart.profile.shipping_zipcode:r}">
<input type="hidden" name="x_Description" value="{cart.description:r}">

<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order">
</form>

<span IF="cart.paymentMethod.params.version=#2#">

<script language="Javascript">
<!--

function SubmitHandler()
{
	var Element = document.getElementById("submit_order_button");
	if (Element) {
		Element.disabled = true;
	}
	var Element = document.getElementById("submiting_process");
	if (Element) {
		Element.style.display = "";
		Element.innerHTML = "Please wait while connecting to <b>2checkout.com</b> payment gateway...";
	}

	window.scroll(0, 100000);

	return false;
}

-->
</script>


<form action="https://www2.2checkout.com/2co/buyer/purchase" method="POST" name="2checkout_form" onSubmit="SubmitHandler()">

<input type="hidden" name=sid value="{cart.paymentMethod.params.account_number:r}"> 
<input type="hidden" name=total value="{cart.total}">
<input type="hidden" name=quantity value="1">
<input type="hidden" name=fixed value="Y">
<input type="hidden" name=cart_order_id value="{cart.order_id}">
<input type="hidden" name=card_holder_name value="{cart.profile.billing_firstname:r} {cart.profile.billing_lastname:r}">
<input type="hidden" name=street_address value="{cart.profile.billing_address:r}">
<input type="hidden" name=city value="{cart.profile.billing_city:r}">
<input type="hidden" name=state value="{cart.profile.billingState.code:r}">
<input type="hidden" name=zip value="{cart.profile.billing_zipcode:r}">
<input type="hidden" name=country value="{cart.profile.billingCountry.country:r}">
<input type="hidden" name=email value="{cart.profile.login:r}">
<input type="hidden" name=phone value="{cart.profile.billing_phone:r}">
<input type="hidden" name=ship_name value="{cart.profile.shipping_firstname:r} {cart.profile.shipping_lastname:r}">
<input type="hidden" name=ship_address value="{cart.profile.shipping_address:r}">
<input type="hidden" name=ship_city value="{cart.profile.shipping_city:r}">
<input type="hidden" name=ship_state value="{cart.profile.shippingState.code:r}">
<input type="hidden" name=ship_zip value="{cart.profile.shipping_zipcode:r}">
<input type="hidden" name=ship_country value="{cart.profile.shipping_country:r}">
<span IF="cart.paymentMethod.params.test_mode=#Y#">
<input type="hidden" name=demo value="Y">
</span>
<input type=hidden name=id_type value="1">
<span IF="cart.shippingAvailable">
<input type=hidden name=sh_cost value="{cart.shipping_cost}">
</span>
<span FOREACH="cart.items,cart_id,item">
<span IF="item.gcid">
<input type="hidden" name="{cart.paymentMethod.fieldName(#c_prod#,cart_id)}" value="{item.gcid},{item.amount}">
</span>
<span IF="!item.gcid">
<input type="hidden" name="{cart.paymentMethod.fieldName(#c_prod#,cart_id)}" value="{item.product.product_id},{item.amount}">
</span>
<input type="hidden" name="{cart.paymentMethod.fieldName(#c_name#,cart_id)}" value="{truncate(cart.paymentMethod.stripSpecials(item.name),#127#):h}">
<span IF="item.product.description">
<input type="hidden" name="{cart.paymentMethod.fieldName(#c_description#,cart_id)}" value="{truncate(cart.paymentMethod.stripSpecials(item.product.description),#254#):h}">
</span>
<span IF="!item.product.description">
<span IF="item.product.brief_description">
<input type="hidden" name="{cart.paymentMethod.fieldName(#c_description#,cart_id)}" value="{truncate(cart.paymentMethod.stripSpecials(item.product.brief_description),#254#):h}">
</span>
<span IF="!item.product.brief_description">
<input type="hidden" name="{cart.paymentMethod.fieldName(#c_description#,cart_id)}" value="{truncate(cart.paymentMethod.stripSpecials(item.name),#254#):h}">
</span>
</span>
<input type="hidden" name="{cart.paymentMethod.fieldName(#c_price#,cart_id)}" value="{cart.paymentMethod.price(item.price)}">
<span IF="item.gcid">
<input type="hidden" name="{cart.paymentMethod.fieldName(#c_tangible#,cart_id)}" value="N">
</span>
<span IF="!item.gcid">
<span IF="item.product.egood">
<input type="hidden" name="{cart.paymentMethod.fieldName(#c_tangible#,cart_id)}" value="N">
</span>
<span IF="!item.product.egood">
<input type="hidden" name="{cart.paymentMethod.fieldName(#c_tangible#,cart_id)}" value="Y">
</span>
</span>
</span>

<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order" id="submit_order_button">
</form>

<span id="submiting_process" style="display:none"></span>

</span>
