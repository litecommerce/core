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

  <ul class="address-section shipping-address-section">
    <li FOREACH="getAddressSectionData(order.profile.billing_address),idx,field" class="{field.css_class} address-field">
      <span class="address-title">{t(field.title)}:</span>
      <span class="address-field">{field.value}</span>
      <span class="address-comma">,</span>
    </li>
  </ul>

  <p>
    {t(#E-mail#)}: <a href="mailto:{order.profile.login:h}">{order.profile.login:h}</a>
  </p>

</td>
