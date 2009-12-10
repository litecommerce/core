<tr id="module_update_section_{dialog.module.name}" IF="getAllUpdatesNumber(dialog.module.name)" style="DISPLAY: none;">
	<td colspan="5" class="{widget.css_class}">
<span align=justify IF="getAllUpdatesNumber(dialog.module.name)">Update Manager has detected <b>{getAllUpdatesNumber(dialog.module.name)}</b> updates for {dialog.module.name} {dialog.module.version} module.<br><b>{getAppliedUpdatesNumber(dialog.module.name)}</b> out of <b>{getAllUpdatesNumber(dialog.module.name)}</b> updates have been applied.</span>
<span align=justify IF="!getAllUpdatesNumber(dialog.module.name)">No updates available.</span>

<br>

<table border="0" cellpadding="1" cellspacing="5">
	<tr>
		<td IF="getAllNotAppliedUpdatesNumber(dialog.module.name)"><input type="button" value=" Apply all updates " OnClick="performModuleUpdateAction('apply_all', {widget.form_name}, '{dialog.module.name}');"></td>
		<td IF="getAppliedUpdatesNumber(dialog.module.name)"><input type="button" value=" Undo all updates " OnClick="performModuleUpdateAction('undo_all', {widget.form_name}, '{dialog.module.name}');"></td>
	</tr>
</table>
<br>
	</td>
</tr>
