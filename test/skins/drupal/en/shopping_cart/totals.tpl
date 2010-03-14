{* SVN $Id$ *}
<ul class="cart-sums">

  <widget module="WholesaleTrading" template="modules/WholesaleTrading/totals.tpl">

  <li><em>Subtotal:</em>
    {price_format(cart,#subtotal#):h}
  </li>

  <li><em>Shipping cost:</em>
    <span IF="!cart.shippingAvailable">n/a</span>
    <span IF="cart.shippingAvailable">
      <span IF="!cart.shipped">Free</span>
      <span IF="cart.shipped">{price_format(cart,#shipping_cost#):h}</span>
    </span>
  </li>

  <li IF="!cart.getDisplayTaxes()"><em>Tax:</em>
    n/a
  </li>
  <li FOREACH="cart.getDisplayTaxes(),tax_name,tax"><em>{cart.getTaxLabel(tax_name)}:</em>
    {price_format(tax):h}
  </li>

  <widget module="GiftCertificates" template="modules/GiftCertificates/totals.tpl" />

  <li class="grand-total"><em>Grand total:</em>
    {price_format(cart,#total#):h}
  </li>

</ul>

<widget class="XLite_View_Button_Regular" label="Checkout" action="checkout" style="bright-button big-button checkout-button" />
<widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/gcheckout_notes.tpl" />
