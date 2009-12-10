<tr class="descriptionTitle"><td colspan=2 class="ProductDetailsTitle">Wholesale pricing</td></tr>
<tr><td class="Line" height=1 colspan=2><img src="images/spacer.gif" width=1 height=1 border=0 alt=""></td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan="2">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td><b>Quantity</b></td>
	<td><b>Price per product</b></td>
</tr>
<tbody FOREACH="wholesalePricing,idx,wholesale_price">
<tr>
	<td class="Line" height=1 colspan=2><img src="images/spacer.gif" width=1 height=1 border=0 alt=""></td>
</tr>
<tr style="height: 18px;"> 
	<td nowrap>{wholesale_price.amount} or more</td>
	<td align="right">{price_format(wholesale_price.price):r}</td>
</tr>
</tbody>
</table>
</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>


