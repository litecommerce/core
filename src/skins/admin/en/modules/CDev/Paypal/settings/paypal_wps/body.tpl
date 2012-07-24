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

<table cellspacing="1" cellpadding="5" class="settings-table">

  <tr>
    <td class="setting-name">
    <label for="settings_account">{t(#Email address of your PayPal account#)}</label>
    </td>
    <td>
      <input type="text" size="40" id="settings_account" name="settings[account]" value="{paymentMethod.getSetting(#account#)}" class="field-required" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label for="settings_description">{t(#Description of the purchase that will be displayed on PayPal payment page#)}</label>
    </td>
    <td>
    <input type="text" size="40" id="settings_description" name="settings[description]" value="{paymentMethod.getSetting(#description#)}" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label for="settings_prefix">{t(#Order prefix#)}</label>
    </td>
    <td>
    <input type="text" size="40" id="settings_prefix" value="{paymentMethod.getSetting(#prefix#)}" name="settings[prefix]" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label for="settings_mode">{t(#Test/Live mode#)}</label>
    </td>
    <td>
    <widget
      class="\XLite\View\FormField\Select\TestLiveMode"
      fieldId="settings_mode"
      fieldName="settings[mode]"
      fieldOnly=true
      value="{paymentMethod.getSetting(#mode#)}" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label for="settings_address_override">{t(#Address override#)}*</label>
    </td>
    <td>
    <widget
      class="\XLite\View\FormField\Select\YesNo"
      fieldId="settings_address_override"
      fieldName="settings[address_override]"
      fieldOnly=true
      value="{paymentMethod.getSetting(#address_override#)}" />
    </td>
  </tr>

  <tr>
    <td colspan="2" class="note">
      *{t(#The address specified with automatic fill-in variables overrides the PayPal member’s stored address.<br /> Buyers see
the addresses that you pass in, but they cannot edit them. PayPal does not show addresses if they are invalid or omitted.#):h}
    </td>
  </tr>

</table>
