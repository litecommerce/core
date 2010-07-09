{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Select language dialog
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="select-language-dialog">
  <h2>{t(#Select language to edit#)}</h2>
  <ul class="added-languages">
    <li FOREACH="getAddedLanguages(),l">
      {if:canDelete(l)}
        {if:isTranslateLanguage(l)}
          <a href="admin.php?target=languages&action=delete&lng_id={l.lng_id}&page={page}" class="delete" onclick="javascript: return confirmLanguageDelete(this, {l.lng_id}, '', '{page}');"><img src="images/spacer.gif" alt="" /></a>
        {else:}
          <a href="admin.php?target=languages&action=delete&lng_id={l.lng_id}&language={language}&page={page}" class="delete" onclick="javascript: return confirmLanguageDelete(this, {l.lng_id}, '{language}', '{page}');"><img src="images/spacer.gif" alt="" /></a>
        {end:}
      {else:}
        <img src="images/spacer.gif" alt="" class="delete" />
      {end:}
      {if:isInterfaceLanguage(l)}
        <img src="images/spacer.gif" alt="" class="selected" />
      {else:}
        <a href="admin.php?target=languages&action=switch&lng_id={l.lng_id}&language={language}&page={page}"{if:l.enabled} class="switch enabled"{else:} class="switch"{end:}><img src="images/spacer.gif" alt="" /></a>
      {end:}
      <img IF="l.flagURL" src="{l.flagURL}" alt="" class="flag" />
      <span class="code">{l.code}</span>
      {if:canSelect(l)}
        <a href="admin.php?target=languages&language={l.code}&page={page}" class="name">{l.name}</a>
      {else:}
        <span class="name selected">{l.name}</span>
      {end:}
    </li>
  </ul>

  <hr class="tiny" />

  <h2>{t(#Add new language#)}</h2>

  <ul class="inactive-languages">
    <li FOREACH="getInactiveLanguages(),l"><img IF="l.flagURL" src="{l.flagURL}" alt="" /><span>{l.code}</span><a href="admin.php?target=languages&action=active&lng_id={l.lng_id}&language={l.code}&page={page}">{l.name}</a></li>
  </ul>

</div>

