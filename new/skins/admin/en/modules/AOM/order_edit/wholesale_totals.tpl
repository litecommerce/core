<tr class="{getRowClass(#TableRow#)}" height="25">
    <td><b>Global discount</b></td>
    <td IF="target=#order#">{price_format(order.global_discount):h}</td>
    <td><input type="text" name="clone[global_discount]" value="{cloneOrder.global_discount}" size="5"></td>
</tr>
