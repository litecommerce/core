Use this page to configure your store to communicate with your <B>NetworkMerchants</B> Payment Processing Gateway.<br>
Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="dialog.updated">NetworkMerchants parameters were successfully changed. Please make sure that the NetworkMerchants payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{dialog.pm.get(#payment_method#)}">

<table border=0 cellspacing=2 cellpadding=1 width="100%">
	<tr>
		<td align="center"><img src="images/modules/NetworkMerchants/nm_logo.gif" alt=""></td>
		<td width="20">&nbsp;</td>
		<td>

<table border=0 cellspacing=5 cellpadding=2>
<tr>
	<td>Username:</td>
	<td><input type=text name=params[param01] size=32 value="{dialog.pm.params.param01}"></td>
</tr>

<tr>
	<td>Password:</td>
	<td><input type=text name=params[param03] size=32 value="{dialog.pm.params.param03}"></td>
</tr>

<tr>
	<td>Order prefix:</td>
	<td><input type=text name=params[param04] size=32 value="{dialog.pm.params.param04}"></td>
</tr>

<tr>
	<td>Mode:</td>
	<td>
		<select name=params[testmode]>
			<option value="auth" selected="{IsSelected(dialog.pm.params.testmode,#auth#)}">Authorization</option>
			<option value="sale" selected="{IsSelected(dialog.pm.params.testmode,#sale#)}">Sale</option>
		</select>
	</td>
</tr>

<tr>
	<td colspan=2 align="middle"><input type=submit value=" Update "></td>
</tr>
</table>

		</td>
	</tr>
</table>
</form>
