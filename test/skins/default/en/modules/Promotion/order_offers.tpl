
<tbody IF="order.DC">
<tr>
	<td nowrap colspan=2>&nbsp;</td>
<tr>
<tr>
	<td nowrap bgcolor="#DDDDDD" colspan=2><b>Discount Coupon</b></td>
<tr>
<tr>
    <td nowrap>Coupon:</td>
    <td>{order.DC.coupon:h}</td>
</tr>
<tr>
    <td nowrap><b>Discount:</b></td>
    <td IF="order.DC.type=#absolute#">{price_format(order.DC.discount):h}</td>
    <td IF="order.DC.type=#percent#">{order.DC.discount}%</td>
    <td IF="order.DC.type=#freeship#">Free shipping</td>
</tr>
</tbody>
