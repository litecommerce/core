{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping address : street
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="checkout.shipping.address", weight="20")
 *}
<li class="street">
  <label for="shipping_address_street">{t(#Address#)}:</label>
  <input type="text" id="shipping_address_street" name="shippingAddress[street]" value="{address.street}" class="field-required" />
</li>
