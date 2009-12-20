<script language="Javascript">
<!--

function RestoreSettings()
{
	document.options_form.action.value = "restore";
	document.options_form.submit();
}

-->
</script>

<table border=0 cellspacing=0 width=100%>
<tr>
	<td align="center" width="100"><center><img src="images/modules/HTMLCatalog/module_logo.gif" border=0></td>
	<td>
    Use this page to configure the "HTMLCatalog" module settings.<br>Complete the required fields below and press the "Update" button.
	</td>
</tr>
</table>

<form action="admin.php" name="options_form" method="POST">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="action" value="update">
<input type="hidden" name="page" value="{page}">

<table cellspacing=2 cellpadding=2 border=0>
{foreach:options,id,option}
<tr id="option_{option.name}">
    <TD width="90">&nbsp;</TD>
    <TD width="220">{option.comment:h}: </TD>
    <TD>
    <widget template="modules/{page}/settings.tpl" option="{option}" dialog="{dialog}">
    </TD>
</tr>
{end:}
<TR><TD colspan=3>&nbsp;</TD></TR>
<TR>
<TD colspan=3 align="center">
	<table cellspacing=0 cellpadding=0 border=0 width=100%>
    <tr>
    <td align="center" width=100%>
		<input type="submit" value="Update">
    </td>
    <td align="right" width=120 nowrap>
		<a href="javascript: RestoreSettings()" title="Click to restore default module settings" onClick="this.blur()"><img src="images/go.gif" border=0 width=13 align=absmiddle>Restore defaults</a>
    </td>
    </tr>
    </table>
</TD>
</TR>
</table>

</form>

<hr>
<b>(*) You can use following variables in the name format fields:</b>
<table cellspacing=2 cellpadding=2 border=0>
<tr>
	<td>%cid</td>
	<td>&nbsp;-&nbsp;</td>
	<td>category ID</td>
</tr>
<tr>
	<td>%cname</td>
	<td>&nbsp;-&nbsp;</td>
	<td>category name</td>
</tr>
<tr>
	<td>%page</td>
	<td>&nbsp;-&nbsp;</td>
	<td>category page</td>
</tr>
<tr>
	<td>%cpage</td>
	<td>&nbsp;-&nbsp;</td>
	<td>category page substring</td>
</tr>
<tr>
	<td>%pid</td>
	<td>&nbsp;-&nbsp;</td>
	<td>product ID</td>
</tr>
<tr>
	<td>%pname</td>
	<td>&nbsp;-&nbsp;</td>
	<td>product name</td>
</tr>
<tr>
	<td>%psku</td>
	<td>&nbsp;-&nbsp;</td>
	<td>product SKU</td>
</tr>
</table>
