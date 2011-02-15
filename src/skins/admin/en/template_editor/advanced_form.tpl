{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<p align="justify">
This section provides the capability to manually modify all templates of the Customer and Administrator Zones and e-mail message templates.
<widget template="template_editor/notes.tpl">
</p>
<p align="justify">
<b>Note:</b> You need to know HTML, CSS and JavaScript in order to modify the templates using this tool. Be careful not to alter the expressions in curly brackets, widgets and other special constructions as this may cause improper software operation.</p>
<form name="templates_form" method="POST">
<input type="hidden" name="target" value="template_editor" />
<input type="hidden" name="editor" value="{editor}" />
<input type="hidden" name="zone" value="{zone}" />
<input type="hidden" name="action" value="" />
<input type="hidden" name="new_name" value="" />
<input type="hidden" name="file" value="{file.path}" />
<input type="hidden" name="node" value="{file.node}" />

<widget class="\XLite\View\Tabber" body="template_editor/advanced_tree.tpl" tabPages="getTreePages" switch="zone">

<script>
var parent_dir = "{node}"

// <!--
function getSelectedNode(){
	buttonGroup = document.templates_form.selected_file
	for (var i = 0; i < buttonGroup.length; i++) {
		if (buttonGroup[i].checked) {
			return buttonGroup[i].value
		}
	}
	return ""
}

function func_removeNode()
{
	node = getSelectedNode()
	if (node!="" && confirm("Are you sure you want to remove the " + node + "?")) {
		document.templates_form.node.value = parent_dir
		document.templates_form.action.value = "remove"
		document.templates_form.submit()
	}
}

function func_renameNode()
{
    node = getSelectedNode();
	if (node!="") {
		new_name = prompt("Enter new name for " + node, "");
		if (new_name != null && new_name != "") {
			document.templates_form.new_name.value = new_name
		    document.templates_form.node.value = parent_dir
			document.templates_form.action.value = "rename"
			document.templates_form.submit()
		}
	}
}

function func_copyNode()
{
	node = getSelectedNode()
	if (node!="") {
		new_name = prompt("Enter copy file name for " + node, "");
		if (new_name != null && new_name != "") {
			document.templates_form.new_name.value = new_name
		    document.templates_form.node.value = parent_dir
			document.templates_form.action.value = "copy"
			document.templates_form.submit()
		}
	}
}

function func_newFile()
{
	new_name = prompt("Create file named: ", "");
	if (new_name != null && new_name != "") {
		document.templates_form.file.value = new_name
		document.templates_form.node.value = parent_dir
		document.templates_form.action.value = "new_file"
		document.templates_form.submit()
	}
}
function func_newDir()
{
	new_name = prompt("Create directory named: ", "");
	if (new_name != null && new_name != "") {
		document.templates_form.file.value = new_name
		document.templates_form.node.value = parent_dir
		document.templates_form.action.value = "new_dir"
		document.templates_form.submit()
	}
}
function func_restoreAll()
{
	if (confirm("All templates from this skin will be restored from the skins_original directory. Continue?")) {
		document.templates_form.action.value = "restore_all"
		document.templates_form.submit()
	}
}
function func_restoreNode()
{
	node = getSelectedNode()
	if (node != "" && confirm("The " + node + " will be restored from the skins_original directory. Continue?")) {
		document.templates_form.action.value = "restore"
		document.templates_form.node.value = parent_dir
		document.templates_form.submit()
	}
}
// -->
</script>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td style="width:50%;">
        <table cellpadding="3" cellspacing="3">
        <tr>
        	<td><widget class="\XLite\View\Button\Regular" label="Copy" jsCode="javascript: func_copyNode()" /></td>
        	<td><widget class="\XLite\View\Button\Regular" label="Rename/Move" jsCode="javascript: func_renameNode()" /></td>
        	<td><widget class="\XLite\View\Button\Regular" label="Remove" jsCode="javascript: func_removeNode()" /></td>
        </tr>
        </table>
	</td>
	<td style="width:50%;">
        <table cellpadding="3" cellspacing="3">
        <tr>
        	<td><widget class="\XLite\View\Button\Regular" label="New File" jsCode="javascript: func_newFile()" /></td>
        	<td><widget class="\XLite\View\Button\Regular" label="New Directory" jsCode="javascript: func_newDir()" /></td>
        </tr>
        </table>
	</td>
</tr>
<tr>
	<td colspan=2><hr /></td>
</tr>
<tr>
	<td colspan=2>
        <table cellpadding="3" cellspacing="3">
        <tr>
        	<td><widget class="\XLite\View\Button\Regular" label="Restore" jsCode="javascript: func_restoreNode()" /></td>
        	<td>&nbsp;&nbsp;&nbsp;</td>
        	<td><widget class="\XLite\View\Button\Regular" label="Restore All" jsCode="javascript: func_restoreAll()" /></td>
        	<td><b>WARNING:</b> ALL CHANGES MADE TO ALL TEMPLATES WILL BE LOST</td>
        </tr>
        </table>
	</td>
</tr>
</table>

</form>
