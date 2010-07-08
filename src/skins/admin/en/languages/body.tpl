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
    <input type="text" name="name" value="{getSearchSubstring()}" class="big" />
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

      <div IF="isAnotherLanguagesAdded()" class="language">
        {if:isTranslatedLanguageSelected()}
          <input type="text" readonly="readonly" value="{translatedLanguage.name}" style="background-image: url({translatedLanguage.flagURL});" class="big flag" />
        {else:}
          <input type="text" readonly="readonly" value="" class="big" />
        {end:}
        <a href="javascript:void(0);" class="select" onclick="javascript: openSelectLanguage(); return false;">{t(#Select language#)}</a>
      </div>

      <div IF="!isAnotherLanguagesAdded()" class="language empty">{t(#No other languages defined#)}</div>

      <div IF="isTranslatedLanguageSelected()" class="options">
        <a href="javascript:void(0);" onclick="javascript: return openLanguageOptions(this, {translatedLanguage.lng_id}, '{language}', {getPage()});">{t(#Language options#)}</a>
      </div>

    </div>

  </div>
</form>

<div class="language-buttons-panel">
  <div class="left">
    <widget class="XLite_View_Button_Regular" label="Add new label" jsCode="openAddNewLabel();" style="add-new-label"/>
    <widget class="XLite_View_Button_Regular" label="Add language" jsCode="openAddNewLanguage();" style="add-new-language"/>
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

      <textarea name="current[{label.label_id}]"{if:!isDefaultLanguage()} class="left"{end:}>{label.label}</textarea>
      <textarea IF="!isDefaultLanguage()" name="translated[{label.label_id}]" class="right">{getTranslation(label)}</textarea>
      <div class="clear"></div>

    </li>
  </ul>

</form>

<div IF="!countLabels()" class="no-language-labels">{t(#Please specify a pattern to find the required labels#)}</div>

<div IF="getInactiveLanguages()" class="add-new-language-dialog" style="display: none;">
  <h2>{t(#Add new language#)}</h2>
  <ul class="inactive-languages">
    <li FOREACH="getInactiveLanguages(),l"><img IF="l.flagURL" src="{l.flagURL}" alt="" /><span>{l.code}</span><a href="admin.php?target=languages&action=active&lng_id={l.lng_id}&language={l.code}&page={getPage()}">{l.name}</a></li>
  </ul>
</div>

<div IF="getInactiveLanguages()" class="select-language-dialog" style="display: none;">
  <h2>{t(#Select language to edit#)}</h2>
  <ul class="added-languages">
    <li FOREACH="getAddedLanguages(),l">
      {if:canDelete(l)}
        {if:language=l.code}
          <a href="admin.php?target=languages&action=delete&lng_id={l.lng_id}&page={getPage()}" class="delete" onclick="javascript: return confirmLanguageDelete(this, {l.lng_id}, '', '{l.flagURL}', '{l.name}');"><img src="images/spacer.gif" alt="" /></a>
        {else:}
          <a href="admin.php?target=languages&action=delete&lng_id={l.lng_id}&language={language}&page={getPage()}" class="delete" onclick="javascript: return confirmLanguageDelete(this, {l.lng_id}, '{language}', '{l.flagURL}', '{l.name}');"><img src="images/spacer.gif" alt="" /></a>
        {end:}
      {else:}
        <img src="images/spacer.gif" alt="" class="delete" />
      {end:}
      {if:isCurrentLanguage(l)}
        <img src="images/spacer.gif" alt="" class="selected" />
      {else:}
        <a href="admin.php?target=languages&action=switch&lng_id={l.lng_id}&language={language}&page={getPage()}"{if:l.enabled} class="switch enabled"{else:} class="switch"{end:}><img src="images/spacer.gif" alt="" /></a>
      {end:}
      <img IF="l.flagURL" src="{l.flagURL}" alt="" class="flag" />
      <span class="code">{l.code}</span>
      {if:canSelect(l)}
        <a href="admin.php?target=languages&language={l.code}&page={getPage()}" class="name">{l.name}</a>
      {else:}
        <span class="name selected">{l.name}</span>
      {end:}
    </li>
  </ul>

  <hr class="tiny" />
  <h2>{t(#Add new language#)}</h2>
  <ul class="inactive-languages">
    <li FOREACH="getInactiveLanguages(),l"><img IF="l.flagURL" src="{l.flagURL}" alt="" /><span>{l.code}</span><a href="admin.php?target=languages&action=active&lng_id={l.lng_id}&language={l.code}&page={getPage()}">{l.name}</a></li>
  </ul>
</div>

<div class="add-new-label-dialog label-dialog" style="display: none;">
  <h2>{t(#Add new label#)}</h2>
  {t(#Please specify text labels for each language#,_ARRAY_(#language#^defaultLanguage.name))}
  <form action="admin.php" method="post" name="add_label_form">
    <input type="hidden" name="target" value="languages" />
    <input type="hidden" name="action" value="add" />
    <input type="hidden" name="language" value="{language}" />
    <input type="hidden" name="page" value="{getPage()}" />

    <ul>

      <li>
        <label for="new_name" class="label">{t(#Label name#)}<span> ({t(#required#)})</span></label>
        <input type="text" id="new_name" name="name" value="" class="field-required" />
      </li>

      <li FOREACH="getAddedLanguages(),l">
        <label for="new_label_{l.code}" class="language" style="background-image: url({l.flagURL});">{l.name}<span IF="l.code=defaultLanguage.code"> ({t(#required#)})</span></label>
        <textarea id="new_label_{l.code}" name="label[{l.code}]"{if:l.code=defaultLanguage.code} class="field-required"{end:}></textarea>
      </li>

    </ul>

    <div class="button">
      <widget class="XLite_View_Button_Submit" label="Save" style="main invert" />
    </div>

  </form>
</div>

<div class="confirm-language-delete-dialog" style="display: none;">
  <h2>{t(#Confirm deletion#)}</h2>
  <div class="body">
    {t(#You are going to delete X language#,_ARRAY_(#language#^#<span class="name"></span>#)):h}
    <div class="row">
      <img src="images/spacer.gif" alt="" class="flag" />
      <span class="name"></span>
    </div>
    <form action="admin.php" method="post" name="confirm_delete_form">
      <input type="hidden" name="target" value="languages" />
      <input type="hidden" name="action" value="delete" />
      <input type="hidden" name="lng_id" value="" id="lng_id_remove" />
      <input type="hidden" name="language" value="" id="language_remove" />
      <input type="hidden" name="page" value="{getPage()}" />

      <div class="buttons">
        <widget class="XLite_View_Button_Submit" label="Delete" style="main invert" />
        <widget class="XLite_View_Button_Regular" label="Cancel" style="main" jsCode="$(this).parents('.ui-dialog').eq(0).find('.ui-dialog-titlebar-close').click();" />
      </div>
    </form>
  </div>
</div>

</div>
