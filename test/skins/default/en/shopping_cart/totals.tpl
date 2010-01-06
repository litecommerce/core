<table align="right" border=0>
<widget module="WholesaleTrading" template="modules/WholesaleTrading/totals.tpl">
<tr><td><b>Subtotal:</b></td><td align="right">{price_format(cart,#subtotal#):h}</td></tr>
<tr><td><b>Shipping:</b></td>
	<td align="right">
		<span IF="!cart.shippingAvailable">n/a</span>
		<span IF="cart.shippingAvailable">
			<span IF="!cart.shipped" class="ProductPriceSmall">Free</span>
			<span IF="cart.shipped">{price_format(cart,#shipping_cost#):h}</span>
		</span>
	</td>
</tr>
<tr IF="!cart.getDisplayTaxes()">
    <td><b>Tax:</b></td>
	<td align="right">n/a</td>
</tr>
<tr FOREACH="cart.getDisplayTaxes(),tax_name,tax">
	<td><b>{cart.getTaxLabel(tax_name)}:</b></td>
	<td align="right">
		{price_format(tax):h}
	</td>
</tr>
<widget module="GiftCertificates" template="modules/GiftCertificates/totals.tpl">
<tr><td><b>Order total:</b></td><td align="right"><font class="ProductPriceSmall">{price_format(cart,#total#):h}</font></td></tr>
</table>
