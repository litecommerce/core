<span IF="!xlite.WishListEnabled">
<table width="100%">
<tr IF="cart.empty">
	<td><img src="images/cart_empty.gif"> Cart is empty</td>
</tr>   
<tr IF="!cart.empty">
	<td>
		<table width="100%">
		<tr>
			<td><img src="images/cart_full.gif"></td>
			<td>
				<b>Items:</b> {cart.itemsCount}<br>
				<b>Total:</b> {price_format(cart,#total#):h}
			</td>
		</tr>   
		<tr>
			<td colspan="2"><hr class="SidebarHr"></td>
		</tr>
		<tr>
			<td colspan="2">
				<a href="cart.php?target=cart" class="SidebarItems"><img src="images/details.gif" width="13" height="13" border="0" align="absmiddle"> View cart</a>
				<br>
				<a href="cart.php?target=checkout" class="SidebarItems"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Checkout</a><br>
			</td>
		</tr>
		</table>   
	</td>   
</tr>
</table>
</span>
<span IF="xlite.WishListEnabled">
<widget module="WishList" template="modules/WishList/mini_cart/body.tpl">
</span>
