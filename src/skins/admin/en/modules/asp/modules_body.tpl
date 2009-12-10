
<form IF="xlite.mm.modules" action="cpanel.php" method="post" name="modules_form_{widget.key}">

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
			    <th class="TableHead" width="100%">Description</th>
			    <th class="TableHead">Version</th>
			    <th class="TableHead">&nbsp;</th>
			</tr>
			<tbody FOREACH="getSortModules(widget.key),module_idx,module">
			<tr valign=middle nowrap class="{getRowClass(module_idx,#DialogBox#,#TableRow#)}">
				<td><b>{module.name:h}</b></td>
				<td>{module.description}</td>
				<td align=center>{module.version}</td>
				<td><input type="button" value=" Uninstall "this.blur(); onClick="uninstallModule(modules_form_{widget.key}, '{module.module_id}', '{addSlashes(module.name)}')"></td>
			</tr>    

			<tr IF="module.enabled&!module.licenseValid">
				<td colspan=4 class=ErrorMessage>&gt;&gt;Cannot initialize module {module.name:h}: the module license is invalid</td>
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
<input type="hidden" name="target" value="modules">
<input type="hidden" name="action" value="update">
<input type="hidden" name="module_id" value="">
<input type="hidden" name="module_name" value="">
</form>
<tr>
    <td colspan=5 height="15"></td>
</tr>    
