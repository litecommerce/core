Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="updated">Netbilling parameters were successfully changed.<br>Please make sure that the Netbilling payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.get(#payment_method#)}">

<table border=0 cellspacing=2 cellpadding=1 width="100%">
	<tr>
		<td align="center"><img src="images/modules/Netbilling/net_billing_logo.gif" alt=""></td>
		<td width="20">&nbsp;</td>
		<td>

<table border=0 cellspacing=5 cellpadding=2>
<tr>
	<td>Account:</td>
	<td><input type="text" name="params[account]" size="24" value="{pm.params.account:r}"></td>
</tr>

<tr>
	<td>Site tag:</td>
	<td><input type=text name="params[site_tag]" size=24 value="{pm.params.site_tag:r}"></td>
</tr>

<tr>
	<td>Transaction type:</td>
	<td>
		<select name="params[tran_type]">
			<option value="A" selected="pm.params.tran_type=#A#">Auth</option>
			<option value="S" selected="pm.params.tran_type=#S#">Sale</option>
		</select>
	</td>
</tr>

<tr>
	<td colspan=2 align=center><input type=submit value=" Update "></td>
</tr>

</table>

		</tr>
	</td>
</table>
</form>
