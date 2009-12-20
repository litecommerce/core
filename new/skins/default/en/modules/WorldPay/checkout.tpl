<form action="{dialog.cart.paymentMethod.getWorldPayURL()}" method="POST" name="WorldPay_form">

<input type=hidden name="instId" value="{dialog.cart.paymentMethod.params.inst_id:r}">
<input type=hidden name="cartId" value="{dialog.cart.paymentMethod.getCartId(dialog.cart.get(#order_id#))}">
<input type=hidden name="amount" value="{dialog.cart.paymentMethod.formatTotal(dialog.cart.get(#total#))}">
<input type=hidden name="currency" value="{dialog.cart.paymentMethod.params.currency:r}">
<input type=hidden name="desc" value="{dialog.cart.getDescription():r}">
<input type=hidden name="testMode" value="{dialog.cart.paymentMethod.getTestMode()}">

<input type=hidden name="name" value="{dialog.cart.paymentMethod.getNameField(dialog.cart):r}">
<input type=hidden name="address" value="{dialog.cart.profile.get(#billing_address#):r}, {dialog.cart.profile.get(#billing_city#)}, {dialog.cart.profile.get(#billingState.state#):r}, {dialog.cart.profile.get(#billing_country#):r}">
<input type=hidden name="postcode" value="{dialog.cart.profile.get(#billing_zipcode#):r}">
<input type=hidden name="country" value="{dialog.cart.profile.get(#billing_country#):r}">
<input type=hidden name="tel" value="{dialog.cart.profile.get(#billing_phone#):r}">
<input type=hidden name="email" value="{dialog.cart.profile.get(#login#):r}">
{if:dialog.cart.paymentMethod.params.preauth}
<!-- pre-auth mode -->
<input type=hidden name="authMode" value="E">
{end:}
{if:dialog.cart.paymentMethod.params.md5HashValue}
<input type=hidden name="signatureFields" value="amount:currency:cartId">
<input type=hidden name="signature" value="{dialog.cart.paymentMethod.getMD5Signature(dialog.cart)}">
{end:}

<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order">
</form>

