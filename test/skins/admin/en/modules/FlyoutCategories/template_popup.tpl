<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <LINK href="skins/admin/en/style.css"  rel=stylesheet type=text/css>
	<TITLE IF="!mode=#edit#">Template Selector</TITLE>
	<TITLE IF="mode=#edit#">Template Editor</TITLE>
</head>
<body class="PopUp" LEFTMARGIN=3 TOPMARGIN=3 RIGHTMARGIN=3 BOTTOMMARGIN=3 MARGINWIDTH=0 MARGINHEIGHT=0>

<span IF="!mode=#edit#">

<form name="templates_form" method="POST">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="editor" value="{editor}">
<input type="hidden" name="zone" value="{zone}">
<input type="hidden" name="action" value="">
<input type="hidden" name="new_name" value="">
<input type="hidden" name="file" value="{file.path}">
<input type="hidden" name="node" value="{file.node}">

Templates directory: <B>{node}</B>
<hr>

<widget class="XLite_View_FileExplorer" formSelectionName="selected_file" columnCount=2 modifier="zone">

<script>
var parent_dir = "{node}"
var formField = '{formField}';
var formName = '{formName}';

// <!--
function getSelectedNode(){
	buttonGroup = document.templates_form.selected_file;
	for (var i = 0; i < buttonGroup.length; i++) {
		if (buttonGroup[i].checked) {
			return buttonGroup[i].value;
		}
	}
	return ""
}

function removeNode()
{
	node = getSelectedNode();
	if (node!="" && confirm("Are you sure you want to remove the " + node + "?")) {
		document.templates_form.node.value = parent_dir;
		document.templates_form.action.value = "remove";
		document.templates_form.submit();
	} else {
		alert ("Please select template!");
	}
}

function renameNode()
{
    node = getSelectedNode();
	if (node!="") {
		new_name = prompt("Enter new name for " + node, "");
		if (new_name != null && new_name != "") {
			document.templates_form.new_name.value = new_name;
		    document.templates_form.node.value = parent_dir;
			document.templates_form.action.value = "rename";
			document.templates_form.submit();
		}
	} else {
		alert ("Please select template!");
	}
}

function copyNode()
{
	node = getSelectedNode();
	if (node!="") {
		new_name = prompt("Enter copy file name for " + node, "");
		if (new_name != null && new_name != "") {
			document.templates_form.new_name.value = new_name;
		    document.templates_form.node.value = parent_dir;
			document.templates_form.action.value = "copy";
			document.templates_form.submit();
		}
	} else {
		alert ("Please select template!");
	}
}

function newFile()
{
	new_name = prompt("Create file named: ", "");
	if (new_name != null && new_name != "") {
		document.templates_form.file.value = new_name;
		document.templates_form.node.value = parent_dir;
		document.templates_form.action.value = "new_file";
		document.templates_form.submit();
	}
}

function newDir()
{
	new_name = prompt("Create directory named: ", "");
	if (new_name != null && new_name != "") {
		document.templates_form.file.value = new_name;
		document.templates_form.node.value = parent_dir;
		document.templates_form.action.value = "new_dir";
		document.templates_form.submit();
	}
}

function SelectTemplate()
{
	node = getSelectedNode()
	if (node!="") {
    	eval("window.opener.document." + formName + "." + formField + ".value='" + node +"';");
    	window.opener.focus();
    	window.close();
	} else {
		alert ("Please select template!");
	}
}

function Cancel()
{
	window.opener.focus();
	window.close();
}

// -->
</script>

<HR>

<input type=button value="Select" onClick="SelectTemplate()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=button value="Cancel" onClick="Cancel()">

<HR>

<a href="javascript: newFile()" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> New File</a>&nbsp;&nbsp;
<a href="javascript: newDir()" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> New Directory</a>&nbsp;&nbsp;
<a href="javascript: copyNode()" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Copy</a>&nbsp;&nbsp;
<a href="javascript: renameNode()" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Rename/Move</a>&nbsp;&nbsp;
<a href="javascript: removeNode()" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Remove Selected</a>&nbsp;&nbsp;

</form>

</span>

<span IF="mode=#edit#">
<form action="admin.php" method="POST" name="editor_form">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="action" value="advanced_update">
<input type="hidden" name="editor" value="advanced">
<input type="hidden" name="zone" value="{zone}">
<input type="hidden" name="file" value="{file.path}">
<input type="hidden" name="node" value="{file.node}">
<input type="hidden" name="formName" value="{formName}">
<input type="hidden" name="formField" value="{formField}">
File: <b>{file.path}</b><br>
<textarea name="content" cols="120" rows="40">{file.content}</textarea>
<p>
<input type="submit" value=" Save "> <input type="button" value=" Cancel " onclick="document.location='{dialog.url}&node={file.node:u}'">
</form>
</span>

</body>

