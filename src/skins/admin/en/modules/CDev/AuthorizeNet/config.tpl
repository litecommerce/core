{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Authorize.Net SIM configuration page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<ul class="form">

  <li>
    <label for="settings_login">{t(#Merchant account username#)}</label>
    <input type="text" id="settings_login" name="settings[login]" value="{paymentMethod.getSetting(#login#)}" />
  </li>

  <li>
    <label for="settings_key">{t(#Transaction key#)}</label>
    <input type="text" id="settings_key" name="settings[key]" value="{paymentMethod.getSetting(#key#)}" />
    <div class="note">
To obtain the transaction key from the Merchant Interface, do the following:<br />
1. Log into the Merchant Interface<br />
2. Select Settings from the Main Menu<br />
3. Click on Obtain Transaction Key in the Security section<br />
4. Type in the answer to the secret question configured on setup<br />
5. Click Submit
    </div>
  </li>

  <li>
    <label for="settings_test">{t(#Processing mode#)}</label>
    <select id="settings_test" name="settings[test]">
      <option value="1" selected="{isSelected(paymentMethod.getSetting(#test#),#1#)}">Test mode</option>
      <option value="0" selected="{isSelected(paymentMethod.getSetting(#test#),#0#)}">Real transaction</option>
    </select>
  </li>

  <li>
    <label for="settings_prefix">{t(#Invoice number prefix#)}</label>
    <input type="text" id="settings_prefix" value="{paymentMethod.getSetting(#prefix#)}" name="settings[prefix]" />
  </li>

</ul>
