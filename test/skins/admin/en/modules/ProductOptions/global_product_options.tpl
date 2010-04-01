<script language='Javascript'>
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
    function updateSize(option_id) {
		OptType = document.getElementById("opttype_" + option_id);
		Textarea = document.getElementById("TextareaTR_" + option_id);
        Text = document.getElementById("TextTR_" + option_id);
	    Textarea1 = document.getElementById("TextareaTR1_" + option_id);
	    Text1 = document.getElementById("TextTR1_" + option_id);
	    if (OptType.value == "Text") {
		    Textarea.style.display="none";
		    Text.style.display="";
		    Textarea1.style.display="none";
		    Text1.style.display="";
		} else if (OptType.value == "Textarea") {
		    Textarea.style.display="";
	        Text.style.display="none";
            Textarea1.style.display="";
            Text1.style.display="none";
        } else {
            Textarea.style.display="none";
            Text.style.display="none";
            Textarea1.style.display="none";
            Text1.style.display="none";
        }
	}
</script>

<widget module="ProductOptions" template="modules/ProductOptions/option_form_js.tpl">

<p>This page allows you to add product options to all products within a specified category(-ies) at once, make global modifications to existing product options and remove product options from entire categories.</p>

<p><b>Hint:</b> Option classes for individual products can be defined in the 'Product options' tab of the respective 'Product info' pages.</p>
<hr>
<!-- form begin --> 
<table border="0" cellpadding="0" cellspacing="3">
<tbody FOREACH="globalOptions,idx,option">
<form IF="globalOptions" action="admin.php" method=POST name="options_form_{idx}">
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}" />
<input type="hidden" name="action" value="update_product_option">
<input type="hidden" name="option_id" value="{option.option_id}">
<tr>
	<td colspan=2 class="TableHead">
		<table border=0 cellpadding=0>
		<tr>
            <td><a name="section_{option.option_id}"></a>Option class name:&nbsp;</td>
            <td>
            <input type=text name="global_options[optclass]" value="{option.optclass:r}" size=12>
            <widget class="XLite_Module_ProductOptions_Validator_RequiredValidator" field="global_options[optclass]" action="update_product_option" option_id="{option.option_id}">
			&nbsp;<span style="color: #606060">should be unique for easier stock management</span>
            </td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td valign=top>
		<table border=0 cellpadding=1 cellspacing=3 valign=top>
        <tr>
            <td class="TableRow">Option values</td>
            <td class="TableRow">Option selection text</td>
            <td class="TableRow">Pos.</td>
        </tr>
        <tr>
            <td rowspan="6" valign=top>
            	<textarea cols=34 rows=11 name="global_options[options]">{option.options:r}</textarea>
            </td>
            <td valign=top>
                <input type=text name="global_options[opttext]" value="{option.opttext:r}" size=34>
            </td>
            <td valign=top>
        		<input type=text name="global_options[orderby]" value="{option.orderby}" size=3>
            </td>
        </tr>
        <tr height=20>
        	<td class="TableRow">
				Option selector
        	</td>
        	<td>&nbsp;</td>
        </tr>
        <tr>
        	<td>
                <select name="global_options[opttype]" id="opttype_{idx}" onChange="javascript: editSize(this.value, '_{idx}');">
                    <option value="Text" selected="option.opttype=#Text#">Text</option>
                    <option value="Textarea" selected="option.opttype=#Textarea#">Text Area</option>
                    <option value="SelectBox" selected="option.opttype=#SelectBox#">Select Box</option>
                    <option value="Radio button" selected="option.opttype=#Radio button#">Radio Button</option>
                </select>
			</td>
        	<td>&nbsp;</td>
        </tr>
        <tr height=20>
        	<td class="TableRow" id="TextTRHead_{idx}" style="display: none">Size (symbols)</td>
        	<td class="TableRow" id="TextareaTRHead_{idx}" style="display: none">Size (cols, rows)</td>
        	<td>&nbsp;</td>
        </tr>
        <tr>
        	<td>
				<table border=0 cellpadding=0 cellspacing=0>
        		<tr>
                	<td id="TextTR_{idx}" style="display: none">
                		<input type=text name="global_options[cols]" size=3 value="{option.cols}">
                	</td>
        			<td>&nbsp;&nbsp;&nbsp;</td>
                    <td id="TextareaTR_{idx}" style="display: none">
                        <input type=text name="global_options[rows]" size=3 value="{option.rows}">
                    </td>
        			<script language="Javascript">initEditSize("_{idx}");</script>
        		</tr>
				</table>
        	<td>&nbsp;</td>
        </tr>
        <tr height=25>
        	<td colspan=2>&nbsp;</td>
        </tr>
		</table>
	</td>
	<td>
		<table border=0 cellpadding=1 cellspacing=3>
        <tr>
            <td class="TableRow">Categories</th>
        </tr>
        <tr>
        	<td>
        		<input id="global_{option.option_id}" type=radio value=1 name="global_options[global_categories]" checked="{option.isGlobal()}" onClick='javascript: this.blur(); disableCategoriesList({option.option_id});'>for All
                <input id="selected_{option.option_id}" type=radio value=0 name="global_options[global_categories]" checked="{!option.isGlobal()}" onClick='javascript: this.blur(); disableCategoriesList({option.option_id});'>for Selected
        		<br> 
        		<select id='categories_{option.option_id}' name="global_options[categories][]" multiple size=10>
                {foreach:categories,cat}
                	{if:option.isCategorySelected(cat.category_id)}
                	<option value="{cat.category_id}" selected>{cat.stringPath:h}</option>
                	{end:}
                {end:}
                {foreach:categories,cat}
                	{if:!option.isCategorySelected(cat.category_id)}
                	<option value="{cat.category_id}">{cat.stringPath:h}</option>
                	{end:}
                {end:}
        		</select>
                <script language='Javascript'>
                    disableCategoriesList({option.option_id});
                </script>
        	</td>
        </tr>
		</table>
	</td>
</tr>
<tr>
    <td><input type=submit name=update value="Update"></td>
    <script language="Javascript">
    function delete_option_{idx}() 
    {
		document.options_form_{idx}.action.value='delete'; 
		document.options_form_{idx}.submit();
    }
    </script>
    <td align=right><input type="button" name="delete" value="Delete" onClick="if (delete_warning('{addSlashes(option.optclass):r}')) delete_option_{idx}();"></td>
</tr>
<tr>
    <td colspan=2><hr></td>
</tr>
</form>
</tbody>
</table>

<script language="Javascript">
function delete_warning(name) 
{
	    if (confirm('You are about to delete \''+name+'\' global product option.\n\nAre you sure you want to delete it?')) { 
			        return true;
	    }
	    return false;
}
</script>

<p><font class="AdminTitle">Add global product option class</font>

<form action="admin.php" method="POST" name=add_option_form>
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<input type="hidden" name="action" value="add">
<input type=hidden name="opttype" value="">

<p><b>Select categories:</b><br>
<select name="categories[]" multiple size="10">
    <option FOREACH="categories,cat" value="{cat.category_id:r}">{cat.stringPath:h}</option>
</select>


<p>

<table border=0 cellpadding=0>

<tr>
    <td colspan=4>
    <table border=0>

    <widget module="ProductOptions" template="modules/ProductOptions/option_form.tpl" action="add">

    <tr><td colspan=2>&nbsp;</td></tr>
    <tr>
        <td colspan=2>
        <input type="submit" name="add" value=" Add to selected ">
        </td>
    </tr>
    </table>
    </td>
</tr>
</table>
</form>

<script language="Javascript" IF="option_id">
document.location = "#section_" + {option_id}; 
</script>
