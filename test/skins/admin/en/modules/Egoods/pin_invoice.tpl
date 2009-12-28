<tr IF="order.is(#processed#)&item.hasValidPins()">
	<td valign="top">PIN codes</td>
	<td>
		<table border="0" cellpadding="0" cellspacing="0">
		<tr FOREACH="item.pinCodes,pin">
		<td valign="top">{pin}</td>
		</tr>
		</table>
	</td>
</tr>	
