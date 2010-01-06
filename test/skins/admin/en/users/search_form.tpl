<p align="justify">
Use this section to search and manage user accounts.
<hr>
</p>

<form action="admin.php" method="GET" name="search_user">
<table>
<tr>
    <td height="10" class="FormButton" nowrap>User info</td>
    <td width="10" height="10"><font class="Star">*</font></td>
    <td><input type="text" name="substring" size="30" value="{substring:r}"></td>
</tr>
<tr>
    <td height="10" colspan=2>&nbsp;</td>
    <td>(substring of user's login, billing or shipping name)</td>
</tr>
<tr>
    <td height="10" class="FormButton" nowrap>Membership</td>
    <td width="10" height="10">&nbsp;</td>
    <td>
        <widget class="XLite_View_MembershipSelect" template="common/select_membership.tpl" field="membership" allOption pendingOption>
    </td>
</tr>
<tr>
    <td height="10" class="FormButton" nowrap>User type</td>
    <td>&nbsp;</td>
    <td>
        <select name="user_type">
            <option value="all" selected="{user_type=#all#}">All</option>
            <option FOREACH="auth.userTypes,user,type" value="{user}" selected="{isSelected(user_type,user)}">{type}</option>
        </select>
    </td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
    <td>
        <input type="hidden" name="target" value="users">
        <input type="hidden" name="mode" value="search">
        <input type="submit" name="search" value="Search" class="DialogMainButton">
        &nbsp;&nbsp;&nbsp;
        <input type="button" name="list" value="List all" onclick="document.search_user.substring.value=''; document.search_user.user_type.value='all'; document.search_user.membership.value='%'; document.search_user.submit();">
    </td>
</tr>
</table>
</form>

<br>
<b>Note:</b> You can also <a href="admin.php?target=profile&mode=register"><u>add a new user</u></a>.
