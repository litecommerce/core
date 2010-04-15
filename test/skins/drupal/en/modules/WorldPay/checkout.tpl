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
<form action="{cart.paymentMethod.getWorldPayURL()}" method="POST" name="WorldPay_form">

<input type=hidden name="instId" value="{cart.paymentMethod.params.inst_id:r}">
<input type=hidden name="cartId" value="{cart.paymentMethod.getCartId(cart.get(#order_id#))}">
<input type=hidden name="amount" value="{cart.paymentMethod.formatTotal(cart.get(#total#))}">
<input type=hidden name="currency" value="{cart.paymentMethod.params.currency:r}">
<input type=hidden name="desc" value="{cart.getDescription():r}">
<input type=hidden name="testMode" value="{cart.paymentMethod.getTestMode()}">

<input type=hidden name="name" value="{cart.paymentMethod.getNameField(cart):r}">
<input type=hidden name="address" value="{cart.profile.get(#billing_address#):r}, {cart.profile.get(#billing_city#)}, {cart.profile.get(#billingState.state#):r}, {cart.profile.get(#billing_country#):r}">
<input type=hidden name="postcode" value="{cart.profile.get(#billing_zipcode#):r}">
<input type=hidden name="country" value="{cart.profile.get(#billing_country#):r}">
<input type=hidden name="tel" value="{cart.profile.get(#billing_phone#):r}">
<input type=hidden name="email" value="{cart.profile.get(#login#):r}">
{if:cart.paymentMethod.params.preauth}
<!-- pre-auth mode -->
<input type=hidden name="authMode" value="E">
{end:}
{if:cart.paymentMethod.params.md5HashValue}
<input type=hidden name="signatureFields" value="amount:currency:cartId">
<input type=hidden name="signature" value="{cart.paymentMethod.getMD5Signature(cart)}">
{end:}

<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order">
</form>
