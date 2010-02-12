<table width="100%">
<tr IF="cart.empty">
    <td><img src="images/cart_empty.gif" alt=""> Cart is empty</td>
</tr>    
<tr IF="!cart.empty">
    <td>
        <table width="100%">
        <tr>
            <td><img src="images/cart_full.gif" alt=""></td>
            <td>
                <b>Items:</b> {cart.itemsCount}<br>
                <b>Total:</b> {price_format(cart,#total#):h}
            </td>
        </tr>    
		<tr>
            <td colspan="2"><hr class="SidebarHr"></td>
        </tr>
        </table>    
    </td>       
</tr>       
<tr>
            <td colspan="2">
			<a IF="{auth.logged&wishlist.products}" href="cart.php?target=wishlist" class="SidebarItems"><img src="images/modules/WishList/wish_list_icon_sm.gif" width="13" height="15" border="0" align="middle" alt=""> Wish list</a>
	        <a IF="{auth.logged&!wishlist.products}" href="cart.php?target=wishlist" class="SidebarItems"><img src="images/modules/WishList/wish_list_icon_empty_sm.gif" width="13" height="15" border="0" align="middle" alt=""> Wish list</a>
            </td>
        </tr>
        <tr IF="!cart.empty">
            <td colspan="2"><a href="cart.php?target=cart" class="SidebarItems"><img src="images/details.gif" width="13" height="13" border="0" align="middle" alt=""> View cart</a></td>
        </tr>
        <tr IF="!cart.empty">
            <td colspan="2"><a href="cart.php?target=checkout" class="SidebarItems"><img src="images/go.gif" width="13" height="13" border="0" align="middle" alt=""> Checkout</a></td>
        </tr>
</table>




