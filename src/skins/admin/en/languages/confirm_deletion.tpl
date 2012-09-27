{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Confirm deletion
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div class="confirm-language-delete-dialog">
  <h2>{t(#Confirm deletion#)}</h2>
  <div class="body">

    {t(#You are going to delete the X language#,_ARRAY_(#language#^confirmLanguage.name)):h}

    <div class="row">
      <img IF="confirmLanguage.flagURL" src="{confirmLanguage.flagURL}" alt="" class="flag" />
      <span class="name">{confirmLanguage.name}</span>
    </div>

    <form action="admin.php" method="post" name="confirm_delete_form">
      <input type="hidden" name="target" value="languages" />
      <input type="hidden" name="action" value="delete" />
      <input type="hidden" name="lng_id" value="{confirmLanguage.lng_id}" />
      <input type="hidden" name="language" value="{language}" />
      <input type="hidden" name="page" value="{page}" />

      <div class="buttons">
        <widget class="\XLite\View\Button\Submit" label={t(#Delete#)}" style="main-button invert" />
        <widget class="\XLite\View\Button\Regular" label="{t(#Cancel#)}" style="main" jsCode="$(this).parents('.ui-dialog').eq(0).find('.ui-dialog-titlebar-close').click();" />
      </div>
    </form>

  </div>
</div>
