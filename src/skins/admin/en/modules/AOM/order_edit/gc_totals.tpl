<tr class="{getRowClass(#TableRow#)}" height="25">
    <td><b>Paid with GC</b></td>
    <td IF="target=#order#">{price_format(order.payedByGC):h}</td>
    <td><input type="text" name="clone[payedByGC]" value="{cloneOrder.payedByGC}" size="5" {if:!cloneOrder.gcid}disabled{end:}></td>
</tr>
			
