{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Billing address : country
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="checkout.payment.address", weight="30")
 *}
<li class="country">
  <label for="billing_address_country">{t(#Country#)}:</label>
  <widget class="\XLite\View\CountrySelect" field="billingAddress[country]" fieldId="billing_address_country" country="{sameAddress.country.code}" allowLabelCountry="true" />
</li>
