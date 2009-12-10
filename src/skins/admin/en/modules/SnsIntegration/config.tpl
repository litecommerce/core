<table border=0 cellspacing=0 width=100%>
<tr>
	<td align="center" width="100"><center><img src="images/modules/SnsIntegration/module_logo.gif" border=0></td>
	<td>
    Use this page to configure the "SnsIntegration" module settings.<br>Complete the required fields below and press the "Update" button.
    <P><B>Note:</B> If some or all website pages work through HTTPS, specify the HTTPS URL of Sns collector. In this case the site tracker and collector queries will work only through HTTPS, which will eliminate browser security warnings.
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
<tr id="option_{option.name}">
    <TD width="50%">{option.comment:h}: </TD>
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

<p>
Trying to access the SNS collector at <span class="DialogTitle">{config.SnsIntegration.collectorURL}</span> ...
{if:checkSNSConnection()}
<span class="SuccessMessage">SUCCESS</span>
{if:!SNSErrCode=#0#}
<p>
<b>Note:</b> <i>Connection was established, but the <b>passphrase</b> is wrong.</i>
</p>
{end:}
{else:}
<span class="ErrorMessage">FAILED</span>
<p>
<b>Note:</b> <i>Connection cannot be established.</i>
</p>
{end:}
</p>