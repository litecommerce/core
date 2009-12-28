Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>
<p>
<span IF="dialog.updated">
<span class="SuccessMessage">CyberSource parameters were successfully changed.</span>
<br>Please make sure that the payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.
</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="phpcybersource_cc">
<table border=0 cellspacing=10>
<tr>
<td>Merchant ID:</td>
<td><input type=text name=params[merchantID] size=24 value="{pm.params.merchantID:r}"></td>
</tr>
<tr>
<td>Keys Directory:</td>
<td><input type=text name=params[keysDirectory] size=24 value="{dialog.pm.params.keysDirectory:r}"></td>
</tr>
<tr>
<td>Order prefix:</td>
<td><input type=text name=params[prefix] size=24 value="{dialog.pm.params.prefix:r}"></td>
</tr>
<tr>
<td>Currency:</td>
<td>
<select name=params[currency]>
<option value="USD" selected="{IsSelected(dialog.pm.params.currency,#USD#)}">US Dollars
<option value="GBP" selected="{IsSelected(dialog.pm.params.currency,#GBP#)}">Sterling
<option value="EUR" selected="{IsSelected(dialog.pm.params.currency,#EUR#)}">Euro
</select>
</td>
</tr>
<tr>
<td>Transaction Type:</td>
<td>
<select name=params[transactionType]>
<option value="auth" selected="{IsSelected(dialog.pm.params.transactionType,#auth#)}">Auth
<option value="capture" selected="{IsSelected(dialog.pm.params.transactionType,#capture#)}">Auth & Capture
</select>
</td>
</tr>
<tr>
<td>Transaction Mode:</td>
<td>
<select name=params[testServer]>
<option value="0" selected="{IsSelected(dialog.pm.params.testServer,#0#)}">Live
<option value="1" selected="{IsSelected(dialog.pm.params.testServer,#1#)}">Test
</select>
</td>
</tr>
<tr>
<td colspan=2>
<input type=submit value=" Update ">
</td>
</tr>
</table>
</form>
