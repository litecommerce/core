{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Drupal's profile username
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 * @ListChild (list="checkout.profile.create", weight="30")
 *}
<div class="username" style="display: none;">
  <label for="create_profile_username">{t(#Username#)}:</label>
  <input type="text" id="create_profile_username" name="username" value="{getUsername()}" class="field-required progress-mark-owner watcher" />
  <p class="username-verified" style="display: none;">{t(#Username verified. The password will be sent to your e-mail after the order is placed#)}</p>
</div>
