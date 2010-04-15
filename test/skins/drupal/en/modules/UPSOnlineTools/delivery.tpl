{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Delivery methods block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div IF="xlite.session.ups_failed_items&cart.shippingMethod.class=#ups#" class="shipping-warning">
United Parcel Service carrier is unavailable. One or more items added to cart exceed the size or the weight limit of the container. Please contact the <a href="mailto:{config.Company.site_administrator:h}">store administrator</a>.
</div>

<div IF="xlite.session.ups_rates_error&cart.shippingMethod.class=#ups#" class="shipping-warning"> 
United Parcel Service return error: ({xlite.session.ups_rates_error})<br />
Please contact the <a href="mailto:{config.Company.site_administrator:h}">store administrator</a>.
</div>

<div IF="cart.shippingAvailable&cart.shipped&cart.getCarriers()" class="carriers">
  <select name="carrier" onchange="javascript: this.form.submit();">
    <option FOREACH="cart.getCarriers(),key,carrier" value="{key}" selected="{cart.isSelected(#carrier#,key)}">{carrier:h}</option>
  </select>
</div>

<ul IF="cart.shippingAvailable&cart.shipped&cart.getCarrierRates()" class="deliveries">
  <li FOREACH="cart.getCarrierRates(),key,rate">
    <input type="radio" id="shipping_{rate.shipping.shipping_id}" name="shipping" onclick="javascript: this.form.submit();" value="{rate.shipping.shipping_id}" checked="{cart.isSelected(#shipping_id#,key)}" />
    <label for="shipping_{rate.shipping.shipping_id}"{if:cart.isSelected(#shipping_id#,key)} class="selected"{end:}>{rate.shipping.name:h} ({price_format(rate,#rate#):h})</label>
  </li>
</ul>

<widget template="modules/UPSOnlineTools/notice.tpl" IF="cart.shippingMethod.class=#ups#" />
