{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Qiwi payment method configuration page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<p>{t(#To complete Qiwi payment gateway integration configuration#,_ARRAY_(#successURL#^getQiwiSuccessURL(),#cancelURL#^getQiwiCancelURL(),#callbackURL#^getQiwiCallbackURL())):h}</p>
<table cellspacing="1" cellpadding="5" class="settings-table">

  <tr>
    <td class="setting-name">
    <label for="settings_login">{t(#Login id#)}</label>
    </td>
    <td>
      <input type="text" id="settings_login" name="settings[login]" value="{paymentMethod.getSetting(#login#)}" class="field-required field-integer" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label for="settings_password">{t(#Password#)}</label>
    </td>
    <td>
      <input type="text" id="settings_password" name="settings[password]" value="{paymentMethod.getSetting(#password#)}" class="field-required" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label for="settings_prefix">{t(#Order id prefix#)}</label>
    </td>
    <td>
      <input type="text" id="settings_prefix" name="settings[prefix]" value="{paymentMethod.getSetting(#prefix#)}" class="field-required" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label for="settings_lifetime">{t(#Order lifetime in hours#)}</label>
    </td>
    <td>
      <input type="text" id="settings_lifetime" value="{paymentMethod.getSetting(#lifetime#)}" name="settings[lifetime]" class="field-integer" />
    </td>
  </tr>

  <tr>
    <td class="setting-name">
    <label for="settings_check_agt">{t(#Check whether customer's mobile phone is registered#)}</label>
    </td>
    <td>
      <input type="hidden" value="0" name="settings[check_agt]" />
      <input type="checkbox" id="settings_check_agt" value="1" {if:paymentMethod.getSetting(#check_agt#)}checked="checked"{end:} name="settings[check_agt]" />
    </td>
  </tr>

</table>
