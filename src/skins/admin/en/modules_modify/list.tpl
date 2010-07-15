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
<form IF="mm.findAll()" action="admin.php" method="post" name="modules_form_{key}" class="modules-list">
  <input type="hidden" name="target" value="modules">
  <input type="hidden" name="action" value="update">
  <input type="hidden" name="module_type" value="{key}" />

  <h2>{t(caption)}</h2>

  <table cellspacing="1" class="data-table">

    <tr>
      <th>{t(#Active#)}<br /><input type="checkbox" class="column-selector" /></th>
      <th>{t(#Title#)}</th>
			<th class="extender">{t(#Description#)}</th>
      <th>{t(#Dependencies#)}</th>
			<th>{t(#Version#)}</th>
      <th>{t(#Status#)}</th>
      <th>&nbsp;</th>
		</tr>

		<tr FOREACH="getModules(key),module_idx,module" class="{getRowClass(module_idx,##,#highlight#)}">
      <td class="active">
        <a name="{module.getName()}"></a>
        <input type="checkbox" name="active_modules[]" value="{module.getModuleId()}"{if:module.getEnabled()} checked="checked"{end:}{if:!canEnable(module)} disabled="disabled"{end:} />
      </td>
      <td{if:module.getEnabled()} class="enabled"{end:}>{module.getName()}</td>
      <td>{module.getDescription()}</td>
      <td class="dependencies">
        {if:module.getDependencies()}
          <ul>
            <li FOREACH="module.getDependenciesModules(),depend">
              <a href="#{depend.getName()}">{depend.getName()}</a>
              [ {if:depend.getEnabled()}<span class="good">{t(#enabled#)}</span>{else:}<span class="none">{t(#disabled#)}</span>{end:} ]
            </li>
          </ul>
        {else:}
          {t(#none#)}
        {end:}
      </td>
      <td>{module.getVersion()}</td>
      <td class="status">{getModuleStatus(module):h}</td>
      <td>
        {if:module.enabled&module.showSettingsForm()}
          <a href="{module.getSettingsFormLink()}">{t(#Configure#)}</a>
        {end:}
        <a href="{buildUrl(#admin#,#uninstall#,_ARRAY_(#module_id#^module.getModuleId()))}" onclick="javascript: return confirmUninstall();">{t(#Uninstall#)}</a>
      </td>
		</tr>    

  </table>

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" label="Update" />
  </div>

</form>
