<p>
<span class="SuccessMessage" IF="updated">PaySystems parameters were successfully changed. Please make sure that the PaySystems payment method is enabled on the <a href="admin.php?target=payment_methods">Payment methods</a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.get(#payment_method#)}">
<table border=0 cellspacing=10>

<tr>
<td>Company ID:</td>
<td><input type=text name=params[param01] size=32 value="{pm.params.param01}"></td>
</tr>

<tr>
<td>Order prefix:</td>
<td><input type=text name=params[param02] size=32 value="{pm.params.param02}"></td>
</tr>

<tr>
<td>PaySystems gateway URL:</td>
<td><input type=text name=params[param08] size=32 value="{pm.params.param08}"></td>
</tr>

<tr>
<td colspan="2"><input type=submit value=" Update "></td>
</tr>

</table>
</form>
