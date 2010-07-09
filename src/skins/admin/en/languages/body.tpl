{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Language labels
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="languages-dialog">

<widget IF="countLabels()" class="XLite_View_SavePanel" formName="updateForm" />

<form action="admin.php" method="post" name="search_language_form" >
  <input type="hidden" name="target" value="languages" />
  <input type="hidden" name="action" value="search" />
  <input IF="isTranslatedLanguageSelected()" type="hidden" name="language" value="{language}" />

  <div class="form-panel languages-search-panel">
    <input type="text" name="name" value="{getSearchSubstring()}" class="big" lang="{defaultLanguage.code}" xml:lang="{defaultLanguage.code}" />
    <widget class="XLite_View_Button_Submit" label="Search" style="main" />
    {if:isTranslatedLanguageSelected()}
      <a href="admin.php?target=languages&action=search&language={language}">{t(#Show all labels#)}</a>
    {else:}
      <a href="admin.php?target=languages&action=search">{t(#Show all labels#)}</a>
    {end:}
    <em IF="isSearch()" class="counter">{t(#N items found#,_ARRAY_(#n#^countLabels()))}</em>
  </div>
</form>

<form action="admin.php" method="get" name="change_language_form" >
  <input type="hidden" name="target" action="languages" />

  <div class="language-translate-panel">
    <div class="left">
      <div class="language">
        <img IF="defaultLanguage.flagURL" src="{defaultLanguage.flagURL}" alt="{defaultLanguage.name}" /> {t(#X labels#,_ARRAY_(#language#^defaultLanguage.name))} <span class="separator">{t(#translate to#)}:</span>
      </div>
      <div class="options">
        <a href="javascript:void(0);" onclick="javascript: return openLanguageOptions(this, {defaultLanguage.lng_id}, '{language}', {getPage()});">{t(#Language options#)}</a>
      </div>
    </div>

    <div class="right">

      {if:isAnotherLanguagesAdded()}

        <div class="language">
          {if:isTranslatedLanguageSelected()}
            <input type="text" readonly="readonly" value="{translatedLanguage.name}" style="background-image: url({translatedLanguage.flagURL});" class="big flag" />
          {else:}
            <input type="text" readonly="readonly" value="" class="big" />
          {end:}
          <a href="javascript:void(0);" class="select" onclick="javascript: return openSelectLanguage(this, '{language}', {getPage()});">{t(#Select language#)}</a>
        </div>

      {else:}  

        <div class="language empty">{t(#No other languages defined#)}</div>

      {end:}

      <div IF="isTranslatedLanguageSelected()" class="options">
        <a href="javascript:void(0);" onclick="javascript: return openLanguageOptions(this, {translatedLanguage.lng_id}, '{language}', {getPage()});">{t(#Language options#)}</a>
      </div>

    </div>

  </div>
</form>

<div class="language-buttons-panel">
  <div class="left">
    <widget class="XLite_View_LanguagesModify_Button_AddNewLabel" language="{language}" page="{getPage()}" style="add-new-label" />
    <widget class="XLite_View_LanguagesModify_Button_AddNewLanguage" page="{getPage()}" style="add-new-language" />
  </div>
  <div IF="countLabels()" class="right">
    <widget class="XLite_View_Pager_Simple" pages="{getPages()}" page="{getPage()}" url="{getPagerURL()}" />
    (<strong>{t(#N items#,_ARRAY_(#n#^countLabels()))}</strong> total)
  </div>
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
        <a href="javascript:void(0);" class="edit" onclick="javascript: return openEditLabelDialog(this, {label.label_id}, '{language}', {getPage()});"><img src="images/spacer.gif" alt="" /></a>
        <a href="admin.php?target=languages&action=delete_label&label_id={label.label_id}&language={language}&page={getPage()}" class="delete"><img src="images/spacer.gif" alt="" /></a>
      </div>

      <textarea name="current[{label.label_id}]"{if:isTranslatedLanguageSelected()} class="left"{end:} lang="{defaultLanguage.code}" xml:lang="{defaultLanguage.code}">{getLabelDefaultValue(label)}</textarea>
      <textarea IF="isTranslatedLanguageSelected()" name="translated[{label.label_id}]" class="right" lang="{translatedLanguage.code}" xml:lang="{translatedLanguage.code}"{if:translatedLanguage.rtl} dir="rtl"{end:}>{getTranslation(label)}</textarea>
      <div class="clear"></div>

    </li>
  </ul>

</form>

<div IF="!countLabels()" class="no-language-labels">{t(#Please specify a pattern to find the required labels#)}</div>

</div>
