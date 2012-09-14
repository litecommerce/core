{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order's shipping address
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="order.shipping", weight="200")
 *}

<div class="address" IF="order.profile&order.profile.getShippingAddress()">
  <strong>{t(#Shipping address#)}</strong>

  <ul class="address-section shipping-address-section">
    <li FOREACH="getAddressSectionData(order.profile.shipping_address),idx,field" class="{field.css_class} address-field">
      <span class="address-title">{t(field.title)}:</span>
      <span class="address-field">{field.value}</span>
      <span class="address-comma">,</span>
    </li>
  </ul>
</div>
