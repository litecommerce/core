{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Language labels
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

{*TODO total refactoring !*}

<div class="languages-dialog">

<form action="admin.php" method="post" name="search_language_form" >
  <input type="hidden" name="target" value="languages" />
  <input type="hidden" name="action" value="search" />
  <input IF="isTranslatedLanguageSelected()" type="hidden" name="language" value="{language}" />

  <div class="form-panel languages-search-panel">

    <input type="text" name="name" value="{getSearchSubstring()}" class="big" lang="{defaultLanguageObject.code}" xml:lang="{defaultLanguageObject.code}" />

    <widget class="\XLite\View\Button\Submit" label="{t(#Search#)}" style="main-button" />

    <a IF="isTranslatedLanguageSelected()" href="admin.php?target=languages&action=search&language={language}">{t(#Show all labels#)}</a>
    <a IF="!isTranslatedLanguageSelected()" href="admin.php?target=languages&action=search">{t(#Show all labels#)}</a>

    <em IF="isSearch()" class="counter">{t(#N items found#,_ARRAY_(#n#^countLabels()))}</em>

  </div>

</form>

<form action="admin.php" method="get" name="change_language_form" >
  <input type="hidden" name="target" action="languages" />

  <div class="language-translate-panel">
    <div class="left">
      <div class="language">
        <img IF="defaultLanguageObject.flagURL" src="{defaultLanguageObject.flagURL}" alt="{defaultLanguageObject.name}" />
        {t(#X labels#,_ARRAY_(#language#^defaultLanguageObject.name))}
        <span class="separator">{t(#translate to#)}:</span>
      </div>
      <div class="options">
        <a
          href="javascript:void(0);"
          onclick="javascript: return openLanguageOptions(this, {defaultLanguageObject.lng_id}, '{language}', {getPage()});">
          {t(#Language options#)}
        </a>
      </div>
    </div>

    <div class="right">

      <div IF="isAnotherLanguagesAdded()" class="language">
        <input
          IF="isTranslatedLanguageSelected()"
          type="text"
          readonly="readonly"
          value="{translatedLanguage.name}"
          style="background-image: url({translatedLanguage.flagURL});"
          class="big flag" />
        <input IF="!isTranslatedLanguageSelected()" type="text" readonly="readonly" value="" class="big" />
        <a href="javascript:void(0);" class="select" onclick="javascript: return openSelectLanguage(this, '{language}', {getPage()});">{t(#Select language#)}</a>
      </div>

      <div IF="!isAnotherLanguagesAdded()" class="language empty">{t(#No other languages defined#)}</div>

      <div IF="isTranslatedLanguageSelected()" class="options">
        <a
          href="javascript:void(0);"
          onclick="javascript: return openLanguageOptions(this, {translatedLanguage.lng_id}, '{language}', {getPage()});">
          {t(#Language options#)}
        </a>
      </div>

    </div>

  </div>
</form>

<div class="language-buttons-panel">

  <div class="left">
    <widget class="\XLite\View\LanguagesModify\Button\AddNewLabel" language="{language}" page="{getPage()}" style="add-new-label" />
    <widget class="\XLite\View\LanguagesModify\Button\AddNewLanguage" page="{getPage()}" style="add-new-language" />
  </div>

  <div IF="countLabels()" class="right">
    <widget class="\XLite\View\PagerOrig\Simple" pages="{getPages()}" page="{getPage()}" url="{getPagerURL()}" />
    (<strong>{t(#N items total#,_ARRAY_(#n#^countLabels()))}</strong>)
  </div>

  <div class="clear"></div>
</div>

<hr class="tiny languages-separator" />

<form IF="countLabels()" method="post" action="admin.php" name="updateForm" >

  <input type="hidden" name="target" value="languages" />
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="page" value="{getPage()}" />
  <input type="hidden" name="language" value="{language}" />

  <ul class="language-labels">

    <li FOREACH="getLabels(),label">

      <div class="title">

        <input type="checkbox" name="mark[]" value="{label.label_id}" class="mark" />

        <input type="text" readonly="readonly" value="{label.name}" class="name" />

        <a
          href="javascript:void(0);"
          class="edit"
          onclick="javascript: return openEditLabelDialog(this, {label.label_id}, '{language}', {getPage()});">
          <img src="images/spacer.gif" alt="" />
        </a>

        <a
          href="admin.php?target=languages&action=delete_label&label_id={label.label_id}&language={language}&page={getPage()}"
          class="delete">
          <img src="images/spacer.gif" alt="" />
        </a>

      </div>

      <textarea
        name="current[{label.label_id}]"
        {if:isTranslatedLanguageSelected()} class="left"{end:}
        lang="{defaultLanguageObject.code}"
        xml:lang="{defaultLanguageObject.code}">{getLabelDefaultValue(label)}</textarea>

      <textarea
        IF="isTranslatedLanguageSelected()"
        name="translated[{label.label_id}]"
        class="right"
        lang="{translatedLanguage.code}"
        xml:lang="{translatedLanguage.code}"
        {if:translatedLanguage.r2l} dir="rtl"{end:}>{getTranslation(label)}</textarea>

      <div class="clear"></div>

    </li>
  </ul>

  <div class="buttons">
  <list name="languages.actions" />
  </div>

</form>

<div IF="!countLabels()" class="no-language-labels">{t(#Please specify a pattern to find the required labels#)}</div>

</div>
