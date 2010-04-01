<script language="Javascript">
function visibleBox(id, status)
{
	var Element = document.getElementById(id);
    if (Element) {
    	Element.style.display = ((status) ? "" : "none");
    }
}

function BrowseScheme(field)
{
window.open("admin.php?target=fc_template_popup&formName=templates_form&node="+field,"selecttemplate","width=700,height=550,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no");
}

function SelectScheme(id)
{
    window.location = "admin.php?target={target}&page={page}&scheme_id="+id+"#Options";
}

function DeleteScheme(id,name)
{
	if (confirm("Are you sure you want to delete\nthe '"+name+"' scheme?")) {
		document.modify_form.action.value = "fc_delete";
		document.modify_form.modified_scheme_id.value = id;
		document.modify_form.submit();
	}
}

function CloneScheme(id)
{
	document.modify_form.action.value = "fc_clone";
	document.modify_form.modified_scheme_id.value = id;
	document.modify_form.submit();
}

function HideActionStatus()
{
	visibleBox("action_status", false);
}

function ChangeOptionType(value, spanObj)
{
    if ( value == 'select_box' )
    {
        spanObj.style.display = '';
    } else {
        spanObj.style.display = 'none';
    }
}

function DeleteOption(key)
{
    if ( confirm('Are you sure delete option: ['+key+']') )
    {
        document.options_form.action.value = 'delete_option';
        document.options_form.keyname.value = key;
        document.options_form.submit();
    }
}

function EditOption(key)
{
	document.options_form.action.value = 'edit_option';
	document.options_form.option_name.value = key;
	document.options_form.submit();
}

function cancelUpdateOption()
{
	document.options_form.action.value = 'cancel_update_option';
	document.options_form.submit();
}

function updateOption()
{
	document.options_form.action.value = 'update_option';
	document.options_form.option_name.value = '{option_name}';
	document.options_form.submit();
}

function switchToExpertMode()
{
	document.modify_form.action.value = 'expert_mode';
	document.modify_form.submit();
}

function switchToSimpleMode()
{
	    document.modify_form.action.value = 'simple_mode';
	    document.modify_form.submit();
}

</script>

<p>Use this section to manage your Flyout Categories template schemes.<br>

<span id="action_status" style="display:">
<font IF="status=#clone_failed#" class="ErrorMessage"><br><br>&gt;&gt;&nbsp;Clone scheme failed&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#delete_failed#" class="ErrorMessage"><br><br>&gt;&gt;&nbsp;Delete scheme failed&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#update_failed#" class="ErrorMessage"><br><br>&gt;&gt;&nbsp;Update schemes list failed&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#cloned#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;Scheme cloned successfully&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#deleted#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;Scheme deleted successfully&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#updated#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;Schemes list updated successfully&nbsp;&lt;&lt;<br><br></font>
<font IF="warning=#drop_scheme#" class="ErrorMessage">&gt;&gt;&nbsp;Warning: You have deleted active scheme. Scheme not selected.&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#opt_canceled#" class="ErrorMessage">&gt;&gt;&nbsp;Option update canceled.&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#option_updated#" class="SuccessMessage">&gt;&gt;&nbsp;Option updated.&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#option_exists#" class="ErrorMessage">&gt;&gt;&nbsp;An option with this key already exists.&nbsp;&lt;&lt;<br><br></font>
</span>

