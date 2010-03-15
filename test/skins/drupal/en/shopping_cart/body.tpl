{* SVN $Id$ *}
<div id="shopping-cart">
  <widget class="XLite_View_Form_Cart_Main" name="cart_form" />

    <table class="cart-items">
      <tbody>
        <tr class="cart-item" FOREACH="cart.items,cart_id,item">
          <widget template="shopping_cart/item.tpl" IF="item.isUseStandardTemplate()" />
          <widget module="GiftCertificates" template="modules/GiftCertificates/item.tpl" IF="item.gcid" />
        </tr>
      </tbody>
    </table>

    <div class="cart-totals">
      <widget template="shopping_cart/totals.tpl">
    </div>

    <div class="cart-buttons">
      <widget class="XLite_View_Button_Regular" label="Clear cart" action="clear" />
      <widget class="XLite_View_Button_Link" label="Continue shopping" location="{session.continueURL}" />
    </div>

    <div class="shipping-estimator">
      <widget template="shopping_cart/delivery.tpl">
    </div>

  <widget name="cart_form" end />
</div>
