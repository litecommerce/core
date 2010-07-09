{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Add (activate) language
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="add-new-language-dialog">
  <h2>{t(#Add new language#)}</h2>
  <ul class="inactive-languages">
    <li FOREACH="getInactiveLanguages(),l"><img IF="l.flagURL" src="{l.flagURL}" alt="" /><span>{l.code}</span><a href="admin.php?target=languages&action=active&lng_id={l.lng_id}&language={l.code}&page={page}">{l.name}</a></li>
  </ul>
</div>


