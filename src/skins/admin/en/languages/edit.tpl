{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Edit label
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="edit-label-dialog label-dialog">
  <h2>{t(#Edit label#)}</h2>
  {t(#Please specify text labels for each language#,_ARRAY_(#language#^defaultLanguage.name))}
  <form action="admin.php" method="post" name="edit_label_form">
    <input type="hidden" name="target" value="languages" />
    <input type="hidden" name="action" value="edit" />
    <input type="hidden" name="label_id" value="{label.label_id}" />
    <input type="hidden" name="language" value="{language}" />
    <input type="hidden" name="page" value="{page}" />

    <ul>

      <li>
        <label for="edit_name" class="label">{t(#Label name#)}</label>
        <input type="text" id="edit_name" name="name" value="{label.name}" readonly="readonly" class="name" />
      </li>

      <li FOREACH="getAddedLanguages(),l">
        <label for="edit_label_{l.code}" class="language" style="background-image: url({l.flagURL});">{l.name}<span IF="isRequiredLanguage(l)"> ({t(#required#)})</span></label>
        <textarea id="edit_label_{l.code}" name="label[{l.code}]"{if:isRequiredLanguage(l)} class="field-required"{end:} lang="{l.code}" xml:lang="{l.code}"{if:l.rtl} dir="rtl"{end:}>{getTranslation(l.code)}</textarea>
      </li>

    </ul>

    <div class="button">
      <widget class="\XLite\View\Button\Submit" label="Save" style="main invert" />
    </div>

  </form>
</div>
