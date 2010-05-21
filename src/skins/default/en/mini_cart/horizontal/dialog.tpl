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
              <strong>Items:</strong> {cart.itemsCount}
              <br />
              <strong>Total:</strong> {price_format(cart,#total#):h}
            </td>
          </tr>  

          <tr>
            <td colspan="2"><hr class="SidebarHr"></td>
          </tr>

          <tr>
            <td colspan="2">
              <img src="images/dark_arrows.gif" width="6" height="6" align="middle" alt="">&nbsp;<a href="{buildURL(#cart#)}" class="SidebarItems">View cart</a>
              <br>
              <img src="images/dark_arrows.gif" width="6" height="6" align="middle" alt="">&nbsp;<a href="{buildURL(#checkout#)}" class="SidebarItems">Checkout</a><br>
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
