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
    {order.profile.billing_title} {order.profile.billing_firstname:h} {order.profile.billing_lastname:h}
  </p>

  <p>
    {order.profile.billing_address:h}<br />
    {order.profile.billing_city:h}, {order.profile.billingState.state:h}, {order.profile.billing_zipcode:h}<br />
    {order.profile.billing_company:h}
  </p>

  <p IF="order.profile.billing_phone|order.profile.billing_fax">
    {if:order.profile.billing_phone}Phone: {order.profile.billing_phone:h}<br />{end:}
    {if:order.profile.billing_fax}Fax: {order.profile.billing_fax:h}{end:}
  </p>

  <p class="last">
    E-mail: <a href="mailto:{order.profile.login:h}">{order.profile.login:h}</a>
  </p>

</td>

<td class="ship">
  <strong>Shipping address</strong>
  <p>
    {order.profile.shipping_title} {order.profile.shipping_firstname:h} {order.profile.shipping_lastname:h}
  </p>

  <p>
    {order.profile.shipping_address:h}<br />
    {order.profile.shipping_city:h}, {order.profile.shippingState.state:h}, {order.profile.shipping_zipcode:h}<br />
    {order.profile.shipping_company:h}
  </p>

  <p IF="order.profile.shipping_phone|order.profile.shipping_fax" class="last">
    {if:order.profile.shipping_phone}Phone: {order.profile.shipping_phone:h}<br />{end:}
    {if:order.profile.shipping_fax}Fax: {order.profile.shipping_fax:h}{end:}
  </p>
</td>

