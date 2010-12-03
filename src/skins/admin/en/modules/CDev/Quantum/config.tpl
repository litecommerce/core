{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * QuantumGateway configuration page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<ul class="form">

  <li>
    <label for="settings_login">{t(#Login#)}</label>
    <input type="text" id="settings_login" name="settings[login]" value="{paymentMethod.getSetting(#login#)}" class="field-required" />
  </li>

  <li>
    <label for="settings_hash">{t(#MD5 hash value#)}</label>
    <input type="text" id="settings_hash" name="settings[hash]" value="{paymentMethod.getSetting(#hash#)}" />
  </li>

  <li>
    <label for="settings_prefix">{t(#Invoice number prefix#)}</label>
    <input type="text" id="settings_prefix" value="{paymentMethod.getSetting(#prefix#)}" name="settings[prefix]" />
  </li>

</ul>
