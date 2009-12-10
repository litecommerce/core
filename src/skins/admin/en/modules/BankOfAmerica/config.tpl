Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="dialog.updated">Bank of America parameters were successfully changed. Please make sure that the Bank of America payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{dialog.pm.get(#payment_method#)}">
<p>
<B>Note:</B> In setup Bank of America payment gateway, you have to proceed these steps:
<LI>Log in to your Bank of America backoffice</LI>
<LI>Go to the '<I>Manage Store/Order Rules/Configure Options</I>' menu</LI>
<LI>Go to the 'When a Shopper Places an Order' section</LI>
<LI>Check the '<I>Treat as In Process HTTP Request</I>' option</LI>
<p>
<table border=0 cellspacing=10>
<tr>
<td>Store ID:</td>
<td><input type=text name=params[param01] size=32 value="{dialog.pm.params.param01}"></td>
</tr>
<tr>
<td>CVV Indicator:</td>
<td>
<select name=params[param02]>
<option value=1 selected="{IsSelected(dialog.pm.params.param02,#1#)}"> PRESENT</option>
<option value=2 selected="{IsSelected(dialog.pm.params.param02,#2#)}">UNREADABLE</option>
<option value=9 selected="{IsSelected(dialog.pm.params.param02,#9#)}">NOTPRESENT</option>
</select>
</td>
</tr>
<tr>
<td>Order prefix:</td>
<td><input type=text name=params[param03] size=32 value="{dialog.pm.params.param03}"></td>
</tr>

<tr>
<td colspan=2>
<input type=submit value="Update">
</td>
</tr>
</table>
</form>
