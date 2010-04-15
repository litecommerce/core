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
