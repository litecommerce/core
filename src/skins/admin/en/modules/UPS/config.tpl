{if:updated}<span class="SuccessMessage">UPS settings have been saved. To use UPS real-time shipping rate calculation, go to <a href="admin.php?target=shipping_methods">Shipping methods</a> dialog and enable UPS shipping methods.</span>{end:}
<table width="100%" border="0">
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="ups">
<input type="hidden" name="action" value="update">
<input type="hidden" name="server" value="{settings.server:r}">
<tr>
	<td width="50%"><b>User ID:</b></td>
	<td width="50%"><input type="text" name="userid" value="{settings.userid:r}" size="32"></td>
</tr><tr>
	<td width="50%"><b>Password:</b></td>
	<td width="50%"><input type="text" name="password" value="{settings.password:r}" size="32"></td>
</tr><tr>
	<td colspan="2">
	If you do not have a UPS account, you can <a href="https://www.ups.com/myups/registration">Register here</a>.</td>
</tr><tr>	
	<td colspan="2">As an end-user, you must <a href="https://www.ups.com/servlet/login">login</a> to your country.s UPS E-Business web site with your UserId and Password. A lisense agreement must be accepted on the "OnLine Tools" section at your country's UPS E-Business web site. After accepting the appropriate Tools licensing agreement, a Developer Key will be e-mailed to the registered user.  You need to permanently save a copy of your Developer Key for future reference. A valid <b>Developer Key is needed to obtain an Access Key</b>. You can obtain your XML Access Key on the "Access Key" section of your country's UPS E-Business web site.
	</td>
</tr><tr>
	<td width="50%"><b>Access Key:</b></td>
	<td width="50%"><input type="text" name="accessKey" value="{settings.accessKey:r}" size="32"></td>
</tr><tr>
	<td width="50%"><b>Pickup type:</b></td>
	<td width="50%">
		<select name="pickup">
			<option FOREACH="pickups,k,v" selected="{isSelected(settings.pickup,k)}" value="{k}">{v}</option>
		</select>
	</td>	
</tr><tr>
	<TD width=50%><B>Packaging type:</B></TD>
	<td>
		<select name="packaging">
			<option FOREACH="packagings,k,v" selected="{isSelected(settings.packaging,k)}" value="{k}">{v}</option>
		</select>
	</td>
</tr><tr>
	<TD width=50%><B>Package width, 1 - 108 inches (optional):</B></TD>
	<td><input type="text" name="width" value="{settings.width}" size="6">
	</td>
</tr><tr>
	<TD width=50%><B>Package length, 1 - 108 inches (optional):</B></TD>
	<td><input type="text" name="length" value="{settings.length}" size="6">
	</td>
</tr><tr>
	<TD width=50%><B>Package height, 1 - 108 inches (optional):</B></TD>
	<td><input type="text" name="height" value="{settings.height}" size="6">
	</td>
</tr><tr>
	<TD width=50%><B>Insured value, USD (optional, max. 50,000 USD):</B></TD>
	<td><input type="text" name="insured" value="{settings.insured:r}" size="6">
	</td>
</tr><tr>
	<td><b>Saturday delivery:</b></td>
	<td><input type="checkbox" name="sat_delivery" value="1" checked="{settings.sat_delivery}"></td>
</tr><tr>
	<td><b>Saturday pickup:</b></td>
	<td><input type="checkbox" name="sat_pickup" value="1" checked="{settings.sat_pickup}"></td>
</tr><tr>
	<td><b>Ship to:</b></td>
	<td><select name="residential">
		<option value="1" selected="{isSelected(settings.residential,#1#)}">Residential</option>
		<option value="0" selected="{isSelected(settings.residential,#0#)}">Business</option>
	</select></td>
</tr>
<tr>
    <TD width=50%><B>Weight unit (use kgs for the countries that don't support lbs):</B></TD>
    <td>
        <select name="weight_unit">
            <option FOREACH="weight_units,k,v" selected="{isSelected(settings.weight_unit,k)}" value="{k}">{v}</option>
        </select>
    </td>
</tr>
<tr>
	<td colspan="2"><input type="submit" value="Apply"></td>
</tr>
</form>
</table>

