<form action="{cart.paymentMethod.params.param09}" method="POST" name="HSBC_form">
<input type=hidden name=CpiDirectResultUrl value="{cart.paymentMethod.getResultURL()}">
<input type=hidden name=CpiReturnUrl value="{cart.paymentMethod.getReturnURL(cart)}">
<input type=hidden name=MerchantData value="{cart.paymentMethod.getMerchantData(cart)}">
<input type=hidden name=Mode value="{cart.paymentMethod.getHsbcMode()}">
<input type=hidden name=OrderDesc value="LiteCommerce order">
<input type=hidden name=OrderId value="{cart.get(#order_id#)}">
<input type=hidden name=PurchaseAmount value="{cart.paymentMethod.getTotalCost(cart)}">
<input type=hidden name=PurchaseCurrency value="{cart.paymentMethod.getCurrency()}">
<input type=hidden name=StorefrontId value="{cart.paymentMethod.getStoreFrontId()}">
<input type=hidden name=TimeStamp value="{cart.paymentMethod.getTimeStamp()}">
<input type=hidden name=TransactionType value="{cart.paymentMethod.getTransactionType()}">
<input type=hidden name=UserId value="{cart.paymentMethod.getUserId(cart)}">

<input type=hidden name=BillingAddress1 value="{cart.profile.get(#billing_address#)}">
<input type=hidden name=BillingCity value="{cart.profile.get(#billing_city#)}">
<input type=hidden name=BillingCountry value="{cart.paymentMethod.getIsoCode(#billing_country#,cart)}">
<input type=hidden name=BillingCounty value="{cart.paymentMethod.getBillingState(cart)}">
<input type=hidden name=BillingFirstName value="{cart.profile.get(#billing_firstname#)}">
<input type=hidden name=BillingLastName value="{cart.profile.get(#billing_lastname#)}">
<input type=hidden name=BillingPostal value="{cart.profile.get(#billing_zipcode#)}">
<input type=hidden name=ShopperEmail value="{cart.profile.get(#login#)}">

<input type=hidden name=ShippingAddress1 value="{cart.profile.get(#shipping_address#)}">
<input type=hidden name=ShippingCity value="{cart.profile.get(#shipping_city#)}">
<input type=hidden name=ShippingCountry value="{cart.paymentMethod.getIsoCode(#shipping_country#,cart)}">
<input type=hidden name=ShippingCounty value="{cart.paymentMethod.getShippingState(cart)}">
<input type=hidden name=ShippingFirstName value="{cart.profile.get(#shipping_firstname#)}">
<input type=hidden name=ShippingLastName value="{cart.profile.get(#shipping_lastname#)}">
<input type=hidden name=ShippingPostal value="{cart.profile.get(#shipping_zipcode#)}">

<input type=hidden name=OrderHash value="{cart.paymentMethod.getHash(cart):h}">
<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order">
</form>
