Use this page to configure your store to communicate with your Protx VSP Form Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="updated">Protx VSP Form parameters were successfully changed. Please make sure that the ProtxForm payment method is enabled on the <a href="admin.php?target=payment_methods">Payment methods</a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.payment_method}">

<table border=0 cellspacing=2 cellpadding=1 width="100%">
	<tr>
		<td align="center">
		<img src="images/modules/ProtxForm/protx_logo.gif" border="0" alt="Protx logo"><br>
		<img src="images/modules/ProtxForm/VSPFormlogo.gif" border="0" alt="Protx VSP Form">
		</td>
		<td width="20">&nbsp;</td>
		<td>
<table border=0 cellspacing=2 cellpadding=3>

<tr>
	<td align="right" width="200">Protx Vendor name:</td>
	<td>&nbsp;</td>
	<td><input type=text name="params[vendor]" size=24 value="{pm.params.vendor:r}"></td>
</tr>

<tr>
	<td align="right" width="200">Encryption Password:</td>
	<td>&nbsp;</td>
	<td><input type=text name="params[xor_password]" size=24 value="{pm.params.xor_password:r}"></td>
</tr>

<tr>
	<td align="right">Order prefix:</td>
	<td>&nbsp;</td>
	<td><input type=text name=params[order_prefix] size=32 value="{dialog.pm.params.order_prefix}"><br><i>it mustn't be empty in test mode</i></td>
</tr>

<tr>
	<td align="right">Test/Live mode:</td>
	<td>&nbsp;</td>
	<td>
		<select name=params[testmode]>
			<option value="Y" selected="{IsSelected(dialog.pm.params.testmode,#Y#)}">test</option>
			<option value="N" selected="{IsSelected(dialog.pm.params.testmode,#N#)}">live</option>
		</select>
	</td>
</tr>

<tr>
	<td align="right">Apply AVS/CV2 checks:</td>
	<td>&nbsp;</td>
	<td>
		<select name=params[ApplyAVSCV2]>
			<option value="0" selected="{IsSelected(dialog.pm.params.ApplyAVSCV2,0)}">Allow AVS/CV2 checks, use rules</option>
			<option value="1" selected="{IsSelected(dialog.pm.params.ApplyAVSCV2,1)}">Force AVS/CV2 checks, use rules</option>
			<option value="2" selected="{IsSelected(dialog.pm.params.ApplyAVSCV2,2)}">Do not allow AVS/CV2 checks</option>
			<option value="3" selected="{IsSelected(dialog.pm.params.ApplyAVSCV2,3)}">Force AVS/CV2 checks, do not use rules</option>
		</select>
	</td>
</tr>

<tr>
	<td align="right">Apply Apply3DSecure  checks:</td>
	<td>&nbsp;</td>
	<td>
		<select name=params[Apply3DSecure]>
			<option value="0" selected="{IsSelected(dialog.pm.params.Apply3DSecure,0)}">Allow 3D-Secure checks, use rules</option>
			<option value="1" selected="{IsSelected(dialog.pm.params.Apply3DSecure,1)}">Force 3D-Secure checks, use rules</option>
			<option value="2" selected="{IsSelected(dialog.pm.params.Apply3DSecure,2)}">Do not allow 3D-Secure checks</option>
			<option value="3" selected="{IsSelected(dialog.pm.params.Apply3DSecure,3)}">Force 3D-Secure checks, do not use rules</option>
		</select>
	</td>
</tr>

<tr>
	<td align="right" width="200">eMailMessage:</td>
	<td>&nbsp;</td>
	<td><textarea rows="5" cols="50" name="params[eMailMessage]" >{pm.params.eMailMessage}</textarea></td>
</tr>

<tr>
	<td align="right">Currency:</td>
	<td>&nbsp;</td>
	<td>
		<select name=params[currency]>
			<option value="USD" selected="{IsSelected(dialog.pm.params.currency,#USD#)}">US Dollar</option>
			<option value="GBP" selected="{IsSelected(dialog.pm.params.currency,#GBP#)}">British Pound</option>
			<option value="EUR" selected="{IsSelected(dialog.pm.params.currency,#EUR#)}">Euro</option>
			<option value="CAD" selected="{IsSelected(dialog.pm.params.currency,#CAD#)}">Canadian Dollar</option>
			<option value="AUD" selected="{IsSelected(dialog.pm.params.currency,#AUD#)}">Australian Dollar</option>
		</select>
	</td>
</tr>

<tr>
	<td align="right">Transaction type:</td>
	<td>&nbsp;</td>
	<td>
		<select name="params[trans_type]">
			<option value="DEFERRED" selected="{dialog.pm.params.trans_type=#DEFERRED#}">Deferred</option>
			<option value="PAYMENT" selected="{dialog.pm.params.trans_type=#PAYMENT#}">Payment</option>
		</select>
	</td>
</tr>

<tr>
	<td colspan="3" height="20">&nbsp;</td>
</tr>

<tr>
	<td colspan="3" align="middle"><input type=submit value=" Update "></td>
</tr>

</table>

</td>
</tr>

</table>

</form>
