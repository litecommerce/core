{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Minicart (horizontal)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div id="lc-minicart-{displayMode}" class="lc-minicart-{displayMode} {collapsed}">

  <div class="cart-link">
    <a href="{buildURL(#cart#)}"><img src="images/spacer.gif" width="16" height="16" alt="" /></a>
  </div>

  <div class="cart-items" IF="cart.empty">
    <p class="cart-empty">Cart is empty</p>
  </div>

  <div class="cart-items" IF="!cart.empty">
    <p class="toggle-button"><a href="{buildURL(#cart#)}" onclick="javascript: xlite_minicart_toggle('lc-minicart-{displayMode}'); return false;">{cart.getItemsCount()} item(s)</a> </p>
    <div class="items-list">
      <ul>
        <li FOREACH="getItemsList(),item">
          <span class="item-name"><a href="{item.getUrl()}">{item.name}</a></span>
          <widget module="ProductOptions" class="XLite_Module_ProductOptions_View_MinicartSelectedOptions" item="{item}" />
          <span class="item-price">{price_format(item,#price#):h}</span><span class="delimiter">x</span><span class="item-qty">{item.amount}</span>
        </li>
      </ul>
      <p class="other-items"><a href="{buildURL(#cart#)}">View cart</a></p>
    </div>
  </div>

  <div class="cart-totals" IF="!cart.empty">
    <p><span class="delimiter">/</span><span class="cart-total">{price_format(cart,#total#):h}</span></p>
  </div>

  <div id="lc-minilist-{displayMode}" class="lc-minilist lc-minilist-{displayMode}" IF="countWishlistProducts()">
    <p><a href="{buildURL(#wishlist#)}">Wish list: {countWishlistProducts()} item(s)</a></p>
  </div>

  <div class="cart-checkout" IF="!cart.empty">
    <widget class="XLite_View_Button_Link" label="Checkout" location="{buildURL(#checkout#)}">
  </div>

</div>
