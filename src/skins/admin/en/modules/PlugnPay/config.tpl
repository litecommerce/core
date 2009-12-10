Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="dialog.updated">PlugnPay parameters were successfully changed. Please make sure that the PlugnPay payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{dialog.pm.get(#payment_method#)}">
<table border=0 cellspacing=10>

<tr>
<td>Publisher name:</td>
<td><input type=text name=params[param01] size=32 value="{dialog.pm.params.param01}"></td>
</tr>

<tr>
<td>Processor host:</td>
<td><input type=text name=params[param03] size=32 value="{dialog.pm.params.param03}"></td>
</tr>


<tr>
<td valign=top>AVS Level:</td>
<td>
<input type=radio name=params[param05] value="0" checked="{IsSelected(dialog.pm.params.param05,#0#)}">Anything Goes. No transaction is rejected based on AVS.<br>
<input type=radio name=params[param05] value="1" checked="{IsSelected(dialog.pm.params.param05,#1#)}">Requires a match of Zip Code or Street Address,<br> but will allow cards where the address information is not available. (Only 'N' responses will be voided) <br>
<input type=radio name=params[param05] value="3" checked="{IsSelected(dialog.pm.params.param05,#3#)}">Requires match of Zip Code or Street Address.<br> All other transactions voided; including those where the address information is not available. <br>
<input type=radio name=params[param05] value="4" checked="{IsSelected(dialog.pm.params.param05,#4#)}">Requires match of Street Address or a exact match.<br> All other transactions voided; including those where the address information is not available. <br>
<input type=radio name=params[param05] value="5" checked="{IsSelected(dialog.pm.params.param05,#5#)}">Requires exact match of Zip Code and Street Address.<br>  All other transactions voided; including those where the address information is not available. <br>
<input type=radio name=params[param05] value="6" checked="{IsSelected(dialog.pm.params.param05,#6#)}">Requires exact match of Zip Code and Street Address,<br> but will allows cards where the address information is not available. <br>
</td><br>
</tr>



<tr>
<td>Order prefix:</td>
<td><input type=text name=params[param04] size=32 value="{dialog.pm.params.param04}"></td>
</tr>

<tr>
<td colspan=2>
<input type=submit value=" Update ">
</td>
</tr>

</table>
</form>
