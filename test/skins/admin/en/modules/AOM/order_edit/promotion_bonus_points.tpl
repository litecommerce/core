<tr class="{getRowClass(#0#,#TableRow#)}" height="25" IF="xlite.PromotionEnabled">
	<td><b>Bonus points discount:</b></td>
	<td IF="target=#order#">{price_format(order,#payedByPoints#):h}</td>
	<td><input type="text" name="clone[payedByPoints]" value="{cloneOrder.payedByPoints}" size="5"></td>
</tr>