<p>
<table border=0 cellpadding="3" cellspacing="2">
<form action="admin.php" method=POST name="modify_form">
<input type=hidden name=action value="fc_update">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="modified_scheme_id" value="">
	<tr class="TableHead">
    	<td>#</td>
    	<td>Pos.</td>
    	<td>Name</td>
    	<td colspan=3 align=center>Actions</td>
	</tr>
	<tbody FOREACH="schemes,k,v">
	<INPUT type=hidden name="schemes_list[{v.scheme_id}][scheme_id]" value="{v.scheme_id}">
	<tr class="{getRowClass(k,#TableRow#)}">
    	<td>{v.scheme_id}{if:isSelected(v.scheme_id,config.FlyoutCategories.scheme)}<font color="red"><b>&nbsp;A</b></font>{end:}</td>
    	<td IF="isReadOnly(v.scheme_id)">
    	&nbsp;{v.order_by}
    	</td>
    	<td IF="!isReadOnly(v.scheme_id)">
    	<INPUT type=text size=3 name="schemes_list[{v.scheme_id}][order_by]" value="{v.order_by}">
    	</td>
    	<td IF="isReadOnly(v.scheme_id)" width=200>
    	&nbsp;{v.name}
    	</td>
    	<td IF="!isReadOnly(v.scheme_id)">
    	<INPUT type=text size=50 name="schemes_list[{v.scheme_id}][name]" value="{v.name}">
    	</td>
    	<td IF="isReadOnly(v.scheme_id)" align=center>
    	<input type=button value="View" onClick="SelectScheme('{v.scheme_id}')">
    	</td>
    	<td IF="!isReadOnly(v.scheme_id)" align=center>
    	<input type=button value="Edit" onClick="SelectScheme('{v.scheme_id}')">
    	</td>
    	<td align=center>
    	<input type=button value="Clone" onClick="CloneScheme('{v.scheme_id}')">
    	</td>
    	<td  align=center>
	    	{if:isReadOnly(v.scheme_id)"}&nbsp;{else:}<input type=button value="Delete" onClick="DeleteScheme('{v.scheme_id}','{v.name}')">{end:}
    	</td>
	</tr>
	</tbody>
	<tr>
		<td colspan="7"><font color="red"><b>A</b></font> - Active scheme. {if:!config.FlyoutCategories.scheme}<font class="ErrorMessage">Now active scheme not selected!</font> {end:}<br>
		<b>Note:</b> You can activate schemes on <a href="admin.php?target=module&page=FlyoutCategories"><u>FlyoutCategories settings</u></a> page.</td>
	</tr>
	<tr>
    	<td colspan=7><hr></td>
	</tr>
	<tr>
    	<td colspan=4></td>
    	<td colspan=3>
    	<input type=submit value="Update list" class="DialogMainButton">
    	</td>
	</tr>
</form>
</table>

<a name="Options"></a>
<span IF="currentScheme">
<p>
<font class="AdminTitle">{currentScheme.name}</font><font class="AdminHead"> scheme templates</font>
<hr>
<table border="0">
<form action="admin.php" method=POST name="templates_form">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type=hidden name=action value="fc_update_templates">
<input type="hidden" name="scheme_id" value="{currentScheme.scheme_id}">
	<tr>
		<td class="FormButton">Templates location</td>
        <td>&nbsp;</td>
        <td IF="isReadOnly(currentScheme.scheme_id)">
        <input type=text name="templates" id="cat_template" size=70 value="{currentScheme.templates}" readonly>
        </td>
        <td IF="!isReadOnly(currentScheme.scheme_id)">
        <input type=text name="templates" id="cat_template" size=70 value="{currentScheme.templates}">
        </td>
        <td IF="isReadOnly(currentScheme.scheme_id)">
        <input type=button value="Browse" disabled>
        </td>
        <td IF="!isReadOnly(currentScheme.scheme_id)">
		<input type=button value="Browse" onClick="BrowseScheme('{currentScheme.templates}')">
        </td>
	</tr>	
	<tr IF="!isReadOnly(currentScheme.scheme_id)">
        <td colspan=6><input type=submit value="Update"></td>
	</tr>	
</form>
</table>


<p>
<table border="0">
<tr>
<td>
{if:!xlite.session.fc_expert_mode}
<input type="button" value="Switch to 'Expert' mode" OnClick="switchToExpertMode();">
{else:}
<input type="button" value="Switch to 'Simple' mode" OnClick="switchToSimpleMode();">
{end:}
</td>	
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><b>Note:</b> After the current scheme options have been modified, please remember to generate the dynamic category menu to reflect the modifications.</td>
</tr>	
</table>
<p>

{* User-defined options *}

{if:xlite.session.fc_expert_mode}
<form action="admin.php" method=POST name="options_form">
<input FOREACH="getallparams(##),_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type=hidden name=action value="fc_update_templates">
<input type="hidden" name="scheme_id" value="{currentScheme.scheme_id}">
<input type="hidden" name="keyname" value="">
<input type="hidden" name="option_name" value="">

<br><br>
<table border="0" cellpadding="2" cellspacing="2" width="100%">

{* User-defined options *}
<tr>
	<td colspan="2" align="left"><font class="AdminTitle">{currentScheme.name:h}</font><font class="AdminHead"> user-defined options</font></td>
</tr>
<tr>
	<td colspan="2"><hr></td>
</tr>
<tr FOREACH="currentScheme.options,k,val">
    <td align="right" width="50%">{val.description:h} [{k:h}]:</td>
    <td align="left" width="50%">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
        <td align="left" width="50%">
        {if:val.type=#text_box#}
        <input type="text" name="options[{k}]" value="{val.value}" disabled>
        {end:}
        {if:val.type=#select_box#}
        <select name="options[{k}]" disabled>
			<option value=""></option>
            <option FOREACH="val.points,v" value="{v}" selected="{isSelected(val.value,v)}">{v}</option>
        </select>
        {end:}
        {if:val.type=#check_box#}
        <input type="checkbox" name="options[{k}]" value="1" checked="{isSelected(val.value,#1#)}" disabled>
        {end:}
        </td>
		<td align="left" width="25%" IF="!isReadOnly(currentScheme.scheme_id)"><input type="button" value="  Edit  " OnClick="EditOption('{k}');"></td>
        <td align="left" width="25%" IF="!isReadOnly(currentScheme.scheme_id)"><input type="button" value="Delete" OnClick="DeleteOption('{k}');"></td>
        </tr>
        </table>
    </td>
</tr>
<tr IF="!currentScheme.options">
	<td colspan="2"><b>Options not defined</b></td>
</tr>


{* Add/Edit user-defined option *}

<span IF="!isReadOnly(currentScheme.scheme_id)">
<tr>
    <td colspan="2" align="left" class="AdminHead">{if:option_name}Edit <font class="AdminTitle">({option_name})</font>{else:}Add{end:} user-defined option</td>
</tr>
<tr>
    <td align="right" width="50%">Keyname:</td>
    <td align="left" width="50%"><input type="text" name="option_keyname" {if:option_name}value="{option_name}"{else:}value="">{end:}</td>
</tr>
<tr>
    <td align="right" width="50%">Option type:</td>
    <td align="left" width="50%">
        <select name="option_type" OnChange="ChangeOptionType(this.value, opt_points);">
            <option value="text_box" selected="{optionParams.type=#text_box#}">Text box</option>
            <option value="select_box" selected="{optionParams.type=#select_box#}">Select box</option>
            <option value="check_box" selected="{optionParams.type=#check_box#}">Checkbox</option>
        </select>
    </td>
</tr>
<tr>
    <td align="right" width="50%">Option description:</td>
    <td align="left" width="50%"><input type="text" name="option_description" {if:option_name}value="{optionParams.description}"{else:}value=""{end:}></td>
</tr>
<tr id="opt_points" style="display: none;">
    <td align="right" width="50%">Option points:</td>
    <td align="left" width="50%"><textarea name="option_points" cols="18" rows="5">{if:option_name}{optionParams.points_str}{end:}</textarea></td>
</tr>

<tr IF="!option_name">
    <td colspan="2" align="middle"><input type="submit" value="Add option" OnClick="document.options_form.action.value='add_option';"></td>
</tr>
<tr IF="option_name">
	<td colspan="2" align="middle"><input type="submit" value="Update option" OnClick="updateOption();">&nbsp;&nbsp;&nbsp;<input type="submit" value=" Cancel " OnClick="cancelUpdateOption();"></td>
</tr>
</span>

</table>

</form>

{end:}  {* //if:xlite.session.fc_expert_mode *}
</span>

<script language="JavaScript" IF="option_name">
	ChangeOptionType('{optionParams.type}', opt_points);
</script>


<script language="Javascript">
<!--

setTimeout("HideActionStatus()", 5000);

-->
</script>
