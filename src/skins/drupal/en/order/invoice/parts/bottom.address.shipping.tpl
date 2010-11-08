{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice shipping address
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="invoice.bottom.address", weight="10")
 *}
<td class="ship">
  <strong>{t(#Shipping address#)}</strong>
  <p>
    {order.profile.shipping_address.title} {order.profile.shipping_address.firstname:h} {order.profile.shipping_address.lastname:h}
  </p>

  <p>
    {order.profile.shipping_address.street:h}<br />
    {order.profile.shipping_address.city:h}, {order.profile.shipping_address.state.state:h}, {order.profile.shipping_address.zipcode:h}<br />
    {order.profile.shipping_address.country.country:h}
  </p>

  <p IF="order.profile.shipping_address.phone" class="last">
    {t(#Phone#)}: {order.profile.shipping_address.phone:h}
  </p>
</td>
