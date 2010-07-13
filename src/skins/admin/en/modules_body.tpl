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
<form IF="xlite.mm.modules" action="admin.php" method="post" name="modules_form_{key}">
<input type="hidden" name="module_type" value="{key}" />
<tr>
	<td>
		<table cellspacing=0 cellpadding=0 border=0 width="100%">
		<tr>
			<td class="SidebarTitle" height="18" align=center nowrap>&nbsp;&nbsp;&nbsp;{caption:h}&nbsp;&nbsp;&nbsp;</td>
			<td width=100%>&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>

<tr>
	<td class="CenterBorder">
		<table cellspacing="1" cellpadding="2" border="0" width="100%">	
			<tr>
			    <th class="TableHead" nowrap><img src="images/spacer.gif" width="62" height="5" border="0">Title<img src="images/spacer.gif" width="62" height="5" border="0"></th>
			    <th nowrap class="TableHead">Active<br><input id="activate_modules_{key}" type="checkbox" onClick="this.blur();setChecked('modules_form_{key}','active_module_{key}',this.checked,'{key}');"></th>
			    <th class="TableHead" width="100%">Description</th>
			    <th class="TableHead">Version</th>
			</tr>
			<tbody FOREACH="getModules(key),module_idx,module">
			<tr valign=middle nowrap class="{getRowClass(module_idx,#DialogBox#,#TableRow#)}">
				<td>
					<table border=0 cellspacing="0" cellpadding="0">
					<script language="Javascript" IF="module.enabled">setHeaderChecked('{key}');</script>
                    <tr valign=top nowrap>
					{if:module.enabled&module.showSettingsForm()}
						<td width=13><a href="{module.getSettingsFormLink()}" title="Click to configure module {module.name}" onClick="this.blur()"><img src="images/go.gif" border=0 width=13 align=absmiddle alt="Click to configure module {module.name}"></a></td>
					{else:}
						<td width=13><img src="images/spacer.gif" border=0 width=13 alt=""></td>
					{end:}
						<td>
					{if:module.enabled&module.showSettingsForm()}
							<a href="{module.getSettingsFormLink()}" title="Click to configure module {module.name}" onClick="this.blur()"><b>{module.name}</b></a>
					{else:}
						{if:module.enabled}
							<b>{module.name}</b>
						{else:}
								{module.name}
						{end:}
					{end:}
						</td>
					<tr>
					</table>
				</td>

				<td align=center><input id="active_module_{key}" type="checkbox" name="active_modules[]" value="{module.module_id}" checked="{module.enabled}" onClick="javascript:this.blur();checkUpdated('{key}')"></td>
				<td>{module.getDescription()}</td>
				<td align=center>{module.getVersion()}</td>
			</tr>    
			</tbody>

		</table>
	</td>
</tr>

<tr>
    <td colspan=5 height="5"></td>
</tr>    
<tr>
    <td colspan=5><input type="submit" name="Update" value="Update" id="update_button_{key}"></td>
</tr>
<input type="hidden" name="target" value="modules">
<input type="hidden" name="action" value="update">
<input type="hidden" name="module_id" value="">
<input type="hidden" name="module_name" value="">
</form>
<tr>
    <td colspan=5 height="15"></td>
</tr>
