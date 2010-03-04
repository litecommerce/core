{if:clone}
<tr IF="!isSelected(cloneOrder,#discount#,#0#)">
    <td colspan="4" align="right" class="AomProductDetailsTitle">Discount:</td>
    <td align="right">{price_format(invertSign(cloneOrder.discount)):h}</td>
</tr>
{else:}
<tr IF="!isSelected(order,#discount#,#0#)">
	<td colspan="4" align="right" class="AomProductDetailsTitle">Discount:</td>
	<td align="right">{price_format(invertSign(order.discount)):h}</td>
</tr>
{end:}
											

