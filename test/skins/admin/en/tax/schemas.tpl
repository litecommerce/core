{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
Use this section to manage tax scheme definitions, save or reset your current tax scheme.
<hr>
<style>
    .adminParagraph {
		text-align  : justify;
	}
</style>
<p class=AdminHead>
	Reset/delete tax scheme
</p>
<p class="adminParagraph">
To reset your tax settings to one of existing tax schemes choose a tax
scheme from the drop-down menu and click on the 'Reset' button next to it.
</p>
<p class="adminParagraph">
<b>Note:</b> It is not guaranteed that the predefined tax schemes provided in LiteCommerce distribution will conform with your local laws. To make sure that your taxation settings are configured properly, please refer to your local tax laws.
</p>
<p class="adminParagraph">
To delete a tax scheme, choose it from the drop-down menu and click on the 'Delete scheme' button.
</p>
<p class="adminParagraph">
<b>Note:</b> Several predefined schemes, namely {foreach:taxes._predefinedSchemas,schema,options}&quot;{schema}&quot;, {end:} cannot be deleted.
</p>
<form action="admin.php" method="POST" name="reset_schema">
<input type="hidden" name="target" value="taxes">
<input type="hidden" name="page" value="{page}">
<input type="hidden" name="action" value="reset">
<select name="schema">
<option>-- select scheme --</option>
<option FOREACH="taxes.predefinedSchemas,schema,options">{schema}</option>
</select>
<input type="button" value=" Reset " onclick="resetSchema()" class="DialogMainButton">
&nbsp;
<input type=button value=" Delete scheme " onClick="deleteSchema()">
<script language=JavaScript>
// <!--
function resetSchema()
{
	var schemaIndex = document.reset_schema.schema.selectedIndex;
	if (schemaIndex <= 0) {
		alert("Please select a predefined scheme");
	} else if (confirm("You are about to reset your current tax scheme. Are you sure you want to proceed?")) {
		 document.reset_schema.submit();
	}
}

function deleteSchema()
{
	var text = "Are you sure you want do delete '" + document.reset_schema.schema.options[document.reset_schema.schema.selectedIndex].text  +  "' scheme?";
    with (document.reset_schema) {
        if (schema.selectedIndex <= 0) {
            alert("Please select a predefined scheme");
        } else if (confirm(text)) {
            action.value = "delete_schema";
            submit();
        }
    }
}
// -->
</script>
</form>
<br><br>
<p class=AdminHead>Save the current tax scheme</p>
<p class="adminParagraph"><b>Note:</b> To save your current tax scheme choose the scheme name from the list or the ' - new name - ' option to save the scheme under a new name.</p>
<form action="admin.php" method=POST name=save_schema_form>
<input type="hidden" name="target" value="taxes">
<input type="hidden" name="page" value="{page}">
<input type="hidden" name="action" value="save">
<input type="hidden" name="new_name" value="">
<select name="save_schema">
<option value="">-- new name --</option>
<option FOREACH="schemas,schema,options" value="{schema}">{schema}</option>
</select>
<input type="button" value=" Save " onclick="saveSchema()" class="DialogMainButton">
<script language=JavaScript>
// <!--
function saveSchema()
{
	var schemaIndex = document.save_schema_form.save_schema.selectedIndex;
    var schema = document.save_schema_form.save_schema.value;
	if (schemaIndex <= 0) {
        name = prompt("Enter scheme name: ");
        if (name == "" || name == null) return;
        document.save_schema_form.new_name.value = name;
	} else if (!confirm("Are you sure you want to overwrite existing tax scheme '" + schema + "'?")) {
        return;
    }
    document.save_schema_form.submit();
}
// -->
</script>
</form>
<span IF="schemas">
<br><br>
<p class=AdminHead>Export tax scheme</p>
<p class="adminParagraph"> 
	<b>Note:</b> To export a scheme, choose it from the drop-down list and click on the 'Export' button. To have your current tax scheme exported, it needs to be saved first.
</p>

<form action="admin.php" method=POST name=export_schema_form>
<input type="hidden" name="target" value="taxes">
<input type="hidden" name="page" value="{page}">
<input type="hidden" name="action" value="export">

<select name="export_schema">
<option FOREACH="schemas,schema,options" value="{schema}">{schema}</option>
</select>
<input type="submit" value=" Export " class="DialogMainButton">

</form>
</span>

<br><br>
<p class=AdminHead>Import tax scheme</p>
<p class="">
<b>Note:</b> Locate the tax scheme file (*.tax) you want to import and click on the 'Import' button.
</p>
<form action="admin.php" method=POST enctype="multipart/form-data">
<input type="hidden" name="target" value="taxes">
<input type="hidden" name="page" value="{page}">
<input type="hidden" name="action" value="import">
<input type=file name=userfile><widget IF="invalid_file" template="common/uploaded_file_validator.tpl" />&nbsp;
<input type="submit" value=" Import " class="DialogMainButton">
</form>

<script IF="invalid_file" language="javascript">
setTimeout("window.scroll(0, 100000);", 500);
</script>
