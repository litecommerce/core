Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="dialog.updated">Echo parameters were successfully changed. Please make sure that the Echo payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{dialog.pm.get(#payment_method#)}">
<table border=0 cellspacing=10>
<tr>
<td>Merchant ECHO-ID:</td>
<td><input type="text" name="params[param01]" size="24" value="{dialog.pm.params.param01:r}"></td>
</tr>
<td>Merchant PIN:</td>
<td><input type=text name="params[param02]" size=24 value="{dialog.pm.params.param02:r}"></td>
</tr>

<tr>
<td>Invoice number prefix:</td>
<td><input type=text name="params[param03]" size=24 value="{dialog.pm.params.param03:r}"></td>
</tr>

<tr>
<td colspan=2>
<input type=submit value=" Update ">
</td>
</tr>

</table>
</form>
