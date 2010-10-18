{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout details billing address block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="chekout.details", weight="30")
 *}
<div class="billing-address-form">

  <h2>Billing address</h2>

  <div class="user-address-form">
    <strong>{cart.profile.billing_address.firstname} {cart.profile.billing_address.lastname}</strong><br />
    <br />
    {cart.profile.billing_address.street}<br />
    {cart.profile.billing_address.city}, {cart.profile.billing_address.state.state}, {cart.profile.billing_address.zipcode}<br />
    {cart.profile.billing_address.country.country}<br />
    <br />
    {cart.profile.billing_address.phone}
  </div>

</div>
