<form action="{dialog.cart.paymentMethod.params.param09}" method="POST" name="HSBC_form">
<input type=hidden name=CpiDirectResultUrl value="{dialog.cart.paymentMethod.getResultURL()}">
<input type=hidden name=CpiReturnUrl value="{dialog.cart.paymentMethod.getReturnURL(dialog.cart)}">
<input type=hidden name=MerchantData value="{dialog.cart.paymentMethod.getMerchantData(dialog.cart)}">
<input type=hidden name=Mode value="{dialog.cart.paymentMethod.getHsbcMode()}">
<input type=hidden name=OrderDesc value="LiteCommerce order">
<input type=hidden name=OrderId value="{dialog.cart.get(#order_id#)}">
<input type=hidden name=PurchaseAmount value="{dialog.cart.paymentMethod.getTotalCost(dialog.cart)}">
<input type=hidden name=PurchaseCurrency value="{dialog.cart.paymentMethod.getCurrency()}">
<input type=hidden name=StorefrontId value="{dialog.cart.paymentMethod.getStoreFrontId()}">
<input type=hidden name=TimeStamp value="{dialog.cart.paymentMethod.getTimeStamp()}">
<input type=hidden name=TransactionType value="{dialog.cart.paymentMethod.getTransactionType()}">
<input type=hidden name=UserId value="{dialog.cart.paymentMethod.getUserId(dialog.cart)}">

<input type=hidden name=BillingAddress1 value="{dialog.cart.profile.get(#billing_address#)}">
<input type=hidden name=BillingCity value="{dialog.cart.profile.get(#billing_city#)}">
<input type=hidden name=BillingCountry value="{dialog.cart.paymentMethod.getIsoCode(#billing_country#,dialog.cart)}">
<input type=hidden name=BillingCounty value="{dialog.cart.paymentMethod.getBillingState(dialog.cart)}">
<input type=hidden name=BillingFirstName value="{dialog.cart.profile.get(#billing_firstname#)}">
<input type=hidden name=BillingLastName value="{dialog.cart.profile.get(#billing_lastname#)}">
<input type=hidden name=BillingPostal value="{dialog.cart.profile.get(#billing_zipcode#)}">
<input type=hidden name=ShopperEmail value="{dialog.cart.profile.get(#login#)}">

<input type=hidden name=ShippingAddress1 value="{dialog.cart.profile.get(#shipping_address#)}">
<input type=hidden name=ShippingCity value="{dialog.cart.profile.get(#shipping_city#)}">
<input type=hidden name=ShippingCountry value="{dialog.cart.paymentMethod.getIsoCode(#shipping_country#,dialog.cart)}">
<input type=hidden name=ShippingCounty value="{dialog.cart.paymentMethod.getShippingState(dialog.cart)}">
<input type=hidden name=ShippingFirstName value="{dialog.cart.profile.get(#shipping_firstname#)}">
<input type=hidden name=ShippingLastName value="{dialog.cart.profile.get(#shipping_lastname#)}">
<input type=hidden name=ShippingPostal value="{dialog.cart.profile.get(#shipping_zipcode#)}">

<input type=hidden name=OrderHash value="{dialog.cart.paymentMethod.getHash(dialog.cart):h}">
<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order">
</form>
