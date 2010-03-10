{* SVN $Id$ *}
<div id="lc-minicart-{displayMode}" class="lc-minicart-{displayMode} {collapsed}">

  <div class="cart-link">
    <h3><a href="{buildURL(#cart#)}">Your cart</a></h3>
  </div>

  <div class="cart-items" IF="cart.empty">
    <span>Cart is empty</span>
  </div>

  <div class="cart-items" IF="!cart.empty">
    <span class="toggle-button"><a href="{buildURL(#cart#)}" onClick="javascript:xlite_minicart_toggle('lc-minicart-{displayMode}'); return false;">{cart.getItemsCount()} item(s)</a> </span>
    <div class="items-list">
      <ul>
        <li FOREACH="getItemsList(),item">
          <span class="item-name"><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^item.product_id,#category_id#^item.category_id))}">{item.name}</a></span>
          <span class="item-price">{price_format(item,#price#):h}</span><span class="delimiter">x</span><span class="item-qty">{item.amount}</span>
        </li>
      </ul>
      <div IF="isTruncated()" class="other-items"><a href="{buildURL(#cart#)}">Other items</a></div>
    </div>
  </div>

  <div class="cart-totals" IF="!cart.empty">
    <span class="cart-total"><span>Total: </span>{price_format(cart,#total#):h}</span>
  </div>

  <div class="cart-checkout" IF="!cart.empty">
    <widget class="XLite_View_Button_Link" label="Checkout" location="{buildURL(#checkout#)}" style="bright">
  </div>

</div>

<div id="lc-minilist-{displayMode}" class="lc-minilist lc-minilist-{displayMode} collapsed" IF="countWishlistProducts()">

  <div class="list-link">
    <h3><a href="{buildURL(#cart#)}">Wishlist</a></h3>
  </div>

  <div class="list-items">
    <span class="toggle-button"><a href="{buildURL(#wishlist#)}" onClick="javascript:xlite_minicart_toggle('lc-minilist-{displayMode}'); return false;">{countWishlistProducts()} item(s)</a> </span>
    <div class="items-list">
      <ul>
        <li FOREACH="getWishlistItems(),item">
          <span class="item-name"><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^item.product_id,#category_id#^item.category_id))}">{item.name}</a></span>
          <span class="item-price">{price_format(item,#price#):h}</span><span class="delimiter">x</span><span class="item-qty">{item.amount}</span>
        </li>
      </ul>
      <div IF="isWishlistTruncated()" class="other-items"><a href="{buildURL(#wishlist#)}">Other items</a></div>
    </div>
  </div>

</div>

