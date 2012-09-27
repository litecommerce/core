{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Create proile selector
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="checkout.profile.create", weight="20")
 *}
<div class="selector">
  <input type="hidden" name="create_profile" value="0" />
  <input type="checkbox" id="create_profile_chk" name="create_profile" value="1" checked="{isSeparateProfile()}" />
  <label for="create_profile_chk">{t(#Create an account for later use#)}</label>
  <p{if:!isSeparateProfile()} style="display: none;"{end:}>{t(#Your password will be sent to your e-mail address#)}</p>
</div>
