<script language='Javascript'>
<!-- 
	var EFCheckboxes = new Array();

    function disableCategoriesList(elmId)
    {
        var global = document.getElementById("global_"+elmId);
        var select = document.getElementById("selected_"+elmId);
        var categoriesList = document.getElementById("categories_"+elmId);
        if (global.checked) {
            categoriesList.selectedIndex = "-1";
            categoriesList.disabled = true;
        }
        if (select.checked) 
            categoriesList.disabled = false; 

    }

    function deleteWarning() 
    {
    	if (confirm('Are you sure you want to delete extra field(s)?')) { 
			return true;
    	}
    	return false;
    }

    function DeleteFields()
    {
    	if (deleteWarning()) {
        	var delete_field = document.getElementById("delete_field");
        	if (delete_field) {
    			delete_field.value = "delete";
    			document.extra_fields_form.submit();
    		}
    	}
    }

    function setChecked(form, input, check)
    {
        for (var i=0; i < EFCheckboxes.length; i++) {
    		var Element = document.getElementById(EFCheckboxes[i]);
    		if (Element) {
            	Element.checked = check;
            }
        }
    }

    function setHeaderChecked()
    {
    	var Element = document.getElementById("activate_efs");
        if (Element && !Element.checked) {
        	Element.checked = true;
        }
    }
--> 
</script>
Use this section to define additional {if:target=#extra_fields#}global {end:}product detail fields.<hr>

<p align=justify><b>Note: </b>It is strongly recommended that you do not use duplicate names for {if:target=#extra_fields#}global {end:}product extra fields.</p> 

<form IF="extraFields" action="admin.php" method=POST name="extra_fields_form">
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}" />
<input type=hidden name=action value=update_fields>
<input type=hidden name=delete id="delete_field" value="">

<table border=0 cellpadding=3 cellspacing=1>
<tr><td colspan=4 class=AdminHead>{if:target=#extra_fields#}Global{else:}Product{end:} extra fields defined</td></tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr class=TableHead>
    <th valign="top">Pos.</th>
    <th valign="top">Field name</th>
    <th valign="top">Field default value</th>
    <th valign="top">Visible</th>
    <th valign="top" IF="target=#extra_fields#">Categories</th>
    <th valign="top"><input id="activate_efs" type="checkbox" onClick="this.blur();setChecked('extra_fields_form','ef_ids',this.checked);"></th>
</tr>
<tbody FOREACH="extraFields,ef">
<tr>
    <td valign=top><input type=text name="extra_fields[{ef.field_id}][order_by]" value="{ef.order_by}" size=4></td>
    <td valign=top><input type=text name="extra_fields[{ef.field_id}][name]" value="{ef.name:r}" size=32 maxlength=255></td>
    <td valign=top><input type=text name="extra_fields[{ef.field_id}][default_value]" value="{ef.default_value:r}" size=32 maxlength=255></td>
    <td valign=top>
		<select name="extra_fields[{ef.field_id}][enabled]">
        	<option value=1 selected="ef.enabled">Yes</option>
            <option value=0 selected="!ef.enabled">No</option>
        </select>
    </td>
    <td IF="target=#extra_fields#">
        <select id="categories_{ef.field_id}" name="extra_fields[{ef.field_id}][categories][]" multiple size=7>
            {foreach:categories,cat}
            	{if:ef.isCategorySelected(cat.category_id)}
            	<option value="{cat.category_id}" selected>{cat.stringPath:h}</option>
            	{end:}
            {end:}
            {foreach:categories,cat}
            	{if:!ef.isCategorySelected(cat.category_id)}
            	<option value="{cat.category_id}">{cat.stringPath:h}</option>
            	{end:}
            {end:}
        </select><br />
    	Applies to:
		<input id="global_{ef.field_id}" type=radio value=0 name="extra_fields[{ef.field_id}][global]" checked="{ef.isGlobal()}" onClick='javascript: this.blur(); disableCategoriesList({ef.field_id});'>All
        <input id="selected_{ef.field_id}" type=radio value=1 name="extra_fields[{ef.field_id}][global]" checked="{!ef.isGlobal()}" onClick='javascript: this.blur(); disableCategoriesList({ef.field_id});'>Selected
        <br />
        <input id="rewrite_{ef.field_id}" type="checkbox" name="extra_fields[{ef.field_id}][rewrite]" value="yes" checked /> Keep existing values for product extra fields
		<script language='Javascript'>
			disableCategoriesList({ef.field_id});
		</script>
    </td>
    <td align=center valign=top>
    <span IF="target=#product#">
    <span IF="ef.parent_field_id=#0#">
    <input id="ef_ids_{ef.field_id}" type=checkbox name="delete_fields[]" value="{ef.field_id}" onClick="javascript: this.blur();">
    <script language='Javascript'>EFCheckboxes.push("ef_ids_{ef.field_id}");</script>
    </span>
    <span IF="!ef.parent_field_id=#0#">
    <a href="admin.php?target=extra_fields"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Modify</a>
    </span>
    </span>
    <span IF="!target=#product#">
    <input id="ef_ids_{ef.field_id}" type=checkbox name="delete_fields[]" value="{ef.field_id}" onClick="javascript: this.blur();">
    <script language='Javascript'>EFCheckboxes.push("ef_ids_{ef.field_id}");</script>
    </span>
    </td>
</tr>
<tr>
    <td colspan=6><hr></td>
</tr>
</tbody>
<tr>
    <td colspan=3 align=left><input type=submit name=update value="Update" class="DialogMainButton"></td>
    <td colspan=3 align=right><input type=button value="Delete selected" onClick="this.blur();DeleteFields();"></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
</table>
</form>

<br>

<form action="admin.php" method=POST>
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}" />
<input type=hidden name=action value=add_field>

<table border=0 cellpadding=3 cellspacing=1>
<tr>
    <td colspan=3 class=AdminTitle>Add {if:!target=#extra_fields#}product {end:}extra field</td>
</tr>    
<tr><td colspan=3>&nbsp;</td></tr>
<tr IF="target=#extra_fields#">
    <td valign=top>
        Select category(ies):<br>
        <br>
        <i>To (un)select category,<br>ctrl-click it.</i>
    </td> 
    <td>&nbsp;</td>
    <td>
        <select name="add_categories[]" multiple size=7>
            <option FOREACH="getCategories(),cat" value="{cat.category_id}" selected="{isCategorySelected(#add_categories#,cat.category_id)}">{cat.stringPath:h}</option>
        </select>
    </td>
</tr>
<tr>
    <td>Field name:</td>
    <td class=Star width=10>*</td>
    <td>
        <input type=text name=name value="{name:r}" size=32 maxlength=255>
        <widget class="XLite_Validator_RequiredValidator" field="name">
    </td>
</tr>    
<tr>
    <td>Default value:</td>
    <td>&nbsp;</td>
    <td><input type=text name=default_value value="{default_value:r}" size=32 maxlength=255></td>
</tr>    
<tr>
    <td>Pos.:</td>
    <td>&nbsp;</td>
    <td><input type=text name=order_by value="{order_by}" size=4 maxlength=4></td>
</tr>
<tr>
    <td>Visible:</td>
    <td>&nbsp;</td>
    <td>
        <select name=enabled>
            <option value=1 selected="enabled=#1#">Yes</option>
            <option value=0 selected="enabled=#0#">No</option>
        </select>
    </td>
</tr>    
<tr>
    <td colspan=3>
        <input type=submit name=add_field value=" Add ">
    </td>
</tr>
</tr>
</table>
</form>
