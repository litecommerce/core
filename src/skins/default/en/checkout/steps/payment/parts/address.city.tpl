{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Billing address : city
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="checkout.payment.address", weight="50")
 *}
<li class="city">
  <label for="billing_address_city">{t(#City#)}:</label>
  <input type="text" id="billing_address_city" name="billingAddress[city]" value="{sameAddress.city}" class="field-required" />
</li>
