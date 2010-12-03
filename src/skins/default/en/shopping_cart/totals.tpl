{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table align="right">

  <widget module="CDev\WholesaleTrading" template="modules/CDev/WholesaleTrading/totals.tpl">

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

  <widget module="CDev\GiftCertificates" template="modules/CDev/GiftCertificates/totals.tpl">

  <tr>
    <td><strong>Order total:</strong></td>
    <td align="right"><font class="ProductPriceSmall">{price_format(cart,#total#):h}</font></td>
  </tr>

</table>
