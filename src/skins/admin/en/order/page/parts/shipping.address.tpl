{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order's shipping address
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.24
 *
 * @ListChild (list="order.shipping", weight="200")
 *}

<div class="address" IF="order.profile&order.profile.getShippingAddress()">
  <strong>{t(#Shipping address#)}</strong>
  <p class="name">{order.profile.shipping_address.title} {order.profile.shipping_address.firstname} {order.profile.shipping_address.lastname}</p>

  <p class="address-line">
    {order.profile.shipping_address.street}<br />
    {order.profile.shipping_address.city}, {order.profile.shipping_address.state.state}, {order.profile.shipping_address.zipcode}<br />
    {order.profile.shipping_address.country.country}
  </p>

  <p IF="order.profile.shipping_address.phone" class="phone">
    {t(#Phone#)}: {order.profile.shipping_address.phone}
  </p>
</div>
