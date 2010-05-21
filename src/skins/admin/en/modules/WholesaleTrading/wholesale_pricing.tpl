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
<table border="0" cellpadding=2 cellspacing=1>
<tr>
    <td colspan=3>
        <font class="AdminHead">Original price: </font><font class="AdminTitle">{price_format(product.listPrice):h}</font>
		<span IF="config.Taxes.prices_include_tax">&nbsp;(price with tax)</span>
    </td>
</tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tr><td colspan=3>&nbsp;</td></tr>
<tbody IF="wholesalePricing">
    <tr>
        <td colspan=3 class=AdminHead>Wholesale price list</td>
    </tr>    
    <tr><td colspan=3>&nbsp;</td></tr>
</tbody>
<tbody FOREACH="wholesalePricing,idx,wholesale_price">
	<form name="wholesale_price_{wholesale_price.price_id}" action="admin.php" method="POST">
	<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
	<input type="hidden" name="target" value="product">
	<input type="hidden" name="action" value="update_wholesale_pricing">
	<input type="hidden" name="wprice_id" value="{wholesale_price.price_id}">
	<tr class=TableHead>
		<td>Amount</td>
		<td>Price per product</td>
		<td>Membership</td>
	    <td IF="config.Taxes.prices_include_tax">Wholesale price with tax</td>
	</tr>
	<tr>
		<td><input name="w_amount" size="5" value="{wholesale_price.amount}"></td>
		<td align="right"><input name="w_price" size="15" value="{wholesale_price.price}"></td>
		<td align="right">
			<select name="w_membership">
			<option value="all" selected="{wholesale_price.membership=#all#}">All</option>
			<option FOREACH="config.Memberships.memberships,membership" value="{membership}" selected="{wholesale_price.membership=membership}">{membership}</option>
			</select>
		</td>
		<td IF="config.Taxes.prices_include_tax">{price_format(product.getFullPrice(wholesale_price.amount)):h}</td>	
	</tr>
	<tr><td colspan="3">
		<input type="submit" value=" Update ">
		<input type="button" value=" Delete " onclick="document.wholesale_price_{wholesale_price.price_id}.action.value='delete_wholesale_price';document.wholesale_price_{wholesale_price.price_id}.submit();"><br><br>
	</td></tr>
	</form>
</tbody>
    
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
		<td colspan="3" class="AdminTitle">Add wholesale price</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr class=TableHead>
		<td>Amount</td>
		<td>Price per product</td>
		<td>Membership</td>
	</tr>
	<form name="add_wholesale_pricing" action="admin.php" method="POST">
	<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
	<input type="hidden" name="target" value="product">
	<input type="hidden" name="action" value="add_wholesale_pricing">
	<tr>
		<td><input name="wp_amount" size="5"></td>
		<td align="right"><input name="wp_price" size="15"></td>
		<td align="right">
			<select name="wp_membership">
			<option value="all" selected>All</option>
			<option FOREACH="config.Memberships.memberships,membership" value="{membership}">{membership}</option>
			</select>
		</td>
	</tr>
	<tr><td colspan="3"><input type="submit" value=" Add "></td></tr>
	</form>
</table>
