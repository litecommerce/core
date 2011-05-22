{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Delete confirmation template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<p>You have selected to delete your profile. Please, confirm you want to proceed:</p>

<br />

<form action="{buildURL()}" method="post" name="delete_account_form">
  <input type="hidden" name="target" value="profile" />
  <input type="hidden" name="action" value="delete" />

  <div class="button">
    <widget class="\XLite\View\Button\Submit" label="Proceed" style="button-proceed" />

    <widget class="\XLite\View\Button\Regular" label="Cancel" jsCode="document.forms['delete_account_form'].elements['action'].value='cancel_delete'; document.forms['delete_account_form'].submit();" style="button-cancel" />
  </div>

</form>
