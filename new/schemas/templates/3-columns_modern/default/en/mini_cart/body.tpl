<div IF="!xlite.WishListEnabled">
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
        <tr>
            <td colspan="2">
             <img src="images/dark_arrows.gif" width="6" height="6" border="0" align="middle" alt="">&nbsp;<a href="cart.php?target=cart" class="SidebarItems">View cart</a>
             <br>
            <img src="images/dark_arrows.gif" width="6" height="6" border="0" align="middle" alt="">&nbsp;<a href="cart.php?target=checkout" class="SidebarItems">Checkout</a><br>
            </td>
        </tr>
        </table>    
    </td>    
</tr>
</table>
</div>
<div IF="xlite.WishListEnabled">
<widget module="WishList" template="modules/WishList/mini_cart/body.tpl">
</div>
