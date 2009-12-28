<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr valign="top">
    <td>
<b>UPS OnLine&reg; Tools</b>
{if:haveAccount}
<p>You have already registered with UPS.</p>
<p>To go through the licensing and registration process for UPS OnLine&reg; Tools again, click on the 'Register' button</p>
<p>To configure UPS Rates & Service Selection and Address Validation Tools click on the 'Configure' button</p>
{else:}
<p>You have not registered your UPS OnLine&reg; Tools.<p>
<p>To start the licensing and registration process for UPS OnLine&reg; Tools click on the 'Register' button.</p>
{end:}
    </td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
    <td colspan="2" align="right" nowrap>
    {if:haveAccount}
		<input type="submit" value=" Configure " class="DialogMainButton" OnClick="document.location='admin.php?target=ups_config'">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value=" Register " OnClick="document.location='admin.php?target=ups_online_tool&action=next'">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    {else:}
		<input type="submit" value=" Register " class="DialogMainButton" OnClick="document.location='admin.php?target=ups_online_tool&action=next'">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	{end:}
    </td>
</tr>
</table>
