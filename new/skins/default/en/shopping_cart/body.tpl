
<p IF="cart.empty">
Your shopping cart is empty.
</p>

<form IF="!cart.empty" name="cart_form" action="cart.php" method="POST">
<input type="hidden" foreach="params,param" name="{param}" value="{get(param)}"/>
<input type="hidden" name="action" value="update">
<p align=justify>The items in your shopping cart are listed below. To remove any item click "Delete Item". To place your order, please click "CHECKOUT".</p>

<div FOREACH="cart.items,cart_id,item">
<p>
<widget template="shopping_cart/item.tpl" IF="item.useStandardTemplate"/>
<widget module="GiftCertificates" template="modules/GiftCertificates/item.tpl" IF="item.gcid"/>
</div>
<IMG SRC="images/spacer.gif" class=DialogBorder WIDTH="100%" HEIGHT=1 BORDER="0" alt="">
<widget template="shopping_cart/delivery.tpl">
<table border=0 width="100%">
	<tr><td>
		<widget template="shopping_cart/totals.tpl">
	</td></tr>
</table>
<IMG SRC="images/spacer.gif" class=DialogBorder WIDTH="100%" HEIGHT=1 BORDER="0" alt="">
<widget template="shopping_cart/buttons.tpl">
<widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/gcheckout_notes.tpl">
</form>

<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}">
