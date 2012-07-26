{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice billing address
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="invoice.bottom.address", weight="20")
 *}
<td class="bill" IF="order.profile&order.profile.getBillingAddress()">
  <strong>{t(#Billing address#)}</strong>
  <p>
    {order.profile.billing_address.title} {order.profile.billing_address.firstname} {order.profile.billing_address.lastname}
  </p>

  <p>
    {order.profile.billing_address.street}<br />
    {order.profile.billing_address.city}, {order.profile.billing_address.state.state}, {order.profile.billing_address.zipcode}<br />
    {order.profile.billing_address.country.country}
  </p>

  <p IF="order.profile.billing_address.phone">
    {t(#Phone#)}: {order.profile.billing_address.phone}
  </p>

  <p>
    {t(#E-mail#)}: <a href="mailto:{order.profile.login}">{order.profile.login}</a>
  </p>

</td>
