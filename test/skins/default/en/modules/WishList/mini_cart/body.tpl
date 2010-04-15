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
            <strong>Items:</strong> {cart.getItemsCount()}
            <br />
            <strong>Total:</strong> {price_format(cart,#total#):h}
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
			<a IF="auth.logged&wishlist.products" href="{buildURL(#wishlist#)}" class="SidebarItems"><img src="images/modules/WishList/wish_list_icon_sm.gif" width="13" height="15" align="middle" alt=""> Wish list</a>
	    <a IF="auth.logged&!wishlist.products" href="{buildURL(#wishlist#)}" class="SidebarItems"><img src="images/modules/WishList/wish_list_icon_empty_sm.gif" width="13" height="15" align="middle" alt=""> Wish list</a>
    </td>
  </tr>

  <tr IF="!cart.empty">
    <td colspan="2">
      <a href="{buildURL(#cart#)}" class="SidebarItems"><img src="images/details.gif" width="13" height="13" align="middle" alt=""> View cart</a>
    </td>
  </tr>

  <tr IF="!cart.empty">
    <td colspan="2">
      <a href="{buildURL(#checkout#)}" class="SidebarItems"><img src="images/go.gif" width="13" height="13" align="middle" alt=""> Checkout</a>
    </td>
  </tr>

</table>
