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
<form action="admin.php" method="POST" name="inventory_tracking">
<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="action" value="update_product_inventory">
<input type="hidden" name="inventory_data[inventory_id]" value="{product.product_id}">
<table>
<tr>
    <td valign="middle" class=ProductDetails>Quantity in stock</td>
    <td valign="middle" class=ProductDetails>
        <input type="text" name="inventory_data[amount]" size="18" value="{inventory.amount}">
    </td>
</tr>
<widget module="ProductAdviser" template="modules/ProductAdviser/inventory_changed.tpl" visible="{isNotifyPresent(inventory.inventory_id)}" dialog="{dialog}" inventory="{inventory}">
<tr>
    <td valign="middle" class=ProductDetails>
	Low stock limit *
	</td>
    <td valign="middle" class=ProductDetails>
        <input type="text" name="inventory_data[low_avail_limit]" size="18" value="{inventory.low_avail_limit}">
    </td>
</tr>
<tr>
    <td valign=middle class=ProductDetails>
        Inventory tracking for this product is
    </td>
    <td valign=middle class=ProductDetails>
        <select name="inventory_data[enabled]">
            <option value=1 selected="{inventory.enabled=#1#}">enabled</option>
            <option value=0 selected="{inventory.enabled=#0#}">disabled</option>
        </select>
    </td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr>
    <td IF="cardFound" colspan="2" align=center><input type="submit" name="submit" value="Update"></td>
    <td IF="!cardFound" colspan="2" align=center><input type="submit" name="submit" value="Add"></td>
</tr>
</table>
</form>

<p><b>* Low stock limit:</b> A notification is sent to admin when the quantity of product items in stock falls below this limit.</p>
