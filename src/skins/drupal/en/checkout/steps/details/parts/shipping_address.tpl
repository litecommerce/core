{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout details shipping address block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="chekout.details", weight="40")
 *}
<div class="shipping-address-form">

  <h2>Shipping address</h2>
  <div class="same-address">
    <input type="checkbox" id="same_address" disabled="disabled" checked="{isSameAddress()}" />
    <label for="same_address">The same as billing</label>
  </div>

  <div class="user-address-form">
    <strong>{cart.profile.shipping_address.firstname} {cart.profile.shipping_address.lastname}</strong><br />
    <br />
    {cart.profile.shipping_address.street}<br />
    {cart.profile.shipping_address.city}, {cart.profile.shipping_address.state.state}, {cart.profile.shipping_address.zipcode}<br />
    {cart.profile.shipping_address.country.country}<br />
    <br />
    {cart.profile.shipping_address.phone}
  </div>

</div>
