<p>
<span class="SuccessMessage" IF="updated">VeriSign Payflow Link parameters were successfully changed. Please make sure that the VeriSignLink payment method is enabled on the <a href="admin.php?target=payment_methods">Payment methods</a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.payment_method}">

<table border=0 cellspacing=10>
<tr>
	<td width="120" valign="top"><a href="https://payments.verisign.com/manager" target="_blank" title="Set up your VeriSign Payflow Link account information" onClick="this.blur()"><img src="images/modules/VerisignLink/VerisignLink.gif" border=0></a></td>
	<td>
<b>Note:</b> In order to track your VeriSign Payflow Link orders by the shopping cart software you have to proceed these steps:
<li>Log in to your VeriSign account</li>
<li>Go to the '<i>Account Info/Payflow Link Info</i>' menu</li>
<li>Set the option '<i>Return URL Method</i>' to '<i>POST</i>'</li>
<li>Set the '<i>Return URL</i>' to:<br> {shopURL(#classes/modules/VerisignLink/callback.php#):h}</li>
<P>
</td>
</tr>

<tr>
	<td align="right">VeriSign login:</td>
	<td><input type=text name="params[login]" size=24 value="{pm.params.login:r}"></td>
</tr>

<tr>
	<td align="right">VeriSign partner:</td>
	<td><input type=text name="params[partner]" size=24 value="{pm.params.partner:r}"></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td colspan=2><p><input type=submit value=" Update "></td>
</tr>

</table>

</form>
