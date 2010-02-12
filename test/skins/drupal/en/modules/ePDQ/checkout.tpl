<form action="https://{xlite.options.host_details.https_host}{xlite.options.host_details.web_dir_wo_slash}/classes/modules/ePDQ/submit.php" method="POST" name="ePDQ_form">
{dialog.cart.paymentMethod.getePDQdata(dialog.cart):h}
<input type=hidden name=merchantdisplayname value="{dialog.cart.paymentMethod.params.param01}">
<input type=hidden name=submit_url value="{dialog.cart.paymentMethod.params.param09}">
<input type=hidden name=email value="{dialog.cart.profile.get(#login#):r}">
<input type=hidden name=baddr1 value="{dialog.cart.profile.get(#billing_address#):r}">
<input type=hidden name=bcity value="{dialog.cart.profile.get(#billing_city#)}">
<input type=hidden name=bcountry value="{dialog.cart.profile.get(#billing_country#)}">
<input type=hidden name=bpostalcode value="{dialog.cart.profile.get(#billing_zipcode#)}">
{if:IsSelected(dialog.cart.profile,#billing_country#,#US#)}
<input type=hidden name=bstate value="{dialog.cart.profile.get(#billingState.code#)}">
<input type=hidden name=sstate value="{dialog.cart.profile.get(#shippingState.code#)}">
{end:}
{if:IsSelected(dialog.cart.profile,#billing_country#,#GB#)}
<input type="hidden" name="bcountyprovince" value="{dialog.cart.profile.get(#billingState.code#)}">
<input type="hidden" name="scountyprovince" value="{dialog.cart.profile.get(#shippingState.code#)}">
{end:} 
<input type=hidden name=saddr1 value="{dialog.cart.profile.get(#shipping_address#)}">
<input type=hidden name=scity value="{dialog.cart.profile.get(#shipping_city#)}">
<input type=hidden name=spostalcode value="{dialog.cart.profile.get(#shipping_zipcode#)}">
<input type=hidden name=scountry value="{dialog.cart.profile.get(#shipping_country#)}">
<input type=hidden name=returnurl value="https://{xlite.options.host_details.https_host}{xlite.options.host_details.web_dir_wo_slash}/classes/modules/ePDQ/return.php">
<INPUT type=hidden name=cpi_logo value="{dialog.cart.paymentMethod.params.param06}">
<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order">
</form>
