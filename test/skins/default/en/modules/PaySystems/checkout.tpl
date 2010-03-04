<FORM action="{cart.paymentMethod.params.param08}" method=POST name=process>
<input type=hidden name=companyid value="{cart.paymentMethod.params.param01}">
<input type=hidden name=total value="{cart.get(#total#)}">
<input type=hidden name=product1 value="">
<input type=hidden name=b_firstname value="{cart.profile.get(#billing_firstname#):r}">
<input type=hidden name=s_firstname value="{cart.profile.get(#shipping_firstname#):r}">
<input type=hidden name=b_middlename value="">
<input type=hidden name=s_middlename value="">
<input type=hidden name=b_lastname value="{cart.profile.get(#billing_firstname#):r}">
<input type=hidden name=s_lastname value="{cart.profile.get(#shipping_firstname#):r}">
<input type=hidden name=email value="{cart.profile.get(#login#):r}">
<input type=hidden name=b_address value="{cart.profile.get(#billing_address#):r} {cart.profile.get(#billing_city#)} {cart.profile.get(#billing_state_code#):r}">
<input type=hidden name=s_address value="{cart.profile.get(#shipping_address#):r} {cart.profile.get(#shipping_city#)} {cart.profile.get(#shipping_state_code#):r}">
<input type=hidden name=b_city value="{cart.profile.get(#billing_city#)}">
<input type=hidden name=s_city value="{cart.profile.get(#shipping_city#)}">
<input type=hidden name=b_country value="{cart.profile.get(#billing_country#):r}">
<input type=hidden name=s_country value="{cart.profile.get(#shipping_country#):r}">
<input type=hidden name=b_zip value="{cart.profile.get(#billing_zipcode#):r}">
<input type=hidden name=s_zip value="{cart.profile.get(#shipping_zipcode#):r}">
<input type=hidden name=b_state value="{cart.profile.get(#billing_state_code#):r}">
<input type=hidden name=s_state value="{cart.profile.get(#shipping_state_code#):r}">
<input type=hidden name=b_tel value="{cart.profile.get(#billing_phone#):r}">
<input type=hidden name=delivery value="N">
<input type=hidden name=formget value="N">
<input type=hidden name=option1 value="{cart.get(#order_id#)}">
<input type=hidden name=redirect value="{shopURL(#cart.php?target=callback&action=callback#):h}&order_id_name=option1">
<INput type=hidden name=redirectfail value="{shopURL(#cart.php?target=callback&action=callback#):h}&order_id_name=option1">

<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order">

</form>
