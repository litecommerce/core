<table border=0 cellspacing=0 width=100%>
<tr>
	<td align="center" width="100"><center><img src="images/modules/Promotion/module_logo.gif" border=0></td>
	<td>
    Use this page to configure the "Promotion" module settings.<br>Complete the required fields below and press the "Update" button.
	</td>
</tr>
</table>

<hr>

<form action="admin.php" name="options_form" method="POST">
<input type="hidden" name="target" value="{target}">
<input type="hidden" name="action" value="update">
<input type="hidden" name="page" value="{page}">

<table cellSpacing=2 cellpadding=2 border=0 align=center>
<tbody FOREACH="options,id,option">
{if:option.name=#offerScheme#}
<TR><TD colspan=2><hr></TD></TR>
<tr id="option_{option.name}">
    <TD width="50%">{option.comment:h}: </TD>
    <TD width="50%">
    <widget template="modules/{page}/settings.tpl" option="{option}">
    </TD>
</tr>
<TR><TD colspan=2>
&nbsp;&nbsp;&nbsp;&#9679;&nbsp;Select this check box to combine special offers if multiple offers apply to an order.<br>
&nbsp;&nbsp;&nbsp;&#9679;&nbsp;Leave the check box empty to apply only the most favorable special offer.<br>
</TD></TR>
{else:}
<tr id="option_{option.name}">
    <TD width="50%">{option.comment:h}: </TD>
    <TD width="50%">
    <widget template="modules/{page}/settings.tpl" option="{option}">
    </TD>
</tr>
{end:}
</tbody>
<TR><TD colspan=2>&nbsp;</TD></TR>
<TR><TD align="right"><input type="submit" value="Update"></TD>
<TD>&nbsp;</TD></TR>
</table>

</form>
