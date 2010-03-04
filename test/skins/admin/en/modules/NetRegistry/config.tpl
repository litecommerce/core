Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="updated">NetRegistry parameters were successfully changed. Please make sure that the NetRegistry payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.get(#payment_method#)}">
<table border=0 cellspacing=10>

<tr>
<td>Merchant ID:</td>
<td><input type="text" name="params[param01]" size="32" value="{pm.params.param01:r}"></td>
</tr>

<td>External access password:</td>
<td><input type=text name="params[param02]" size=32 value="{pm.params.param02:r}"></td>
</tr>

<tr>
<td>Gateway URL:</td>
<td><input type=text name="params[param09]" size=32 value="{pm.params.param09:r}"></td>
</tr>

<tr>
<td collspan="2">
<input type=submit value=" Update ">
</td>

</table>
</form>
