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
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td IF="cart.discountCoupon" valign="top">
<table border="0" cellpadding="1" cellspacing="0" align="center">
<tr>
	<td bgcolor="#B2B2B3">
		<table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr>
			<td colspan="2" height="15" style="background:url('{xlite.layout.path}images/modules/Promotion/coupon_head.gif');" valign="bottom" align="center">&nbsp;&nbsp;<font color="#00224C">Discount coupon</font>&nbsp;&nbsp;</td>
        </tr>

		<tr>
			<td style="height: 80px;" class="DialogBox" width="50" align="center" valign="middle">
				<img height="46" width="44" src="images/modules/Promotion/coupon.gif" border="0" alt="">
			</td>
			<td style="height: 80px;" class="DialogBox" align="center" width="150" valign="middle">
				<span IF="cart.DC.type=#freeship#" class="ProductPriceSmall">Free shipping</span>
				<span IF="cart.DC.type=#absolute#" class="ProductPriceSmall">{price_format(invertSign(cart.DC.discount)):h}</span>
				<span IF="cart.DC.type=#percent#" class="ProductPriceSmall">-&nbsp;{cart.DC.discount}&nbsp;%</span>
				<br>
                {if:!cart.DC.type=#freeship#}
				<span IF="cart.DC.applyTo=#product#">on product<br><a href="cart.php?target=product&amp;product_id={cart.DC.product_id}">{cart.DC.product.name}</a></span>
				<span IF="cart.DC.applyTo=#category#">on category<br>'<a href="cart.php?target=category&amp;category_id={cart.DC.category_id}">{cart.DC.category.name}</a>'</span>
				<span IF="cart.DC.applyTo=#total#"><span IF="!cart.DC.minamount=0">to orders<br>greater than {price_format(cart.DC.minamount):h}</span></span>
				<span IF="cart.DC.applyTo=#total#"><span IF="cart.DC.minamount=0">on all orders</span></span>
				<br>
                {end:}
				<br>
				<a href="cart.php?target=cart&amp;action=discount_coupon_delete"><img width="54" height="14" border="0" src="images/modules/Promotion/delete_button.gif" alt=""></a>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</td>
<td valign="top">
<table align="right" border="0">
<widget module="CDev\WholesaleTrading" template="modules/CDev/WholesaleTrading/totals.tpl">
<tr><td><b>Subtotal:</b></td><td align="right">{price_format(cart.subtotal):h}</td></tr>
<tr IF="!cart.discount=0"><td><b>Discount:</b></td><td align="right">{price_format(invertSign(cart.discount)):h}</td></tr>
<tr><td><b>Shipping:</b></td>
	<td align="right">
		<span IF="!cart.shippingAvailable">n/a</span>
		<span IF="cart.shippingAvailable">
			<span IF="!cart.shipped" class="ProductPriceSmall">Free</span>
			<span IF="cart.shipped">{price_format(cart.shipping_cost):h}</span>
		</span>
	</td>
</tr>
<tr IF="!cart.payedByPoints=0">
	<td><b>Paid with bonus points:</b></td>
	<td align="right">
		{price_format(invertSign(cart.payedByPoints)):h}
	</td>
</tr>
<tr FOREACH="cart.displayTaxes,tax_name,tax">
	<td><b>{cart.getTaxLabel(tax_name)}:</b></td>
	<td align="right">
		{price_format(tax):h}
	</td>
</tr>
<widget module="CDev\GiftCertificates" template="modules/CDev/GiftCertificates/totals.tpl">
<tr><td><b>Order total:</b></td><td align="right"><font class="ProductPriceSmall">{price_format(cart.total):h}</font></td></tr>
</table>
</td>
</tr>
</table>
