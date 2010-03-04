Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="updated">Trust Commerce parameters were successfully changed. Please make sure that the Trust Commerce payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.get(#payment_method#)}">
<table border=0 cellspacing=10>
<tr>
<td>Customer ID:</td>
<td><input type=text name="params[param01]" size=24 value="{pm.params.param01}"></td>
</tr>

<tr>
<td>Password:</td>
<td><input type=password name="params[param02]" size=24 value="{pm.params.param02}"></td>
</tr>

<tr>
<td>Currency:</td>
<td>
<select name="params[param05]">
<option value="eur" selected="{IsSelected(pm.params.param05,#eur#)}">Euro (Europe)
<option value="usd" selected="{IsSelected(pm.params.param05,#usd#)}">US Dollar (United States)
</select>
</td>
</tr>

<tr>
<td>Test/Live mode:</td>
<td>
<select name="params[testmode]">
<option value="Y" selected="{IsSelected(pm.params.testmode,#Y#)}">test
<option value="N" selected="{IsSelected(pm.params.testmode,#N#)}">live
</select>
</td>
</tr>


<tr>
<td>AVS:</td>
<td>
<select name="params[param06]">
<option value="Y" selected="{IsSelected(pm.params.param06,#Y#)}">Y
<option value="N" selected="{IsSelected(pm.params.param06,#N#)}">N
</select>
</td>
</tr>

<tr>
<td>Order prefix:</td>
<td><input type=text name="params[param04]" size=36 value="{pm.params.param04}"></td>
</tr>

<tr>
<td>Operator:</td>
<td><input type=text name="params[param07]" size=36 value="{pm.params.param07}"></td>
</tr>

<tr>
<td colspan=2>
<input type=submit value="Update">
</td>
</tr>

</table>
</form>
