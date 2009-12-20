Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="dialog.updated">Parameters were successfully changed. Please make sure that the PayFlow Pro payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
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
<option value="Y" selected="{IsSelected(dialog.pm.params.testmode,#Y#)}">test</option>
<option value="N" selected="{IsSelected(dialog.pm.params.testmode,#N#)}">live</option>
</select>
</td>
</tr>
<tr>
<td>Type of transaction:</td>
<td>
<select name=params[param07]>
<option value="A" selected="{IsSelected(dialog.pm.params.param07,#A#)}">Auth</option>
<option value="S" selected="{IsSelected(dialog.pm.params.param07,#S#)}">Sale</option>
</select>
</td>
</tr>
<tr>
<td>Currency:</td>
<td>
<select name=params[currency]>
<option value="USD" selected="{IsSelected(dialog.pm.params.currency,#USD#)}">USD</option>
<option value="EUR" selected="{IsSelected(dialog.pm.params.currency,#EUR#)}">EUR</option>
<option value="GBP" selected="{IsSelected(dialog.pm.params.currency,#GBP#)}">GBP</option>
<option value="CAD" selected="{IsSelected(dialog.pm.params.currency,#CAD#)}">CAD</option>
<option value="JPY" selected="{IsSelected(dialog.pm.params.currency,#JPY#)}">JPY</option>
<option value="AUD" selected="{IsSelected(dialog.pm.params.currency,#AUD#)}">AUD</option>
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
