{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order's billing address
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="order.payment", weight="200")
 *}

<div class="address" IF="order.profile&order.profile.getBillingAddress()">
  <strong>{t(#Billing address#)}</strong>
  <p class="name">{order.profile.billing_address.title} {order.profile.billing_address.firstname} {order.profile.billing_address.lastname}</p>

  <p class="address-line">
    {order.profile.billing_address.street}<br />
    {order.profile.billing_address.city}, {order.profile.billing_address.state.state}, {order.profile.billing_address.zipcode}<br />
    {order.profile.billing_address.country.country}
  </p>

  <p IF="order.profile.billing_address.phone" class="phone">
    {t(#Phone#)}: {order.profile.billing_address.phone}
  </p>
</div>
