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

{* Checkout pages: cart content *}
<widget module="ProductOptions" template="modules/ProductOptions/selected_options_js.tpl">

<widget class="XLite_View_Form_Checkout_Main" name="checkout_form" />

<table width="100%" cellpadding=5>
<tr class="TableHead">
<td nowrap width="35"><b>Qty</b></td><td nowrap width="35"><b>SKU</b></td><td><b>Product</b></td><td align=right nowrap width="100"><b>Price</b></td><td align=right nowrap width="100"><b>Total</b></td></tr>
<tbody FOREACH="cart.items,key,item">
<tr valign=top>
<td nowrap width="35"><input type=text size=3 name="amount[{key}]" value="{item.amount}"></td>
<td nowrap width="35"><span style="white-space: nowrap">{item.sku}</span></td>
<td IF="!item.hasOptions()"><b><span IF="item.product.product_id"><a href="cart.php?target=product&amp;product_id={item.product.product_id}">{truncate(item,#name#,#30#):h}</a></span><span IF="!item.product.product_id">{truncate(item,#name#,#30#):h}</span></b></td>
<td IF="item.hasOptions()" id="close{key}" style="cursor: hand;" onClick="visibleBox('{key}')"><b><a href="{item.url}">{truncate(item,#name#,#30#):h}</a></b><font IF="{item.hasOptions()}" class=SidebarItem><br>&nbsp;&nbsp;<img src="images/modules/ProductOptions/open.gif" width="13" height="13" border="0" align="absmiddle" alt="Click to view selected product options">&nbsp;Selected options</font></td>
<td IF="item.hasOptions()" id="open{key}" style="display: none; cursor: hand;" onClick="visibleBox('{key}')"><b><span IF="item.product.product_id"><a href="cart.php?target=product&amp;product_id={item.product.product_id}">{truncate(item,#name#,#30#):h}</a></span><span IF="!item.product.product_id">{truncate(item,#name#,#30#):h}</span></b><span IF="{item.hasOptions()}"><table border=0 cellpadding=0 cellspacing=0><tr><td>&nbsp;&nbsp;<img src="images/modules/ProductOptions/close.gif" width="13" height="13" border="0" align="absmiddle" alt="hide options list"></td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td><widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" visible="{item.hasOptions()}"></td></tr></table></span></td>
<td class="ProductPriceSmall" align="right" nowrap width="100"><span style="white-space: nowrap">{price_format(item,#price#):h}</span></td>
<td class="ProductPriceSmall" align="right" nowrap width="100"><span style="white-space: nowrap">{price_format(item,#total#):h}</span></td>
</tr>
<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/checkout_item.tpl" visible="{xlite.PA_InventorySupport}">
</tbody>
<tr>
  <td colspan="10">
    <hr />
    <widget template="shopping_cart/delivery.tpl" />
    <widget template="shopping_cart/totals.tpl" />
    <widget class="XLite_View_Button_Submit" label="Update" />
  </td>
</tr>
</table>

<widget name="checkout_form" end />

<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}">
