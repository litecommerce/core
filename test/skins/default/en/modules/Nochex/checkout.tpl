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
<script language="Javascript" type="text/javascript">
function CheckoutSubmit()
{
    var Element = document.getElementById("submit_order_button");
    if (Element) {
        Element.style.display = 'none';
    }

    var Element = document.getElementById("submiting_process");
    if (Element) {
        Element.style.display = "";
        Element.innerHTML = "<b>Please wait while your order is being processed...</b>";
    }

	window.scroll(0, 100000);
    document.nochex_checkout.submit();
}
</script>
<form action="https://secure.nochex.com/" method="POST" name="nochex_checkout">
<input type="hidden" name="merchant_id" value="{cart.paymentMethod.params.param01}">
<input type="hidden" name="amount" value="{cart.paymentMethod.getTotal(cart)}">
<input type="hidden" name="order_number" value="{cart.paymentMethod.params.param04}{cart.order_id}">
{if:cart.paymentMethod.params.param03=#T#}
<input type="hidden" name="test_transaction" value="100">
<input type="hidden" name="test_success_url" value="{cart.paymentMethod.getReturnURL(cart):h}">
{else:}
<input type="hidden" name="success_url" value="{cart.paymentMethod.getReturnURL(cart):h}">
{end:}
<input type="hidden" name="billing_firstname" value="{cart.profile.billing_firstname:r} {cart.profile.billing_lastname:r}">
<input type="hidden" name="billing_address" value="{cart.profile.billing_address:r}, {cart.profile.billing_city:r}, {cart.profile.billingState.state:r}">
<input type="hidden" name="billing_postcode" value="{cart.profile.billing_zipcode:r}">
<input type="hidden" name="delivery_firstname" value="{cart.profile.shipping_firstname:r} {cart.profile.shipping_lastname:r}">
<input type="hidden" name="delivery_address" value="{cart.profile.shipping_address:r}, {cart.profile.shipping_city:r}, {cart.profile.shippingState.state:r}">
<input type="hidden" name="delivery_postcode" value="{cart.profile.shipping_zipcode:r}">
<input type="hidden" name="email_address" value="{cart.profile.login:r}">
<input type="hidden" name="customer_phone_number" value="{cart.profile.billing_phone:r}">
<input type="hidden" name="cancel_url" value="{cart.paymentMethod.getReturnURL(cart):h}">
<input type="hidden" name="callback_url" value="{cart.paymentMethod.getResponderURL(cart):h}">
<p>By clicking "SUBMIT" you agree with our "<a href='cart.php?target=help&amp;mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&amp;mode=privacy_statement'><u>Privacy statement</u></a>".<br>
<span id="submit_order_button">
<widget class="XLite_View_Button" label="Submit order" href="javascript: CheckoutSubmit();">
</span>
<span id="submiting_process" style="display:none"></span>
</form>
