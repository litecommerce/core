<tr class="{getRowClass(#0#,#TableRow#)}" height="25">
	<td><b>Discount</b></td>
    <td IF="target=#order#">{price_format(order.discount):h}</td>
	<td><input type="text" name="clone[discount]" value="{cloneOrder.discount}" size="5"></td>
</tr>

