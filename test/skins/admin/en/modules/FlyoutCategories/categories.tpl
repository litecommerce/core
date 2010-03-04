{if:config.FlyoutCategories.scheme&category_id=#0#}

<script type="text/javascript" language="JavaScript">
<!--
function fc_actions_update()
{
	if (!document.flyout_actions_form.auto_generate.checked &&
		!document.flyout_actions_form.rebuild_categories.checked)
	{
		alert("Please select an action to perform.");
		return;
	}

	document.flyout_actions_form.submit();
}
-->
</script>

<form name="flyout_actions_form" method="POST" action="admin.php">
<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="action" value="fc_categories_actions">

<br>
<table border=0 cellspacing=0 cellpadding=2 width="100%">
	<tr>
		<td colspan=2><widget template="modules/FlyoutCategories/separator.tpl" caption="FlyoutCategories actions"></td>
	</tr>
	<tr>
		<td width="5%"><input type=checkbox name="auto_generate" value=1 {if:!fCatGDlibEnabled}disabled{end:} onclick="this.blur();"></td>
		<td>Automatically generate missing small category icons{if:!fCatGDlibEnabled}<br><font class=ErrorMessage>GDlib is disabled or its version is lower than 2.0.</font>{end:}</td>
	</tr>
	<tr>
		<td width="5%"><input type=checkbox name="rebuild_categories" value=1 checked onclick="this.blur();"></td>
		<td>Rebuild category menu layout</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td colspan=2><input type=button value="Update" onclick="fc_actions_update();"></td>
	</tr>
</table>

</form>

{end:}
