<form action="{cart.paymentMethod.params.param08}" method="POST" name="WellsFargo_form">
	<INPUT type=hidden name=ecom_billto_online_email value="{cart.profile.get(#login#)}">
	<INPUT type=hidden name=ecom_billto_postal_city value="{cart.profile.get(#billing_city#)}">
	<INPUT type=hidden name=ecom_billto_postal_countrycode value="{cart.profile.get(#billing_country#)}">
	<INPUT type=hidden name=ecom_billto_postal_name_first value="{cart.profile.get(#billing_firstname#)}">
 	<INPUT type=hidden name=ecom_billto_postal_name_last value="{cart.profile.get(#billing_lastname#)}">
	<INPUT type=hidden name=ecom_billto_postal_postalcode value="{cart.profile.get(#billing_zipcode#)}">
	<INPUT type=hidden name=ecom_billto_postal_stateprov value="{cart.profile.get(#billing_state#)}">
	<INPUT type=hidden name=ecom_billto_postal_street_line1 value="{cart.profile.get(#billing_address#)}">
	<INPUT type=hidden name=ecom_billto_telecom_phone_number value="{cart.profile.get(#billing_phone#)}">

	<INPUT type=hidden name=ecom_shipto_online_email value="{cart.profile.get(#login#)}">
	<INPUT type=hidden name=ecom_shipto_postal_city value="{cart.profile.get(#shipping_city#)}">
	<INPUT type=hidden name=ecom_shipto_postal_countrycode value="{cart.profile.get(#shipping_country#)}">
	<INPUT type=hidden name=ecom_shipto_postal_name_first value="{cart.profile.get(#shipping_firstname#)}">
 	<INPUT type=hidden name=ecom_shipto_postal_name_last value="{cart.profile.get(#shipping_lastname#)}">
	<INPUT type=hidden name=ecom_shipto_postal_postalcode value="{cart.profile.get(#shipping_zipcode#)}">
	<INPUT type=hidden name=ecom_shipto_postal_stateprov value="{cart.profile.get(#shipping_state#)}">
	<INPUT type=hidden name=ecom_shipto_postal_street_line1 value="{cart.profile.get(#shipping_address#)}">
	<INPUT type=hidden name=ecom_shipto_telecom_phone_number value="{cart.profile.get(#shipping_phone#)}">

	<INPUT type=hidden name=ioc_merchant_id value="{cart.paymentMethod.params.param01}">
	<INPUT type=hidden name=ioc_merchant_order_id value="{cart.get(#order_id#)}">
	<INPUT type=hidden name=ioc_order_transaction_type value="CC">
	<INPUT type=hidden name=ioc_shopper_ip_address value="{cart.paymentMethod.getIP()}">
	<INPUT type=hidden name=ioc_order_total_amount value="{cart.get(#total#)}">
	<INPUT type=hidden name=ioc_order_tax_amount value="{cart.get(#tax#)}">
	<INPUT type=hidden name=ioc_order_ship_amount value="{cart.get(#shipping_cost#)}">
	<INPUT type=hidden name=ioc_auto_settle_flag value="Y">
	<INPUT type=hidden name=ioc_transaction_type value="E">
	<INPUT type=hidden name=ioc_shipto_same_as_billto value="0">
<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order">
</form>

