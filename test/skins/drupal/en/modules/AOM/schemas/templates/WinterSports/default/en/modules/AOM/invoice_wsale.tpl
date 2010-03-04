{if:clone}
<tr>
    <td colspan="4" align="right" class="AomProductDetailsTitle">Global discount:</td>
    <td align="right">{price_format(invertSign(cloneOrder.global_discount)):h}</td>
</tr>
{else:}
<tr>
    <td colspan="4" align="right" class="AomProductDetailsTitle">Global discount:</td>
    <td align="right">{price_format(invertSign(order.global_discount)):h}</td>
</tr>
{end:}

