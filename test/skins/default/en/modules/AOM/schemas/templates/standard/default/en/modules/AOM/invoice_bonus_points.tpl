{if:clone}
<tr IF="!cloneOrder.payedByPoints=0">
    <td colspan="4" align="right" class="AomProductDetailsTitle">Bonus points discount:</td>
    <td align="right">{price_format(invertSign(cloneOrder.payedByPoints)):h}</td>
</tr>
{else:}
<tr IF="!order.payedByPoints=0">
	<td colspan="4" align="right" class="AomProductDetailsTitle">Bonus points discount:</td>
	<td align="right">{price_format(invertSign(order.payedByPoints)):h}</td>
</tr>
{end:}

