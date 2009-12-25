
<form IF="xlite.mm.modules" action="admin.php" method="post" name="modules_form_{widget.key}">

<tr>
	<td>
		<table cellspacing=0 cellpadding=0 border=0 width="100%">
		<tr>
			<td class="SidebarTitle" height="18" align=center nowrap>&nbsp;&nbsp;&nbsp;{widget.caption:h}&nbsp;&nbsp;&nbsp;</td>
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
			    <th nowrap class="TableHead">Active<br><input id="activate_modules_{widget.key}" type="checkbox" onClick="this.blur();setChecked('modules_form_{widget.key}','active_module_{widget.key}',this.checked,'{widget.key}');"></th>
			    <th class="TableHead" width="100%">Description</th>
			    <th class="TableHead">Version</th>
			    <th class="TableHead">&nbsp;</th>
			</tr>
			<tbody FOREACH="getSortModules(widget.key),module_idx,module">
			<tr valign=middle nowrap class="{getRowClass(module_idx,#DialogBox#,#TableRow#)}">
				<td>
					<table border=0 cellspacing="0" cellpadding="0">
					<script language="Javascript" IF="module.enabled">setHeaderChecked('{widget.key}');</script>
                    <tr valign=top nowrap>
					{if:module.enabled&module.showSettingsForm}
						<td width=13><a href="{module.settingsForm}" title="Click to configure module {module.name}" onClick="this.blur()"><img src="images/go.gif" border=0 width=13 align=absmiddle alt="Click to configure module {module.name}"></a></td>
					{else:}
						<td width=13><img src="images/spacer.gif" border=0 width=13 alt=""></td>
					{end:}
						<td>&nbsp;</td>
						<td>
					{if:module.enabled&module.showSettingsForm}
							<a href="{module.settingsForm}" title="Click to configure module {module.name}" onClick="this.blur()"><b>{module.name}</b></a>
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

				<td align=center><input id="active_module_{widget.key}" type="checkbox" name="active_modules[]" value="{module.module_id}" checked="{module.enabled}" onClick="javascript:this.blur();checkUpdated('{widget.key}')"><input type="hidden" name="installed_modules[]" value="{module.module_id}"></td>
				<td>{module.description}</td>
				<td align=center>{module.version}</td>
				<td><input type="button" value=" Uninstall " onClick="uninstallModule(modules_form_{widget.key}, '{module.module_id}', '{addSlashes(module.name)}')"></td>
			</tr>    

			<tr IF="module.brokenDependencies">
				<td colspan=4 class=ErrorMessage valign=top>&gt;&gt;Cannot initialize module {module.name:h}: dependency modules are not available<br>{foreach:module.brokenDependencies,idx,dep}<li> {dep:h}<br>{end:}</td>
			</tr>
			</tbody>

		</table>
	</td>
</tr>

<tr>
    <td colspan=5 height="5"></td>
</tr>    
<tr>
    <td colspan=5><input type="submit" name="Update" value="Update" id="update_button_{widget.key}"></td>
</tr>
<input type="hidden" name="target" value="modules">
<input type="hidden" name="action" value="update">
<input type="hidden" name="module_id" value="">
<input type="hidden" name="module_name" value="">
</form>
<tr>
    <td colspan=5 height="15"></td>
</tr>    
