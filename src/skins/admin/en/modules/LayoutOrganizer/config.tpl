<script language="Javascript">
<!--

function UpdateSettings()
{
	document.options_form.submit();
}

-->
</script>

<table border=0 cellspacing=0 width=100%>
<tr>
	<td align="center" width="100"><center><img src="images/modules/LayoutOrganizer/module_logo.gif" border=0></td>
	<td>
	Use this page to configure the "LayoutOrganizer" module settings.<br>
    Click on the "Update" button to save the changes.<br><br>
    Note: The default look&feel of subcategories can be changed in the "<a href="admin.php?target=settings&page=General" onClick="this.blur()"><u>General Settings</u></a>" section.
	</td>
</tr>
</table>

<hr>

<form action="admin.php" name="options_form" method="POST">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="action" value="update">
<input type="hidden" name="page" value="{page}">

<table cellSpacing=2 cellpadding=2 border=0 width="100%">
<tbody FOREACH="options,id,option">
<tr id="option_{option.name}">
    <TD width="50%" align=right>{option.comment:h}: </TD>
    <TD width="50%">
    <widget template="modules/{page}/settings.tpl" option="{option}">
    </TD>
</tr>
</tbody>
<TR><TD colspan=2>&nbsp;</TD></TR>
<TR><TD align="right"><input type="submit" value="Update"></TD>
<TD>&nbsp;</TD></TR>
</table>

</form>

<script language="Javascript">
<!--

function visibleBox(id, status)
{
	var Element = document.getElementById(id);
    if (Element) {
    	Element.style.display = ((status) ? "" : "none");
    }
}

-->
</script>

{if:!config.LayoutOrganizer.template=#modules/LayoutOrganizer/icons.tpl#}
<script language="Javascript">
<!--
visibleBox("option_columns", false);
-->
</script>
{end:}

{if:!config.LayoutOrganizer.template=#modules/LayoutOrganizer/list.tpl#}
<script language="Javascript">
<!--
visibleBox("option_show_description", false);
-->
</script>
{end:}

