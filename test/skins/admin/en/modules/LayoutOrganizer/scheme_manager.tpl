<script language="Javascript">
<!--

function visibleBox(id, status)
{
	var Element = document.getElementById(id);
    if (Element) {
    	Element.style.display = ((status) ? "" : "none");
    }
}

function SelectTemplate(field)
{
	window.open("admin.php?target=lo_template_popup&formName=templates_form&formField="+field,"selecttemplate","width=700,height=550,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no");
}

function EditTemplate(field,scheme)
{
	var Element = document.getElementById(field);
    if (Element) {
    	window.location = "admin.php?target=template_editor&editor=advanced&zone=default&mode=edit&scheme_manager="+scheme+"&file="+Element.value;
    }
}

function SelectScheme(id)
{
    window.location = "admin.php?target=scheme_manager&scheme_id="+id;
}

function DeleteScheme(id,name)
{
	if (confirm("Are you sure you want to delete\nthe '"+name+"' scheme?")) {
		document.modify_form.action.value = "delete";
		document.modify_form.modified_scheme_id.value = id;
		document.modify_form.submit();
	}
}

function CloneScheme(id)
{
	document.modify_form.action.value = "clone";
	document.modify_form.modified_scheme_id.value = id;
	document.modify_form.submit();
}

function HideActionStatus()
{
	visibleBox("action_status", false);
}

-->
</script>

<p>Use this section to manage your template schemes.<br>
These schemes can be assigned to individual categories and products at your store to give them their unique look and feel.

<span id="action_status" style="display:">
<font IF="status=#clone_failed#" class="ErrorMessage"><br><br>&gt;&gt;&nbsp;Clone scheme failed&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#delete_failed#" class="ErrorMessage"><br><br>&gt;&gt;&nbsp;Delete scheme failed&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#update_failed#" class="ErrorMessage"><br><br>&gt;&gt;&nbsp;Update schemes list failed&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#cloned#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;Scheme cloned successfully&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#deleted#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;Scheme deleted successfully&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#updated#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;Schemes list updated successfully&nbsp;&lt;&lt;<br><br></font>
</span>

<p>
<table border=0 cellpadding="3" cellspacing="2">
<form action="admin.php" method=POST name="modify_form">
<input type=hidden name=target value="scheme_manager">
<input type=hidden name=action value="update">
<input type="hidden" name="scheme_id" value="{scheme_id}">
<input type="hidden" name="modified_scheme_id" value="">
	<tr class="TableHead">
    	<td>#</td>
    	<td>Pos.</td>
    	<td>Name</td>
    	<td>Enabled</td>
    	<td colspan=3 align=center>Actions</td>
	</tr>
	<tbody FOREACH="schemes,k,v">
	<INPUT type=hidden name="schemes_list[{v.scheme_id}][scheme_id]" value="{v.scheme_id}">
	<tr class="{getRowClass(k,#TableRow#)}">
    	<td>{v.scheme_id}</td>
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
    	<td IF="isInvariable(v.scheme_id)" align=center>
    	<INPUT type=checkbox checked="{v.enabled}" disabled>
    	</td>
    	<td IF="!isInvariable(v.scheme_id)" align=center>
    	<INPUT type=checkbox name="schemes_list[{v.scheme_id}][enabled]" value="1" checked="{v.enabled}" onClick="this.blur()">
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
    	<td IF="!isReadOnly(v.scheme_id)" align=center>
    	<input type=button value="Delete" onClick="DeleteScheme('{v.scheme_id}','{v.name}')">
    	</td>
	</tr>
	</tbody>
	<tr>
    	<td colspan=7><hr></td>
	</tr>
	<tr>
    	<td colspan=4></td>
    	<td colspan=3>
    	<input type=submit value="Update list">
    	</td>
	</tr>
</form>
</table>

<span IF="currentScheme">
<p>
<font class="AdminTitle">{currentScheme.name}</font><font class="AdminHead"> scheme templates</font>
<hr>
<table border="0">
<form action="admin.php" method=POST name="templates_form">
<input type=hidden name=target value="scheme_manager">
<input type=hidden name=action value="update_templates">
<input type="hidden" name="scheme_id" value="{currentScheme.scheme_id}">
	<tr>
		<td class="FormButton">Subcategories list template</td>
        <td>&nbsp;</td>
        <td IF="isReadOnly(currentScheme.scheme_id)">
        <input type=text name="scat_template" id="scat_template" size=70 value="{currentScheme.scat_template}" readonly>
        </td>
        <td IF="!isReadOnly(currentScheme.scheme_id)">
        <input type=text name="scat_template" id="scat_template" size=70 value="{currentScheme.scat_template}">
        </td>
        <td IF="isReadOnly(currentScheme.scheme_id)">
        <input type=button value="Browse" onClick="SelectTemplate('scat_template')" disabled>
        </td>
        <td IF="!isReadOnly(currentScheme.scheme_id)">
        <input type=button value="Browse" onClick="SelectTemplate('scat_template')">
        </td>
        <td IF="!currentScheme.scat_template=##">
        <input type=button value="Edit" onClick="EditTemplate('scat_template','{currentScheme.scheme_id}')">
        </td>
	</tr>	
	<tr>
		<td class="FormButton">Product list template</td>
        <td>&nbsp;</td>
        <td IF="isReadOnly(currentScheme.scheme_id)">
        <input type=text name="cat_template" id="cat_template" size=70 value="{currentScheme.cat_template}" readonly>
        </td>
        <td IF="!isReadOnly(currentScheme.scheme_id)">
        <input type=text name="cat_template" id="cat_template" size=70 value="{currentScheme.cat_template}">
        </td>
        <td IF="isReadOnly(currentScheme.scheme_id)">
        <input type=button value="Browse" onClick="SelectTemplate('cat_template')" disabled>
        </td>
        <td IF="!isReadOnly(currentScheme.scheme_id)">
        <input type=button value="Browse" onClick="SelectTemplate('cat_template')">
        </td>
        <td IF="!currentScheme.cat_template=##">
        <input type=button value="Edit" onClick="EditTemplate('cat_template','{currentScheme.scheme_id}')">
        </td>
	</tr>	
	<tr>
		<td class="FormButton">Product page template</td>
        <td>&nbsp;</td>
        <td IF="isReadOnly(currentScheme.scheme_id)">
        <input type=text name="prod_template" id="prod_template" size=70 value="{currentScheme.prod_template}" readonly>
        </td>
        <td IF="!isReadOnly(currentScheme.scheme_id)">
        <input type=text name="prod_template" id="prod_template" size=70 value="{currentScheme.prod_template}">
        </td>
        <td IF="isReadOnly(currentScheme.scheme_id)">
        <input type=button value="Browse" onClick="SelectTemplate('prod_template')" disabled>
        </td>
        <td IF="!isReadOnly(currentScheme.scheme_id)">
        <input type=button value="Browse" onClick="SelectTemplate('prod_template')">
        </td>
        <td IF="!currentScheme.prod_template=##">
        <input type=button value="Edit" onClick="EditTemplate('prod_template','{currentScheme.scheme_id}')">
        </td>
	</tr>	
	<tr IF="!isReadOnly(currentScheme.scheme_id)">
        <td colspan=6>
        <input type=submit value="Update">
        </td>
	</tr>	
</form>
</table>
</span>

<script language="Javascript">
<!--

setTimeout("HideActionStatus()", 5000);

-->
</script>
