{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice addresses
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="invoice.bottom", weight="10")
 *}
<td class="bill">
  <strong>Billing address</strong>
  <p>
    {order.profile.billing_address.title} {order.profile.billing_address.firstname:h} {order.profile.billing_address.lastname:h}
  </p>

  <p>
    {order.profile.billing_address.street:h}<br />
    {order.profile.billing_address.city:h}, {order.profile.billing_address.state.state:h}, {order.profile.billing_address.zipcode:h}<br />
    {order.profile.billing_address.coountry.country:h}
  </p>

  <p IF="order.profile.billing_address.phone">
    Phone: {order.profile.billing_address.phone:h}
  </p>

  <p class="last">
    E-mail: <a href="mailto:{order.profile.login:h}">{order.profile.login:h}</a>
  </p>

</td>

<td class="ship">
  <strong>Shipping address</strong>
  <p>
    {order.profile.shipping_address.title} {order.profile.shipping_address.firstname:h} {order.profile.shipping_address.lastname:h}
  </p>

  <p>
    {order.profile.shipping_address.street:h}<br />
    {order.profile.shipping_address.city:h}, {order.profile.shipping_address.state.state:h}, {order.profile.shipping_address.zipcode:h}<br />
    {order.profile.shipping_address.country.country:h}
  </p>

  <p IF="order.profile.shipping_address.phone" class="last">
    Phone: {order.profile.shipping_address.phone:h}
  </p>
</td>

