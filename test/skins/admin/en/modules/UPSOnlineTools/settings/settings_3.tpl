<span IF="getMessage()" class="ErrorMessage">{getMessage()}<br/><br/></span>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr valign="top">
    <td>
        <form action="admin.php" method="post" name="reg_frm">
        <input FOREACH="allParams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
        <input type="hidden" name="action" value="next"/>
        <widget template="modules/UPSOnlineTools/settings/register.tpl">
        </form>
        <br/>
        To fill in the form by data from your personal profile click on the 'Fill From Profile' button. 
        <br/><br/>
    </td>
</tr>
<tr>
    <td colspan="2" align="right" nowrap>
		<input type="button" value=" Fill from profile " OnClick="document.location='admin.php?target=ups_online_tool&action=fill_from_profile';">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" value=" Next " class="DialogMainButton" OnClick="document.reg_frm.submit();">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value=" Cancel " OnClick="document.location='admin.php?target=ups_online_tool&action=cancel';">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    </td>
</tr>
</table>
