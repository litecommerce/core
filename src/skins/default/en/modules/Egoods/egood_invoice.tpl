<tr> 
	<td valign="top" nowrap>Download URL&nbsp;&nbsp;&nbsp;</td>
	<td IF="order.is(#processed#)">
	<table cellspacing="0" cellpadding="0" border="0" IF="item.hasValidLinks()">
	<tr FOREACH="item.getEgoods(),egood">
		<td valign="top">{egood.name:h}:&nbsp;&nbsp;&nbsp;</td>
		<td><a href="{egood.link:r}"><u>{egood.link:r}</u></a></td>
	</tr>
	</table>
	<span IF="!item.hasValidLinks()">
	N/A
	</span>
	</td>
	<td IF="!order.is(#processed#)">
	N/A
	</td>
</tr>

