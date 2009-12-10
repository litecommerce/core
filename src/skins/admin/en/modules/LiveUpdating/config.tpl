<table border=0 cellspacing=0 width=100%>
<tr>
	<td align="center" width="100"><center><img src="images/modules/LiveUpdating/module_logo.gif" border=0></td>
	<td>
	Use this page to configure the "LiveUpdating" module settings.<br>Complete the required fields below and click on the "Update" button.
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
<tbody IF="!option.type=#serialized#">
<tr IF="!option.orderby=#1000#" id="option_{option.name}">
    <TD width="50%" align=right>{option.comment:h}: </TD>
    <TD width="50%">
    <widget template="modules/{page}/settings.tpl" option="{option}" dialog="{dialog}">
    </TD>
</tr>
</tbody>
</tbody>
<tr IF="config.LiveUpdating.use_ftp">
    <TD width="50%" align=right>Trying to access the shop via FTP: </TD>
    <TD width="50%">
    <B>
    <FONT color=red IF="checkFTP()=#100#">Failure (PHP doesn't support FTP functions)</FONT>
    <FONT color=red IF="checkFTP()=#1#">Failure (cannot connect to FTP host)</FONT>
    <FONT color=red IF="checkFTP()=#2#">Failure (cannot login to FTP host)</FONT>
    <FONT color=red IF="checkFTP()=#3#">Failure (cannot change dir)</FONT>
    <FONT color=red IF="checkFTP()=#4#">Failure (cannot	upload test file)</FONT>
    <FONT color=red IF="checkFTP()=#5#">Failure (cannot	change test file' permissions)</FONT>
    <FONT color=red IF="checkFTP()=#6#">Failure (cannot	delete test file)</FONT>
    <FONT color=green IF="checkFTP()=#0#">Success</FONT>
    </B>
    </TD>
</tr>
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

{if:!config.LiveUpdating.use_ftp}
<script language="Javascript">
<!--
visibleBox("option_ftp_host", false);
visibleBox("option_ftp_port", false);
visibleBox("option_ftp_login", false);
visibleBox("option_ftp_password", false);
visibleBox("option_ftp_dir", false);
visibleBox("option_ftp_passive", false);
-->
</script>
{end:}

{if:config.LiveUpdating.use_ftp&checkFTP()=#100#}
<script language="Javascript">
<!--
document.options_form.use_ftp.checked = false;
-->
</script>
{end:}
