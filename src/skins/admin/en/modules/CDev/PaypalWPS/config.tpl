{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Paypal Website Payments Standard configuration page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.1
 *}
<ul class="form">

  <li>
    <label for="settings_account">{t(#Email address of your PayPal account#)}</label>
    <input type="text" id="settings_account" name="settings[account]" value="{paymentMethod.getSetting(#account#)}" class="field-required" />
  </li>

  <li>
    <label for="settings_description">{t(#Description of the purchase that will be displayed on PayPal payment page#)}</label>
    <input type="text" id="settings_description" name="settings[description]" value="{paymentMethod.getSetting(#description#)}" />
  </li>

  <li>
    <label for="settings_prefix">{t(#Order prefix#)}</label>
    <input type="text" id="settings_prefix" value="{paymentMethod.getSetting(#prefix#)}" name="settings[prefix]" />
  </li>

  <li>
    <label for="settings_mode">{t(#Test/Live mode#)}</label>
    <widget
      class="\XLite\View\FormField\Select\TestLiveMode"
      fieldId="settings_mode"
      fieldName="settings[mode]"
      fieldOnly=true
      value="{paymentMethod.getSetting(#mode#)}" />
  </li>

  <li>
    <label for="settings_address_override">{t(#Address override#)}</label>

    <widget
      class="\XLite\View\FormField\Select\YesNo"
      fieldId="settings_address_override"
      fieldName="settings[address_override]"
      fieldOnly=true
      value="{paymentMethod.getSetting(#address_override#)}" />
  </li>

</ul>
