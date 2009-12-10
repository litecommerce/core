{* Checkout pages: cart content *}
<widget module="ProductOptions" template="modules/ProductOptions/selected_options_js.tpl">
<form name="cart_form" action="cart.php" method="POST">
<table width=100% cellpadding=5>
<input type="hidden" name="target" value="checkout">
<input type="hidden" name="action" value="update">
<tr class="TableHead">
<td><b>Qty</b></td><td><b>SKU</b></td><td><b>Product</b></td><td align=right><b>Price</b></td><td align=right><b>Total</b></td></tr>
<tbody FOREACH="cart.items,key,item">
<tr valign=top>
<td><input type=text size=3 name="amount[{key}]" value="{item.amount}"></td>
<td>{item.sku}</td>
<td IF="!item.hasOptions()"><b><span IF="item.product.product_id"><a href="cart.php?target=product&product_id={item.product.product_id}">{truncate(item,#name#,#30#):h}</a></span><span IF="!item.product.product_id">{truncate(item,#name#,#30#):h}</span></b></td>
<td IF="item.hasOptions()" id="close{key}" style="cursor: hand;" onClick="visibleBox('{key}')"><b><a href="{item.url}">{truncate(item,#name#,#30#):h}</a></b><font IF="{item.hasOptions()}" class=SidebarItem><br>&nbsp;&nbsp;<img src="images/modules/ProductOptions/open.gif" width="13" height="13" border="0" align="absmiddle" alt="Click to view selected product options">&nbsp;Selected options</font></td>
<td IF="item.hasOptions()" id="open{key}" style="display: none; cursor: hand;" onClick="visibleBox('{key}')"><b><span IF="item.product.product_id"><a href="cart.php?target=product&product_id={item.product.product_id}">{truncate(item,#name#,#30#):h}</a></span><span IF="!item.product.product_id">{truncate(item,#name#,#30#):h}</span></b><span IF="{item.hasOptions()}"><table border=0 cellpadding=0 cellspacing=0><tr><td>&nbsp;&nbsp;<img src="images/modules/ProductOptions/close.gif" width="13" height="13" border="0" align="absmiddle" alt="hide options list"></td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td><widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" visible="{item.hasOptions()}"></td></tr></table></span></td>
<td class="ProductPriceSmall" align="right">{price_format(item,#price#):h}</td>
<td class="ProductPriceSmall" align="right">{price_format(item,#total#):h}</td>
</tr>
<tr IF="!item.valid">
<td>&nbsp;</td>
<td colspan=4><font class="ProductPriceSmall">(!) This product is out of stock or it has been disabled for sale.</font></td>
</tr>
<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/checkout_item.tpl" visible="{xlite.PA_InventorySupport}">
</tbody>
</table>
<hr>
<widget template="shopping_cart/delivery.tpl">
<widget template="shopping_cart/totals.tpl">
<widget class="CButton" label="Update" href="javascript: document.cart_form.action.value='update';document.cart_form.submit()" font="FormButton">
</form>
<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}">
