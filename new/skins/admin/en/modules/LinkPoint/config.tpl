Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="dialog.updated">LinkPoint parameters were successfully changed. Please make sure that the LinkPoint payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{dialog.pm.get(#payment_method#)}">

<table border=0 cellspacing=10>
<tr>
<td>Store name:</td>
<td><input type=text name=params[param01] size=32 value="{dialog.pm.params.param01:r}"></td>
</tr>

<tr>
<td>Secure host & port:</td>
<td><input type=text name=params[param06] size=32 value="{dialog.pm.params.param06:r}">:<input type=text name=params[param07] size=4 value="{dialog.pm.params.param07:r}">
</td>
</tr>

<tr>
<td>&nbsp;</td>
<td>
<i>Usually, using <b>staging.linkpt.net</b> or <b>secure.linkpt.net</b>. Port <b>1129</b> or <b>1139</b></i>
</td>
</tr>

<tr>
<td>Test/Live mode:</td>
<td>
<select name=params[testmode]>
<option value="A" selected="{IsSelected(dialog.pm.params.testmode,#A#)}">test:approved</option>
<option value="D" selected="{IsSelected(dialog.pm.params.testmode,#D#)}">test:declined</option>
<option value="N" selected="{IsSelected(dialog.pm.params.testmode,#N#)}">live</option>
</select>
</td>
</tr>

<tr>
<td>CVM Indicator:</td>
<td>
<select name=params[param04]>
<option value="not_provided" selected="{IsSelected(dialog.pm.params.param04,#not_provided#)}">No CVM check requested</option>
<option value="provided" selected="{IsSelected(dialog.pm.params.param04,#provided#)}">The CVM value is requested</option>
<option value="illegible" selected="{IsSelected(dialog.pm.params.param04,#illegible#)}">Can not read the CVM on the card</option>
<option value="not_present" selected="{IsSelected(dialog.pm.params.param04,#not_present#)}">No CVM on the back of the card</option>
</select>
</td>
</tr>

<tr>
<td>Order prefix:</td>
<td><input type=text name=params[param05] size=32 value="{dialog.pm.params.param05}"></td>
</tr>
<tr>
<td colspan=2>
<input type=submit value="Update">
</td>
</tr>
</table>
</form>

<hr>
<form action="admin.php" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="target" value="cc_cert">
	<input type="hidden" name="action" value="update">
	<input type="hidden" name="cc_processor" value="linkpoint_cc">
	<table>
		<tr><td>Upload Certificate file: </td></tr>
		<tr><td>
			<input type="file" name="cert_file" size="32">
		</td></tr>	
	</table>
	<br>
	<input type="submit" value="Update">
</form>
