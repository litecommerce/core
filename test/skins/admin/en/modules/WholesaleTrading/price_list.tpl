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
<table border="1" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td rowspan="2"><font size="3"><b>Sku</b></font></td>
<td rowspan="2"><font size="3"><b>Title</b></font></td>
{if:membership=#all#}
<td colspan="4" align="center"><font size="3"><b>Price</b></font></td>
{else:}
<td colspan="3" align="center"><font size="3"><b>Price</b></font></td>
{end:}
</tr>
<tr>
<td><b>Qty</b></td>
<td IF="membership=#all#"><b>Membership</b></td>
<td><b>Wholesale pricing</b></td>
<td><b>Basic price</b></td>
</tr>
<tbody FOREACH="priceList,category">
<tr IF="category.products">
	{if:membership=#all#}
	<td IF="category.stringPath" align="center" colspan="6"><font size="3"><b>{category.stringPath}</b></font></td>
	{else:}
	<td IF="category.stringPath" align="center" colspan="5"><font size="3"><b>{category.stringPath}</b></font></td>
	{end:}
</tr>
{foreach:category.products,key,product}
<tr>
	<td rowspan="{inc(getWholesaleCount(product.product_id))}">{product.sku}&nbsp;</td>
	{if:getWholesaleCount(product.product_id)=0}
		{if:membership=#all#}
	<td colspan="4">{product.name}&nbsp;</td>
	<td align="right">{price_format(product.price):h}&nbsp;</td>
		{else:}
	<td colspan="3">{product.name}&nbsp;</td>
	<td align="right" colspan="2">{price_format(product.price):h}&nbsp;</td>
		{end:}
	{else:}
	<td rowspan="{inc(getWholesaleCount(product.product_id))}">{product.name}&nbsp;</td>
	<td>Qty</td>
	<td IF="membership=#all#">Membership</td>
	<td>Wholesale pricing</td>
	<td align="right" rowspan="{inc(getWholesaleCount(product.product_id))}">{price_format(product.price):h}&nbsp;</td>
	{end:}
</tr>
<tr FOREACH="getWholesalePricing(product.product_id),w_price">
	<td nowrap>{w_price.amount} or more</td>
	<td IF="membership=#all#">{w_price.membership}&nbsp;</td>
	<td align="right">{price_format(w_price.price):h}&nbsp;</td>
</tr>
{end:}
</tbody>
</table>
