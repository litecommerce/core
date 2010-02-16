{* SVN $Id$ *}
<table align="right">

  <widget module="WholesaleTrading" template="modules/WholesaleTrading/totals.tpl">

  <tr>
    <td><strong>Subtotal:</strong></td>
    <td align="right">{price_format(cart,#subtotal#):h}</td>
  </tr>

  <tr>
    <td><strong>Shipping:</strong></td>
	  <td align="right">
		  <span IF="!cart.shippingAvailable">n/a</span>
  		<span IF="cart.shippingAvailable">
		  	<span IF="!cart.shipped" class="ProductPriceSmall">Free</span>
			  <span IF="cart.shipped">{price_format(cart,#shipping_cost#):h}</span>
  		</span>
	  </td>
  </tr>

  <tr IF="!cart.getDisplayTaxes()">
    <td><strong>Tax:</strong></td>
	  <td align="right">n/a</td>
  </tr>

  <tr FOREACH="cart.getDisplayTaxes(),tax_name,tax">
	  <td><strong>{cart.getTaxLabel(tax_name)}:</strong></td>
  	<td align="right">{price_format(tax):h}</td>
  </tr>

  <widget module="GiftCertificates" template="modules/GiftCertificates/totals.tpl">

  <tr>
    <td><strong>Order total:</strong></td>
    <td align="right"><font class="ProductPriceSmall">{price_format(cart,#total#):h}</font></td>
  </tr>

</table>
