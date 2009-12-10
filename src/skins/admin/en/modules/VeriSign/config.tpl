Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="dialog.updated">Verisign parameters were successfully changed. Please make sure that the Verisign payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{dialog.pm.get(#payment_method#)}">
<table border=0 cellspacing=10>
<tr>
<td>Merchant account username:</td>
<td><input type=text name=params[param01] size=24 value="{dialog.pm.params.param01}"></td>
</tr>
<tr>
<td>Merchant account password:</td>
<td><input type=password name=params[param04] size=24 value="{dialog.pm.params.param04}"></td>
</tr>
<tr>
<td>Vendor:</td>
<td><input type=text name=params[param02] size=24 value="{dialog.pm.params.param02}"></td>
</tr>
<tr>
<td>Partner:</td>
<td><input type=text name=params[param03] size=24 value="{dialog.pm.params.param03}"></td>
</tr>
<tr>
<td>Test/Live mode:</td>
<td>
<select name=params[testmode]>
<option value="Y" selected="{IsSelected(dialog.pm.params.testmode,#Y#)}">test
<option value="N" selected="{IsSelected(dialog.pm.params.testmode,#N#)}">live
</select>
</td>
</tr>
<tr>
<td>Type of transaction:</td>
<td>
<select name=params[param07]>
<option value="A" selected="{IsSelected(dialog.pm.params.param07,#A#)}">Auth
<option value="S" selected="{IsSelected(dialog.pm.params.param07,#S#)}">Sale
</select>
</td>
</tr>

<tr>
<td colspan="2"><hr></td>
</tr>
<tr>
<td>Gateway URL:</td>
<td><input type=text size=48 name=params[param05] value="{dialog.pm.params.param05}"></td>
</tr>
<td>Test gateway URL:</td>
<td><input type=text size=48 name=params[param06] value="{dialog.pm.params.param06}"></td>
</tr>
<tr>
<td colspan=2>
<input type=submit value=" Update ">
</td>
</tr>
</table>
</form>
<hr>
<form action="admin.php" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="target" value="cc_cert">
	<input type="hidden" name="action" value="update">
	<input type="hidden" name="cc_processor" value="verisign_cc">
	<table>
		<tr><td>Upload [Certificate] file: </td></tr>
		<tr><td>
			<input type="file" name="cert_file" size="32">
		</td></tr>	
	</table>
	<br>
	<input type="submit" value="Update">
</form>
