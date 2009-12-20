Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.

<p>
<span class="SuccessMessage" IF="updated">BeanStream  parameters were successfully changed. Please make sure that the BeanStream payment method is enabled on the <a href="admin.php?target=payment_methods">Payment methods</a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.payment_method}">
<center>
<table border=0 cellspacing=10 width="90%">
<tr>
	<td rowspan="12" align="center"><img src="images/modules/BeanStream/beanstream_logo.gif" border="0"></td>
	<td colspan="2"><widget template="modules/BeanStream/separator.tpl" caption="Account settings"></td>
</tr>
<tr>
	<td align="right">Merchant Id:</td>
	<td><input type="text" name="params[merchant_id]" size="24" value="{pm.params.merchant_id:r}"></td>
</tr>

<tr>
	<td align="right">Username:</td>
	<td><input type="text" name="params[username]" size="24" value="{pm.params.username:r}"></td>
</tr>

<tr>
	<td align="right">Password:</td>
	<td><input type="text" name="params[password]" size="24" value="{pm.params.password:r}"></td>
</tr>

<tr> 
	<td align="right">Transaction type:</td>
	<td>
		<select name="params[trnType]">
			<option value="P" selected="{pm.params.trnType=#P#}">Purchase</option>
			<option value="PA" selected="{pm.params.trnType=#PA#}">Pre-Authorization</option>
		</select>
	</td>
</tr>

<tr>
	<td colspan="2"><widget template="modules/BeanStream/separator.tpl" caption="Order settings"></td>
</tr>

<tr>
	<td align="right">Success order status:</td>
	<td><widget class="CStatusSelect" field="params[status_success]" value="{dialog.pm.orderSuccessStatus}"></td>
</tr>

<tr>
	<td align="right">Failed order status:</td>
	<td><widget class="CStatusSelect" field="params[status_fail]" value="{dialog.pm.orderFailStatus}"></td>
</tr>

<tr>
	<td align="right">Order prefix:</td>
	<td><input type=text name="params[order_prefix]" size=16 value="{dialog.pm.params.order_prefix}"></td>
</tr>

<tr>
	<td colspan="2"><widget template="modules/BeanStream/separator.tpl" caption="Authentication settings"></td>
</tr>

<tr>
	<td align="right">Enable Verified by Visa service:</td>
	<td><input type="checkbox" name="params[vbvEnabled]" checked="{pm.params.vbvEnabled}" /></td>
</tr>

<tr>
	<td align="right">Enable MasterCard SecureCode service:</td>
	<td><input type="checkbox" name="params[scEnabled]" checked="{pm.params.scEnabled}" /></td>
</tr>

</table>
<p>
<input type=submit value=" Update ">
</form>
</center>
