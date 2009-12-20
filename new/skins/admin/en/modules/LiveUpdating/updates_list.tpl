<script type="text/javascript" language="JavaScript 1.2" src="skins/admin/en/modules/LiveUpdating/common.js"></script>

<table border="0" cellpadding="2" cellspacing="0" >
<tr>
	<td>Update Manager has found <b>{allUpdatesNumber}</b> updates for your LiteCommerce system.</td>
</tr>
<tr>
	<td><a href="#core"><u>Applied to core</u></a>: <b>{getAppliedUpdatesNumber(#core#)}</b> of <b>{getAllUpdatesNumber(#core#)}</b> updates.</td>
</tr>
<tr>
	<td><a href="#modules"><u>Applied to modules</u></a>: <b>{getAppliedUpdatesNumber(#module#)}</b> of <b>{getAllUpdatesNumber(#module#)}</b> updates.</td>
</tr>
</table>

<script language="Javascript">
<!--

function visibleBox(id, status)
{
	var Element = document.getElementById(id);
    if (Element) {
    	Element.style.display = ((status) ? "" : "none");
    }
}

function ClearAllFilters()
{
	var filters = new Array("search_text", "search_type_1", "search_type_2", "search_type_3", "search_status", "search_period");
    var Element;
    for (var i=0; i<filters.length; i++) {
    	Element = document.getElementById(filters[i]);
    	if (Element) {
    		switch (filters[i]) {
    			case "search_text":
    				Element.value = "";
    			break;
    			case "search_type_1":
    			case "search_type_2":
    			case "search_type_3":
					Element.checked = true;
    			break;
    			case "search_status":
					Element.checked = true;
    			break;
    			case "search_period":
    				Element.selectedIndex = 0;
    			break;
    		}
        }
    }

    SetPeriod();
}

function SetDateElements(dateVal,prefix,custom)
{
	var elm1 = document.getElementById("search_"+prefix+"Year");
	var elm2 = document.getElementById("search_"+prefix+"Month");
	var elm3 = document.getElementById("search_"+prefix+"Day");
	var elm4 = document.getElementById("search_"+prefix+"Raw");
	if (!(elm1 && elm2 && elm3 && elm4))
		return;

    if (!custom) {
    	for(var i=0; i<elm1.length; i++) {
    		if (elm1.options[i] == dateVal.getYear()) {
    			elm1.selectedIndex = i;
    			break;
    		}
    	}
    }
	elm1.disabled = (!custom) ? true : false;

    if (!custom) {
		elm2.selectedIndex = dateVal.getMonth();
	}
	elm2.disabled = (!custom) ? true : false;
    if (!custom) {
		elm3.selectedIndex = (dateVal.getDate() - 1);
	}
	elm3.disabled = (!custom) ? true : false;
    if (!custom) {
    	var dateTime = new String;
        dateTime += dateVal.getTime();
    	elm4.value = dateTime.substr(0,10);
    }
}

function SetMonthPeriod(previous)
{
    var currentDate = new Date();
    var startDate = new Date(currentDate.getYear(),currentDate.getMonth(),currentDate.getDate());

    previous = (previous) ? 1 : 0;
    startDate.setMonth(currentDate.getMonth()-previous);
    startDate.setDate(1);
    SetDateElements(startDate, "startDate");

    var endDate = new Date(startDate.getYear(),startDate.getMonth()+1,1);
    endDate.setDate(endDate.getDate()-1);
    SetDateElements(endDate, "endDate");
}

function SetWeekPeriod(previous)
{
    var currentDate = new Date();
    var startDate = new Date(currentDate.getYear(),currentDate.getMonth(),currentDate.getDate());

    var currentDay = currentDate.getDay();
    if (currentDay == 0) {
    	currentDay = 7;
    }

    previous = (previous) ? 7 : 0;
    startDate.setDate(currentDate.getDate()-currentDay+1-previous);
    SetDateElements(startDate, "startDate");

    var endDate = new Date(startDate.getYear(),startDate.getMonth(),startDate.getDate());
    endDate.setDate(startDate.getDate()+6);
    SetDateElements(endDate, "endDate");
}

function SetDayPeriod(previous)
{
    var currentDate = new Date();
    var startDate = new Date(currentDate.getYear(),currentDate.getMonth(),currentDate.getDate());

    previous = (previous) ? 1 : 0;
    startDate.setDate(currentDate.getDate()-previous);
    SetDateElements(startDate, "startDate");

    var endDate = new Date(startDate.getYear(),startDate.getMonth(),startDate.getDate());
    SetDateElements(endDate, "endDate");
}

function SetCustomPeriod()
{
    SetDateElements(null, "startDate", true);
    SetDateElements(null, "endDate", true);
}

function SetPeriod()
{
	var Element = document.getElementById("search_period");
	if (!Element)
		return;

	var period = Element.options[Element.selectedIndex].value;
	switch (period) {
		case "-1":
		case "0":
			SetDayPeriod();
		break;
		case "1":
			SetDayPeriod(true);
		break;
		case "2":
			SetWeekPeriod();
		break;
		case "3":
			SetWeekPeriod(true);
		break;
		case "4":
			SetMonthPeriod();
		break;
		case "5":
			SetMonthPeriod(true);
		break;
		case "6":
			SetCustomPeriod();
		break;
	}
	visibleBox("custom_dates", ((period == "6")?true:false));
}

function SaveFilters()
{
	document.subscriber_search_form.action.value = "save_filters";
	document.subscriber_search_form.submit();
}

function undoUpdate(module_name, module_version)
{
	if (confirm('Are you sure you want to undo all updates for ' + module_name + ' ' + module_version + ' module?')) {
		document.modules_update_form.module_name.value = module_name;
		document.modules_update_form.action.value = "undo_module_updates";
		document.modules_update_form.submit();
	}
}
-->
</script>

{* Core update *}

<a name="core"></a>
<table width="100%" border=0 cellpadding=3 cellspacing=0>
<form action="admin.php" method="GET" name="subscriber_search_form">
<span FOREACH="dialog.getAllParams(#text,importance,status,period,startDateRaw,endDateRaw,startDateDay,startDateMonth,startDateYear,endDateDay,endDateMonth,endDateYear,module_name#),name,val">
<input type="hidden" name="{name}" value="{val}">
</span>
<input type="hidden" name="action" value="">
<tr>
    <td width="100%">
    	<table cellspacing=0 cellpadding=0 border=0 width="100%">
    	<tr IF="!id=#0#">
    		<td colspan=2>&nbsp;</td>
    	</tr>
    	<tr>
    		<td class="SidebarTitle" align=center width=150 nowrap>Core updates</td>
    		<td width=100%>&nbsp;</td>
    	</tr>
    	<tr>
    		<td class="SidebarTitle" align=center colspan=2 height=3></td>
    	</tr>
    	</table>
    </td>
</tr>
<tr>
    <td>
    	<table cellspacing=0 cellpadding=0 border=0>
    	<tr>
        	<td rowspan=3>&nbsp;&nbsp;&nbsp;</td>
        	<td nowrap valign=top>Severity:&nbsp;</td>
        	<td valign=top>
            	<table cellspacing=0 cellpadding=0 border=0>
            	<tr>
            		<td><input type=checkbox name="importance[0]" id="search_type_1" checked="{checkedImportance(importance,#critical#)}" value="critical" onClick="this.blur()"></td>
            		<td><img src="images/modules/LiveUpdating/update_critical.gif" border=0></td>
					<td>&nbsp;</td>
            		<td>Critical update</td>
            	</tr>
            	<tr>
            		<td><input type=checkbox name="importance[1]" id="search_type_2" checked="{checkedImportance(importance,#bug fix#)}" value="bug fix" onClick="this.blur()"></td>
            		<td><img src="images/modules/LiveUpdating/update_bug_fix.gif" border=0></td>
					<td>&nbsp;</td>
            		<td>Minor update</td>
            	</tr>
            	<tr>
            		<td><input type=checkbox name="importance[2]" id="search_type_3" checked="{checkedImportance(importance,#new feature#)}" value="new feature" onClick="this.blur()"></td>
            		<td><img src="images/modules/LiveUpdating/update_improvement.gif" border=0></td>
					<td>&nbsp;</td>
            		<td>New feature</td>
            	</tr>
            	</table>
        	</td>
        	<td>&nbsp;&nbsp;</td>
			<td style="background:#E8E8E8" width=2></td>
			<td>&nbsp;&nbsp;</td>
        	<td nowrap valign=top>Status:&nbsp;</td>
        	<td valign=top colspan=2>
            	<table cellspacing=0 cellpadding=0 border=0>
            	<tr>
            		<td colspan=3>
                    	<table cellspacing=0 cellpadding=0 border=0>
                    	<tr>
                    		<td><input type=radio name="status" id="search_status" checked="status=#0#" value="0" onClick="this.blur()"></td>
                    		<td>All</td>
                    	</tr>
                    	</table>
            		</td>
            	</tr>
            	<tr>
            		<td>
                    	<table cellspacing=0 cellpadding=0 border=0>
                    	<tr>
                    		<td><input type=radio name="status" checked="status=#N#" value="N" onClick="this.blur()"></td>
                    		<td><img src="images/modules/LiveUpdating/update_not_applied.gif" border=0></td>
							<td>&nbsp;</td>
                    		<td>Not applied</td>
                    	</tr>
                    	</table>
            		</td>
            	</tr>
            	<tr>
            		<td>
                    	<table cellspacing=0 cellpadding=0 border=0>
                    	<tr>
                    		<td><input type=radio name="status" checked="status=#A#" value="A" onClick="this.blur()"></td>
                    		<td><img src="images/modules/LiveUpdating/update_applied.gif" border=0></td>
							<td>&nbsp;</td>
                    		<td>Applied</td>
                    	</tr>
                    	</table>
            		</td>
            	</tr>
            	</table>
        	</td>
        	<td>&nbsp;&nbsp;</td>
			<td style="background:#E8E8E8" width=2></td>
			<td>&nbsp;&nbsp;</td>
        	<td valign=top>
            	<table cellspacing=0 cellpadding=0 border=0>
            	<tr>
                	<td nowrap>Description:</td>
                	<td>&nbsp;</td>
                	<td><input type="text" name="text" id="search_text" value="{text:r}" size=40></td>
            	</tr>
            	<tr><td colspan=3 height=10></td></tr>
            	<tr>
                	<td colspan=3><a href="javascript: ClearAllFilters()" onClick="this.blur()"><img src="images/go.gif" border="0" align="absmiddle" IF="sortby=#type#"> Clear all filters</a></td>
            	</tr>
            	<tr><td colspan=3 height=10></td></tr>
            	<tr>
                	<td colspan=3><a href="javascript: SaveFilters()" onClick="this.blur()"><img src="images/go.gif" border="0" align="absmiddle" IF="sortby=#type#"> Save filter preferences</a></td>
            	</tr>
            	</table>
        	</td>
    	</tr>
        <tr>
        	<td colspan=20><hr></td>
        </tr>
    	<tr>
        	<td valign=top colspan=7>
            	<table cellspacing=5 cellpadding=0 border=0>
            	<tr>
                	<td nowrap>Release period:</td>
                	<td>&nbsp;</td>
                	<td>
                	<select name="period" id="search_period" onChange="SetPeriod()">
                	<option value="-1" selected="period=#-1#">Any</option>
                	<option value="0" selected="period=#0#">Today</option>
                	<option value="1" selected="period=#1#">Yesterday</option>
                	<option value="2" selected="period=#2#">Current week</option>
                	<option value="3" selected="period=#3#">Previous week</option>
                	<option value="4" selected="period=#4#">Current month</option>
                	<option value="5" selected="period=#5#">Previous month</option>
                	<option value="6" selected="period=#6#">Custom period</option>
                	</select>
                	</td>
            	</tr>
            	<tbody id="custom_dates" style="display:none">
            	<tr>
                	<td nowrap>Date from:</td>
                	<td>&nbsp;</td>
                	<td nowrap><widget class="CDate" template="modules/LiveUpdating/form_date.tpl" field="startDate" id_prefix="search_"></td>
            	</tr>
            	<tr>
                	<td nowrap>Date through:</td>
                	<td>&nbsp;</td>
                	<td nowrap><widget class="CDate" template="modules/LiveUpdating/form_date.tpl" field="endDate" id_prefix="search_"></td>
            	</tr>
            	</tbody>
            	</table>
        	</td>
        	<td valign=middle colspan=8>
            	<table cellspacing=0 cellpadding=0 border=0 width=100% valign=middle>
            	<tr>
                	<td>&nbsp;&nbsp;&nbsp;</td>
                	<td>&nbsp;</td>
                	<td width=100% align=right><input type=submit value="View selection"></td>
            	</tr>
            	</table>
        	</td>
    	</tr>
        <tr>
        	<td class="SidebarTitle" align=center height=3 colspan=20></td>
        </tr>
		<tr>
			<td colspan="20" height="20">&nbsp;</td>
		</tr>
        <tr>
        	<td align=left colspan=17>
        	<span IF="!getDownloadedUpdates(#core#)">No updates selected.</span>
        	<span IF="getDownloadedUpdates(#core#)"><b>{foundUpdatesNumber}</b> update<span IF="!foundUpdatesNumber=#1#">s</span> selected.</span>
        	</td>
        </tr>
		<script language="Javascript">SetPeriod()</script>
    	</table>
    </td>
</tr>
</form>
</table>

<br>

<span IF="{getDownloadedUpdates(#core#)}">

<widget class="CPager" data="{getDownloadedUpdates(#core#)}" name="pager" itemsPerPage="{xlite.config.LiveUpdating.number_updates}">

<script language="Javascript">
<!--

function SortBy(field)
{
	document.updates_sort_form.sortby.value = field;
    document.updates_sort_form.submit();
}

-->
</script>


<table border=0 cellpadding=0 cellspacing=0 width=100%>
<form action="admin.php" method="GET" name="updates_sort_form">
<span FOREACH="dialog.getAllParams(#sortby,module_name#),name,val">
<input type="hidden" name="{name}" value="{val}">
</span>
<input type=hidden name=sortby value="{sortby}">
</form>
<tr>
    <td class=CenterBorder>

<table IF="pager.pageData" border=0 cellpadding=2 cellspacing=1 width=100%>
<tr class=TableHead>
    <th class="TableHead">&nbsp;&nbsp;Status</th>
    <th class="TableHead" nowrap><widget template="modules/LiveUpdating/list_header.tpl" anchor="update_id" label="Update ID"></th>
    <th class="TableHead" width=100 nowrap><widget template="modules/LiveUpdating/list_header.tpl" anchor="date" label="Release Date"></th>
    <th class="TableHead" nowrap><widget template="modules/LiveUpdating/list_header.tpl" anchor="importance" label="Severity"></th>
    <th class="TableHead" width=100%>Description</th>
</tr>
<tbody FOREACH="pager.pageData,upd_id,upd">
<tr class=DialogBox>
    <td align="middle"><widget template="modules/LiveUpdating/status_icon.tpl" status="{upd.status}"></td>
    <td align=right>{upd.name}<span IF="upd.status=#A#"><br>Applied at:</span><span IF="upd.status=#O#"><br>Applied at:</span></td>
    <td nowrap>{time_format(upd.date)}<span IF="upd.status=#A#"><br>{time_format(upd.applied)}</span><span IF="upd.status=#O#"><br>{time_format(upd.applied)}</span></td>
    <td>
		<table border=0 cellpadding=3 cellspacing=0 width=100%>
		<tr>
		    <td><widget template="modules/LiveUpdating/fix_icon.tpl" importance="{upd.importance}"></td>
		    <td nowrap align=left>{translateImportanceStr2Visual(upd.importance)}</td>
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

<script language="JavaScript">
function ProcessAction(action)
{
	document.process_update_form.action.value = action;
	document.process_update_form.submit();
}
</script>
<form action="admin.php" method="POST" name="process_update_form">
<span FOREACH="dialog.getAllParams(#action,update_id,module_name#),name,val">
<input type="hidden" name="{name}" value="{val}">
</span>
<input type="hidden" name="action" value="">
<input type="hidden" name="update_id" value="0">
</form>
<br>
<form action="admin.php" method="POST" name="process_updates_form">
<span FOREACH="dialog.getAllParams(#action,module_name#),name,val">
<input type="hidden" name="{name}" value="{val}">
</span>
<input type="hidden" name="action" value="apply_all">
<input type=submit value=" Apply all updates " class="DialogMainButton" IF="applyAllValid"/>&nbsp;&nbsp;&nbsp;
<input type=button value=" Undo all updates " OnClick="ProcessAction('undo_all');" IF="undoAllValid"/>
</form>

</span>


{* Module updates *}

<p>

<a name="modules"></a>
<table width="100%" border=0 cellpadding=3 cellspacing=0>
<tr>
    <td width="100%">
        <table cellspacing=0 cellpadding=0 border=0 width="100%">
        <tr IF="!id=#0#">
            <td colspan=2>&nbsp;</td>
        </tr>
        <tr>
            <td class="SidebarTitle" align=center width=150 nowrap>Module updates</td>
            <td width=100%>&nbsp;</td>
        </tr>
        <tr>
            <td class="SidebarTitle" align=center colspan=2 height=3></td>
        </tr>
        </table>
    </td>
</tr>
</form>
</table>

{if:modulesUpdatesList}
<br>

<form action="admin.php" method="GET" name="modules_update_form">
<span FOREACH="dialog.getAllParams(#text,importance,status,period,startDateRaw,endDateRaw,startDateDay,startDateMonth,startDateYear,endDateDay,endDateMonth,endDateYear,module_name#),name,val">
<input type="hidden" name="{name}" value="{val}">
</span>

<input type="hidden" name="module_name" value="">
<input type="hidden" name="action" value="apply_modules_updates">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td class="CenterBorder">
		<table cellspacing="1" cellpadding="2" border="0" width="100%">
			<tr class="TableHead">
				<th nowrap class="TableHead">{if:getNotAppliedUpdatesNumber(#module#)}<input type="checkbox" OnClick="setChecked('modules_update_form', 'modules', this.checked)">{else:}[#]{end:}</th>
				<th nowrap class="TableHead">Name</th>
				<th nowrap class="TableHead">Version</th>
				<th nowrap class="TableHead">Description</th>
				<th nowrap class="TableHead">Undo updates</th>
			</tr>
			<tr FOREACH="modulesUpdatesList,idx,module" class="{getRowClass(idx,#DialogBox#,#TableRow#)}">
				<td align="middle">{if:module.updates_not_applied}<input type="checkbox" id="modules" name="modules[]" value='{module.name}'>{else:}[#]{end:}</td>
				<td align="left">{module.name:h}<widget template="modules/LiveUpdating/module_version.tpl" moduleValue="{module}"></td>
				<td align="middle">{module.version}</td>
				<td>
					<div id="module_desc_section_{module.name}" style="display: ;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="left">Update Manager has found <b>{module.updates_count}</b> update(s) for {module.name} {module.version} module.<br><b>{module.updates_applied}</b> out of <b>{module.updates_count}</b> updates have been applied.</td>
							<td align="right"><img src="images/modules/LiveUpdating/open.gif" border="" alt="" OnClick="this.blur(); openCloseModuleUpdatesSection('{module.name}')"></td>
						</tr>
					</table>
					</div>
					<div id="module_updates_section_{module.name}" style="display: none;">
					<table border="0" cellpadding="5" cellspacing="0" width="100%">
					<tr><td>

					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="CenterBorder">
							<table cellspacing="1" cellpadding="2" border="0" width="100%">
							<tr class="TableHead">
								<th class="TableHead">#</th>
								<th class="TableHead">Id</th>
								<th class="TableHead">Date</th>
								<th class="TableHead" width="95%">Description</th>
								<th class="TableHead"><img src="images/modules/LiveUpdating/close.gif" border="" alt="" OnClick="this.blur(); openCloseModuleUpdatesSection('{module.name}');"></th>
							</tr>
							<tr FOREACH="module.updates_list,update_idx,update" class="DialogBox">
								<td align="middle"><widget template="modules/LiveUpdating/status_icon.tpl" status="{update.status}"></td>
								<td>{update.update_id}</td>
								<td>{date_format(update.date)}</td>
								<td colspan="2">
									<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td><widget template="modules/LiveUpdating/fix_icon.tpl" importance="{update.importance}"></td>
										<td>{update.description:h}</td>
									</tr>
									</table>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					</table>

					</td></tr>
					</table>
					</div>
				</td>
				<td align="middle"><input type="button" value="Undo all" OnClick="undoUpdate('{module.name}', '{module.version}')" {if:!module.updates_applied}disabled{end:}></td>
			</tr>
		</table>
	</td>
</tr>
</table>

<br>
<input type=submit class="DialogMainButton" value=" Apply all selected updates ">
</form>
{else:}
No module updates found
{end:}
