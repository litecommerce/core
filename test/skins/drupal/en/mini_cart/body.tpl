{* SVN $Id$ *}
{*

Populated variables:

- itemsList - list of cart items to be displayed in the mini cart
- isTruncated - says whether itemsList is no the full list of cart items

- cart.empty - says whether the cart is emtpy, or not
- cart.itemsCount - the number of items in cart
- cart.total - the total sum
- cart.items - collection of items in cart, can be iterated with FOREACH (see below)

- cart.order_id - ?
- cart.profile_id - ?
- cart.orig_profile_id - ?
- cart.subtotal - ? sum of product prices
- cart.shipping_cost - ? shipping cost
- cart.tax - ? applied tax
- cart.shipping_id - ?
- cart.tracking - ?
- cart.date - ?
- cart.status - ?
- cart.payment_method - ?
- cart.details - ?
- cart.detail_lables - ?
- cart.notes - ?
- cart.taxes - ? sum of applied taxes

Cart items can be iterated with FOREACH="cart.items,cart_id,item":

- item.url - url of the product page
- item.thumbnailURL - url of the product thumbnail
- item.name - product name
- item.brief_description - brief product descrioption
- item.weight - item weight (the weight symbol is set in config.General.weight_symbol)
- item.price - unit price
- item.amount - product quantity
- item.total - the total item price
- item.valid - states whether the product is not out of stock and is not disabled from sale
*}
{* Wishlist stub - should be corrected *}
<widget module="WishList" template="modules/WishList/mini_cart/body.tpl" />
{*

Empty cart

*}
<div id="lc-minicart" class="empty" IF="cart.empty">
  {* Toggle button *}
  <img src="images/spacer.gif" class="toggle-button" />
  {* Title *}
  <h3 class="lc-minicart-title">Shopping cart</h3>
  {* Cart items *}
  <div class="lc-minicart-items">
    <p IF="cart.empty">Cart is empty</p>
  </div>
</div>
{*

Cart with items

*}
<div id="lc-minicart" class="collapsed" IF="!cart.empty">
  {* Toggle button *}
  <img src="images/spacer.gif" class="toggle-button" onClick="javascript:xlite_minicart_toggle('lc-minicart');" />
  {* Title *}
  <h3 class="lc-minicart-title">Shopping cart</h3>
  {* Cart items *}
  <div class="lc-minicart-items">
    <ul>
      <li FOREACH="getItemsList(),item">
        <span class="line-item"><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^item.product_id,#category_id#^item.category_id))}">{item.name}</a></span>
        <span class="currency">{price_format(item,#price#):h}</span> x <span class="qty">{item.amount}</span>
      </li>
    </ul>
    <p IF="isTruncated()"><a href="{buildURL(#cart#)}">Other items</a></p>
  </div>
  {* Cart totals *}
  <div class="lc-minicart-totals">
    <p><a href="{buildURL(#cart#)}">{cart.getItemsCount()} items</a></p>
    <ul>
      <li FOREACH="getTotals(),name,value">{name}: <span class="currency">{price_format(value):h}</span></li>
    </ul>
  </div>
  {* Checkout button *}
  <div class="lc-minicart-links"><form action="{buildURL(#checkout#)}" method="post"><button type="submit" class="lc-minicart-checkout">Checkout</button></form></div>
</div>
