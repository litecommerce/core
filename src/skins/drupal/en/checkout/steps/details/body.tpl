{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order details block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<widget class="XLite_View_Form_Checkout_Place" name="checkout" className="checkout-details" />
  <span class="title">E-mail:</span>&nbsp;&nbsp;<span class="text">{cart.profile.login}</span><br />

  <div IF="cart.shippingMethod" class="shipping-method">
    <span class="title">Shipping method:</span>&nbsp;&nbsp;<span class="text">{cart.shippingMethod.name} ({price_format(cart,#shipping_cost#):h})</span>
    <widget class="XLite_View_Button_Link" label="Change shipping method" style="change" location="{buildURL(#checkout#,##,_ARRAY_(#mode#^#paymentMethod#))}" />
  </div>

  <div class="billing-address-form">

    <h2>Billing address</h2>

    <div class="user-address-form">
      <strong>{cart.profile.billing_firstname} {cart.profile.billing_lastname}</strong><br />
      <br />
      {cart.profile.billing_address}<br />
      {cart.profile.billing_city}, {cart.profile.billingState.state}, {cart.profile.billing_zipcode}<br />
      {cart.profile.billingCountry.country}<br />
      <br />
      {cart.profile.billing_phone}
    </div>

  </div>

  <div class="shipping-address-form">

    <h2>Shipping address</h2>
    <div class="same-address">
      <input type="checkbox" id="same_address" disabled="disabled" checked="{isSameAddress()}" />
      <label for="same_address">The same as billing</label>
    </div>

    <div class="user-address-form">
      <strong>{cart.profile.shipping_firstname} {cart.profile.shipping_lastname}</strong><br />
      <br />
      {cart.profile.shipping_address}<br />
      {cart.profile.shipping_city}, {cart.profile.shippingState.state}, {cart.profile.shipping_zipcode}<br />
      {cart.profile.shippingCountry.country}<br />
      <br />
      {cart.profile.shipping_phone}
    </div>

  </div>

  <div class="center"><widget class="XLite_View_Button_Link" label="Change addresses" location="{buildURL(#checkout#,##,_ARRAY_(#mode#^#register#))}" /></div>

  <div class="payment-method">
    <widget class="XLite_View_Button_Link" label="Change payment method" style="change" location="{buildURL(#checkout#,##,_ARRAY_(#mode#^#paymentMethod#))}" />
    <h2>{cart.paymentMethod.name}</h2> 
    <widget template="{getDir()}/paymentForm.tpl" />
  </div>

  <widget template="checkout/totals.tpl" />

  <div class="notes">
    Customer notes:<br />
    <textarea rows="3" name="notes"></textarea>
  </div>

  <div class="agree">
    <input type="checkbox" name="agree" id="agree" value="Y" />&nbsp;<label for="agree">I accept "<a href="{buildUrl(#help#,##,_ARRAY_(#mode#^#terms_conditions#))}">Terms &amp; Conditions</a>" and "<a href="{buildUrl(#help#,##,_ARRAY_(#mode#^#privacy_statement#))}">Privacy statement</a>".</label> 
  </div>

  <div class="button-row">
    <widget class="XLite_View_Button_Submit" label="Place order" style="bright-button big-button place-button" />
    <div class="submit-progress" style="display: none;">Please wait while your order is being processed...</div>
  </div>

<widget name="checkout" end />
