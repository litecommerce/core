{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script type="text/javascript">
var confirmNotes = [];
confirmNotes['uninstall'] = '{t(#Are you sure you want to uninstall this add-on?#)}';
confirmNotes['enable']    = '{t(#Are you sure you want to enable this add-on?#)}';
confirmNotes['disable']   = '{t(#Are you sure you want to disable this add-on?#)}';

var dependedAlert = '{t(#The following dependent add-ons will be automatically disabled:#)}';
var depends = [];

<!--
function confirmNote(action, id)
{

  var extraTxt = '';
  if (action == 'disable' && id !== undefined && depends[id] && depends[id].length > 0) {
    extraTxt = "\n" + dependedAlert + "\n";
    for (i in depends[id]) {
      extraTxt += depends[id][i] + "\n";
    }
  }

  return confirmNotes[action]
    ? confirm(confirmNotes[action] + extraTxt)
    : confirm('{t(#Are you sure?#)}');
}
-->
</script>

<div class="modules-filters">

  <div class="tags">
  </div>

  <div class="activity">
    {foreach:getFilters(),fltr,desc}
      <a href="{buildUrl(#modules#,##,_ARRAY_(#filter#^fltr))}" class="upgradable{if:fltr=getFilter()} current{end:}">{t(desc)}</a><span>({getModulesCount(fltr)})</span>
    {end:}
  </div>

  <div class="clear"></div>

</div>

<form action="admin.php" method="post" name="modules_form_{key}" class="modules-list">
  <input type="hidden" name="target" value="modules">
  <input type="hidden" name="action" value="update">
  <input type="hidden" name="module_type" value="{key}" />

  <table cellspacing="0" cellpadding="0" class="items-list data-table modules-list">

    <tr FOREACH="getPageData(),idx,module" class="{getRowClass(idx,##,#TableRow#)}{if:!module.getEnabled()} disabled{end:}">
      {displayListPart(#columns#,_ARRAY_(#module#^module))}
    </tr>

  </table>
</form>
