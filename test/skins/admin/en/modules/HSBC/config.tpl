<p>
<span class="SuccessMessage" IF="updated">HSBC parameters were successfully changed. Please make sure that the HSBC payment method is enabled on the <a href="admin.php?target=payment_methods">Payment methods</a> page before you can start using it.</span>

<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.get(#payment_method#)}">

<center>
<table border=0 cellspacing=10>
<tr>
	<td rowspan="6"><a href="http://www.hsbc.com"><img src="images/modules/HSBC/hsbc_logo.gif" border="0"></a></td>
	<td rowspan="6">&nbsp;&nbsp;&nbsp;</td>
	<td>Store Id:</td>
	<td><input type=text name=params[param01] size=24 value="{pm.params.param01}"></td>
</tr>

<tr>
	<td>CPI Hash Key:</td>
	<td><input type=text name=params[param02] size=24 value="{pm.params.param02}"></td>
</tr>

<tr>
	<td>Test/Live mode:</td>
	<td>
	<select name=params[param03]>
		<option value=T selected="{IsSelected(pm.params.param03,#T#)}">test</option>
		<option value=P selected="{IsSelected(pm.params.param03,#P#)}">live</option>
	</select>
	</td>
</tr>

<tr>
	<td>Charge Type:</td>
	<td>
	<select name=params[param05]>
		<option value="auth" selected="{IsSelected(pm.params.param05,#auth#)}">Auth</option>
		<option value="capture" selected="{IsSelected(pm.params.param05,#capture#)}">Capture</option>
	</select>
	</td>
</tr>

<tr>
	<td>Purchase currency:</td>
	<td>
	<select name=params[param04]>
		<option value="978" selected="{IsSelected(pm.params.param04,#978#)}">Euro</option>
		<option value="344" selected="{IsSelected(pm.params.param04,#344#)}">Hong Kong Dollar</option>
		<option value="392" selected="{IsSelected(pm.params.param04,#392#)}">Japanese Yen</option>
		<option value="826" selected="{IsSelected(pm.params.param04,#826#)}">Pound Sterling</option>
		<option value="840" selected="{IsSelected(pm.params.param04,#840#)}">US Dollar</option>
	</select>
	</td>
</tr>

<tr>
	<td>Gateway URL:</td>
	<td><input type=text name=params[param09] size=48 value="{pm.params.param09}"></td>
</tr>

<tr>
	<td colspan="4"><hr></td>
</tr>

<tr>
	<td rowspan="3">&nbsp;</td>
	<td rowspan="3">&nbsp;&nbsp;&nbsp;</td>
	<td>'Queued' order status:</td>
	<td><widget class="XLite_View_StatusSelect" field="params[status_queued]" value="{pm.getStatusCode(#status_queued#)}"></td>
</tr>

<tr>
	<td>'Processed' order status:</td>
	<td><widget class="XLite_View_StatusSelect" field="params[status_processed]" value="{pm.getStatusCode(#status_processed#)}"></td>
</tr>

<tr>
	<td>'Failed' order status:</td>
	<td><widget class="XLite_View_StatusSelect" field="params[status_failed]" value="{pm.getStatusCode(#status_failed#)}"></td>
</tr>

<tr>
	<td colspan="4">&nbsp;</td>
</tr>

<tr>
	<td colspan=4 align="middle"><input type=submit value=" Update "></td>
</tr>
</table>
</center>

</form>
