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
<p IF="inventories"><font class="AdminHead">Tracking options</font>

<br><br>
<form FOREACH="inventories,idx,ivt" name="inventory_form_{idx}" action="admin.php" method="POST">
<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="action" value="update_tracking_option">
<input type="hidden" name="inventory_id" value="{ivt.inventory_id}">

<table>
<tr class=TableHead><td colspan=2>#{inc(idx)} <font IF="!ivt.enabled" color="#FF0000">(inactive)</font></td></tr>
<tr class=Head>
    <td valign=top>Selected options</td>
    <td valign=top>
        {foreach:ivt.product_options,id,options}
            {foreach:options,option}
                {option:h}<br>
            {end:}    
        {end:}
    </td>
</tr>
<widget module="InventoryTracking" template="modules/InventoryTracking/inventory_sku.tpl" ignoreErrors>
<tr>
    <td valign=top>Quantity in stock</td>
    <td valign=top> <input type="text" name="optdata[amount]" value="{ivt.amount}"></td>
</tr>
<widget module="ProductAdviser" template="modules/ProductAdviser/inventory_changed.tpl" IF="{isNotifyPresent(ivt.inventory_id)}" dialog="{dialog}" inventory="{ivt}">
<tr>
    <td valign=top>Low stock limit *</td>
    <td valign=top><input type="text" name="optdata[low_avail_limit]" value="{ivt.low_avail_limit}"></td>
</tr>
<tr>
    <td>Inventory tracking for this option is</td>
    <td>
        <select name="optdata[enabled]">
            <option value=1 selected="{ivt.enabled=#1#}">enabled</option>
            <option value=0 selected="{ivt.enabled=#0#}">disabled</option>
        </select>
    </td>
</tr>
<tr>
    <td colspan=2>
        <input type="submit" name="update" value="Update" onclick="document.inventory_form_{idx}.action.value='update_tracking_option'; document.inventory_form_{idx}.submit();">
        &nbsp;
        <input type="button" name="delete" value="Delete" onclick="document.inventory_form_{idx}.action.value='delete_tracking_option'; document.inventory_form_{idx}.submit();">
    </td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
</table>
</form>

<hr border="0">
</p>

<form action="admin.php" method="POST">
<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="action" value="add_tracking_option">
<input type="hidden" name="order_by" value="{inc(maxOrderBy)}">

<!-- ADD inventory trackin option form -->

<table border="0">
<tr>
    <td colspan=3 class="AdminTitle">Add tracking option</td>
</tr>
<tr>
    <td colspan=3>&nbsp;</td>
</tr>
<tr class=TableHead>
    <td colspan=2>Select option class</td>
    <td>Option value</td>
</tr>
<tbody FOREACH="productOptions,product_option">
<tr IF="!isEmpty(product_option.options)" class=Head>
    <td valign=middle width=10>
        <input type="checkbox" name="optdata[{product_option.optclass:h}][used]" checked>
    </td>
    <td valign=middle>{product_option.optclass:h}</td>
    </td>
    <td valign=middle>
        <select name="optdata[{product_option.optclass:h}][option]">
            <option FOREACH="product_option.productoptions,option" value="{option.option}">{option.option:h}</option>
        </select>
    </td>
</tr>
</tbody>
<widget module="InventoryTracking" template="modules/InventoryTracking/inventory_sku.tpl" newInventory ignoreErrors>
<tr>
    <td colspan=2>Quantity in stock</td>
    <td><input type="text" name="amount" value="{inventory.amount}"></td>
</tr>
<tr>
    <td colspan=2>Low stock limit *</td>
    <td><input type="text" name="low_avail_limit" value="{inventory.low_avail_limit}"></td>
</tr>
<tr>
    <td colspan=2>Inventory tracking for this option is</td>
    <td>
        <select name="enabled">
            <option value=1 selected="{inventory.enabled=#1#}">enabled</option>
            <option value=0 selected="{inventory.enabled=#0#}">disabled</option>
        </select>
    </td>
</tr>
<tr><td colspan=3><input type="submit" name="submit" value="Add"></td></tr>

</table>
</form>

<p><b>* Low stock limit:</b> the quantity in stock when a notification is sent to  admin</p>
