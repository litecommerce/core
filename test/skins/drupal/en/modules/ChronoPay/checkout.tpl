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
<form action="{cart.paymentMethod.params.url}" method="POST" name="payment_form">
<input type=hidden name=product_id value="{cart.paymentMethod.params.product_id}">
<input type=hidden name=product_name value="{*cart.description:r*}">
<input type=hidden name=product_price value='{cart.total}'>
<input type=hidden name=language value='{cart.paymentMethod.params.language}'>
<input type="hidden" name="product_price_currency" value="USD" />

<input type="hidden" name="name" value="{cart.profile.billing_firstname:r} {cart.profile.billing_lastname:r}">
<input type="hidden" name="street" value="{cart.profile.billing_address:r}" />
<input type="hidden" name="city" value="{cart.profile.billing_city:r}" />
<input type="hidden" name="state" value="{cart.profile.billingState.code:r}" />
<input type="hidden" name="zip" value="{cart.profile.billing_zipcode:r}" />
<input type="hidden" name="country" value="{cart.profile.billingCountry.country:r}" />
<input type="hidden" name="phone" value="{cart.profile.billing_phone:r}" />
<input type="hidden" name="email" value="{cart.profile.login:r}" />
<input type="hidden" name="type" value="P" />

<input type=hidden name="cs1" value="{cart.order_id:r}">
<input type=hidden name="cs2" value="chronopay">
<input type=hidden name=cb_url value="{getShopUrl(#cart.php?target=callback#)}&action=callback&order_id={cart.order_id:r}">
<input type=hidden name=decline_url value="{getShopUrl(#cart.php?target=callback#)}&action=callback&order_id={cart.order_id:r}&error=1">
<input type="hidden" name="cb_type" value="P" />

<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order">
</form>
