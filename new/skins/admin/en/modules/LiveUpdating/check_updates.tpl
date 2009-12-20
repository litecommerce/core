{if:dialog.modulesUpdatesNumber}
Update Manager has found <b>{modulesUpdatesNumber}</b> add-on module{if:!dialog.modulesUpdatesNumber=#1#}s{end:} which ha{if:dialog.modulesUpdatesNumber=#1#}s{else:}ve{end:} {if:dialog.modulesUpdatesNumber=#1#}a {end:} newer version{if:!dialog.modulesUpdatesNumber=#1#}s{end:} available. <a href="admin.php?target=modules" class="NavigationPath" onClick="this.blur()"><b>More details &gt;&gt;&gt;</b></a>
<p>
{end:}

{if:getUpdatesNumber(#0#)=#0#}
<span IF="upToDate">Your LiteCommerce core is up to date.</span>
<span IF="!upToDate">Update Manager has found no new updates for your LiteCommerce system.</span>

<p IF="errMessage">
<HR>
Error: <font class=ErrorMessage>{errMessage}</font>
</p>
{end:}

<span IF="!getUpdatesNumber(#0#)=#0#">
Update Manager has found <b>{updatesNumber}</b> new update{if:!updatesNumber=#1#}s{end:} available for your LiteCommerce system.

<p>

<form action="admin.php" method="POST" name="updates_form">
<span FOREACH="dialog.getAllParams(),name,val">
<input type="hidden" name="{name}" value="{val}">
</span>
<input type="hidden" name="action" value="download_updates">
<table border=0 cellpadding=0 cellspacing=0>
<tr>
    <td class=CenterBorder>

<table border=0 cellpadding=2 cellspacing=1 width=100%>
<tr class=TableHead>
    <th class="TableHead" nowrap>Update ID</td>
    <th class="TableHead" width=100 nowrap>Release Date</td>
	<th class="TableHead" width=100 nowrap>Type</td>
    <th class="TableHead" nowrap>Severity</td>
    <th class="TableHead" width=100%>Description</td>
</tr>
<tbody FOREACH="updates,upd_id,upd">
<tr class=DialogBox>
    <td align=right>{getUpdateName(upd.update_id)}<input type=hidden name="selected[{upd.update_id}]" value="{upd.update_id}"></td>
    <td nowrap>{time_format(upd.date)}</td>
	<td nowrap>{extractUpdateType(upd.version)}</td>
    <td>
		<table border=0 cellpadding=3 cellspacing=0 width=100%>
		<tr>
		    <td><widget template="modules/LiveUpdating/fix_icon.tpl" importance="{upd.importance}"></td>
		    <td nowrap align=left>&nbsp;{translateImportanceStr2Visual(upd.importance)}</td>
		</tr>
		</table>
    </td>
    <td>
		<table border=0 cellpadding=0 cellspacing=0>
		<tr>
		    <td>&nbsp;&nbsp;</td>
		    <td>{upd.description}</td>
		</tr>
		</table>
    </td>
</tr>
</tbody>
</table>

    </td>
</tr>
</table>
<br>

<input type=submit value=" Download updates ">

</form>

</span>
