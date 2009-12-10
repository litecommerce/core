<table width="100%">
<tr IF="cart.empty">
    <td align="center" class="cart"><img src="images/custom/cart.gif" alt=""> Cart is empty</td>
</tr>
<tr IF="!cart.empty">
    <td>
        <table width="100%">
        <tr>
            <td><img src="images/custom/shopping_cart_full.gif" alt=""></td>
            <td class="CartItems">
                <b>Items:</b> {cart.itemsCount}<br>
                <b>Total:</b> {price_format(cart,#total#):h}
            </td>
        </tr>
        <tr>
            <td colspan="2">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td height="5"><img src="images/spacer.gif" width="1" height="5" alt=""></td>
</tr>
<tr>
    <td height="1" class="bg_menuhr"><img src="images/spacer.gif" width="1" height="1" alt=""></td>
</tr>
<tr>
    <td height="3"><img src="images/spacer.gif" width="1" height="3" alt=""></td>
</tr>
</table></td>
        </tr>
        <tr>
            <td colspan="2">
            <a IF="{auth.logged&wishlist.products}" href="cart.php?target=wishlist" class="SidebarItems"><img src="images/modules/WishList/wish_list_icon_sm.gif" width="13" height="15" border="0" align="middle" alt=""> Wish list</a>
            <a IF="{auth.logged&!wishlist.products}" href="cart.php?target=wishlist" class="SidebarItems"><img src="images/modules/WishList/wish_list_icon_empty_sm.gif" width="13" height="15" border="0" align="middle" alt=""> Wish list</a>
            </td>
        </tr>
        <tr IF="!cart.empty">
            <td colspan="2" align="center"><table cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td>
             <a href="cart.php?target=cart" class="CartItems"> View cart</a>
             <br>
            <a href="cart.php?target=checkout" class="CartItems"> Checkout</a><br></td>
        </tr>
        </table>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>
