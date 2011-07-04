{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Moneybookers configuration page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<ul class="form">

  <li>
    <label for="settings_email">{t(#Merchant's moneybookers.com account#)}</label>
    <input type="text" id="settings_email" name="settings[email]" value="{paymentMethod.getSetting(#email#)}" class="field-required" />
  </li>

  <li>
    <label for="settings_logo_url">{t(#Company logo URL#)}</label>
    <input type="text" id="settings_logo_url" name="settings[logo_url]" value="{paymentMethod.getSetting(#logo_url#)}" />
  </li>

  <li>
    <label for="settings_test">{t(#Processing mode#)}</label>
    <select id="settings_test" name="settings[test]">
      <option value="1" selected="{isSelected(paymentMethod.getSetting(#test#),#1#)}">Test mode</option>
      <option value="0" selected="{isSelected(paymentMethod.getSetting(#test#),#0#)}">Real transaction</option>
    </select>
  </li>

  <li>
    <label for="settings_secret_word">{t(#Secret word#)}</label>
    <input type="text" id="settings_secret_word" value="{paymentMethod.getSetting(#secret_word#)}" name="settings[secret_word]" />
    <div class="description">{t(#The uppercase MD5 value of the ASCII equivalent of the secret word#)}</div>
  </li>

  <li>
    <label for="settings_prefix">{t(#Invoice number prefix#)}</label>
    <input type="text" id="settings_prefix" value="{paymentMethod.getSetting(#prefix#)}" name="settings[prefix]" />
  </li>

</ul>
