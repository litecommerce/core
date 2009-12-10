<?php
    $find_str = <<<EOT
<tr><td><b>Shipping:</b></td>
	<td align="right">
		<span IF="!cart.shippingAvailable">n/a</span>
		<span IF="cart.shippingAvailable">{price_format(cart,#shipping_cost#):h}</span>
	</td>
</tr>
<tr FOREACH="cart.getDisplayTaxes(),tax_name,tax">
	<td><b>{cart.getTaxLabel(tax_name)}:</b></td>
	<td align="right">
EOT;
    $replace_str = <<<EOT
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
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
