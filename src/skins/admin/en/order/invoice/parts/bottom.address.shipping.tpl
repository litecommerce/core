{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice shipping address
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="invoice.bottom.address", weight="10")
 *}
<td class="ship" IF="order.profile&order.profile.getShippingAddress()&getShippingModifier()&shippingModifier.getMethod()">
  <strong>{t(#Shipping address#)}</strong>
  <p>
    {order.profile.shipping_address.title} {order.profile.shipping_address.firstname} {order.profile.shipping_address.lastname}
  </p>

  <p>
    {order.profile.shipping_address.street}<br />
    {order.profile.shipping_address.city}, {order.profile.shipping_address.state.state}, {order.profile.shipping_address.zipcode}<br />
    {order.profile.shipping_address.country.country}
  </p>

  <p IF="order.profile.shipping_address.phone" class="last">
    {t(#Phone#)}: {order.profile.shipping_address.phone}
  </p>
</td>
