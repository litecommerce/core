<script language="Javascript">
<!--
function HideActionStatus()
{
	var id = "action_status";
	var status = false;
	var Element = document.getElementById(id);
    if (Element) {
        Element.style.display = ((status) ? "" : "none");
    }
}
-->
</script>

<table border=0 cellspacing=0 width=100%>
<tr>
	<td align="center" width="100"><center><img src="images/modules/FlyoutCategories/module_logo.gif" border=0></td>
	<td>
    Use this page to configure the "FlyoutCategories" module settings.<br>
    Complete the required fields below and press the "Update" button.
	<br><br>
	<b>Note:</b> You can manage your Flyout Categories template schemes on <a href="admin.php?{flyoutSchemeManagerPageURL:h}"><u>Flyout Categories Manager</u></a> page.
	</td>
</tr>
</table>

<hr>

<span id="action_status">
<font IF="status=#rebuilt#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;Flyout categories layout rebuilt successfully.&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#error#" class="ErrorMessage"><br><br>&gt;&gt;&nbsp;Flyout categories layout rebuilt error.&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#disabled#" class="ErrorMessage"><br><br>&gt;&gt;&nbsp;Flyout categories layout disabled.&nbsp;&lt;&lt;<br><br></font>
</span>

<form action="admin.php" name="options_form" method="POST">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="action" value="update">
<input type="hidden" name="page" value="{page}">
<input type="hidden" name="keyname" value="">

<table cellSpacing=2 cellpadding=2 border=0 width="100%">
<tbody FOREACH="options,id,option">
<tbody IF="!option.orderby=#1000#">
<tr id="option_{option.name}">
    <td width="50%" align=right>{option.comment:h}: </td>
    <td width="50%">
    <widget template="modules/{page}/settings.tpl" option="{option}">
    </td>
</tr>
</tbody>
</tbody>
<tr><td colspan=2>&nbsp;</td></tr>
</table>

<div align="left" IF="IsCategoryOverload()">
<font class="ErrorMessage">Warning: There are more than 300 visible categories in your FlyoutCategories layout. This may dramatically slow down the Customer Zone operation or make the store partially or completely inoperable. Set a lower value in the 'Maximum depth of the tree' field to reduce the number of visible categories.</font>
</div>

<div align="center" IF="!config.FlyoutCategories.scheme">
<input type="submit" value="Update & rebuild layout" OnClick="document.options_form.action.value='save_rebuild';">
</div>

{if:config.FlyoutCategories.scheme}
<hr>
<table border="0" cellpadding="2" cellspacing="2" width="100%">
<tr>
	<td colspan="2" align="left" class="AdminHead">Options for scheme: {flyoutCatScheme.name:h}</td>
</tr>
<tr>
	<td colspan="2" height="8"></td>
</tr>
<tr>
	<td align="right" width="50%">Maximum depth of the tree:</td>
	<td align="left" width="50%">
		<select name="max_depth">
			<option value="1" selected="{isSelected(flyoutCatScheme.max_depth,#1#)}">1 Node</option>
			<option value="2" selected="{isSelected(flyoutCatScheme.max_depth,#2#)}">2 Nodes</option>
			<option value="3" selected="{isSelected(flyoutCatScheme.max_depth,#3#)}">3 Nodes</option>
			<option value="4" selected="{isSelected(flyoutCatScheme.max_depth,#4#)}">4 Nodes</option>
			<option value="5" selected="{isSelected(flyoutCatScheme.max_depth,#5#)}">5 Nodes</option>
			<option value="6" selected="{isSelected(flyoutCatScheme.max_depth,#6#)}">6 Nodes</option>
			<option value="7" selected="{isSelected(flyoutCatScheme.max_depth,#7#)}">7 Nodes</option>
		</select>
	</td>
</tr>

{* User-defined options *}
<tbody IF="flyoutCatScheme.options">
<tr IF="xlite.session.fc_expert_mode">
	<td colspan="2" align="left" class="AdminHead">User-defined options</td>
</tr>
<tr FOREACH="flyoutCatScheme.options,k,val">
	<td align="right" width="50%">{val.description:h}{if:xlite.session.fc_expert_mode} [{k:h}]{end:}:</td>
	<td align="left" width="50%">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td align="left" width="50%">
		{if:val.type=#text_box#}
		<input type="text" name="adv_options[{k}]" value="{val.value}">
		{end:}
		{if:val.type=#select_box#}
		<select name="adv_options[{k}]">
			<option FOREACH="val.points,v" value="{v}" selected="{isSelected(val.value,v)}">{v}</option>
		</select>
		{end:}
		{if:val.type=#check_box#}
		<input type="checkbox" name="adv_options[{k}]" value="1" checked="{isSelected(val.value,#1#)}">
		{end:}
		</td>
		</tr>
		</table>
	</td>
</tr>
</tbody>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="middle"><input type="submit" value="Update & Rebuild layout" OnClick="document.options_form.action.value='save_rebuild';"></td>
</tr>
<tr>
	<td height="32">&nbsp;</td>
</tr>
</table>
{end:}

</form>

<script language="Javascript">
<!--
setTimeout("HideActionStatus()", 5000);
-->
</script>
