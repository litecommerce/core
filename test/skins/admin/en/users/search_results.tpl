<widget class="XLite_View_Pager" data="{users}" name="searchResults" itemsPerPage="{config.General.users_per_page}">

<script language="JavaScript">
<!--

var SelectedProfileLevel = 0;

function CheckOrders(level)
{
	level = (level == 0) ? false : true;
    document.user_profile.Orders.disabled = level;
}

function DeleteProfile()
{
	if (confirm("Are you sure you want to delete the selected user?")) { 
		document.user_profile.mode.value = "delete"; 
		document.user_profile.submit();
	}
}

// -->
</script>

<form action="admin.php" method="get" name="user_profile">
<table border="0" width="100%">
<tr class="TableHead">
    <td width=10>&nbsp;</td>
    <td nowrap align=left>Login</td>
    <td nowrap align=left>Username</td>
    <td nowrap align=left width=110>First login</td>
    <td nowrap align=left width=110>Last login</td>
</tr>
<tr FOREACH="searchResults.pageData,id,user" class="{getRowClass(id,##,#TableRow#)}">
    <td align="center" width="10"><input type="radio" name="profile_id" value="{user.profile_id}" checked="{isSelected(id,#0#)}" onClick="this.blur();CheckOrders('{user.access_level}')"></td>
    <td nowrap><a href="admin.php?target=profile&profile_id={user.profile_id}&backUrl={url:u}"><u>{user.login:h}</u></a></td>
    <td nowrap><a href="admin.php?target=profile&profile_id={user.profile_id}&backUrl={url:u}">{user.billing_firstname:h}&nbsp;{user.billing_lastname:h}</a></td>
    <td nowrap align=left width=110>{if:user.first_login}{time_format(user.first_login):h}{else:}Never{end:}</td>
    <td nowrap align=left width=110>{if:user.last_login}{time_format(user.last_login):h}{else:}Never{end:}</td>
	<script language="JavaScript" IF="isSelected(id,#0#)">SelectedProfileLevel='{user.access_level}';</script>
</tr>
</table>

<br>
<input type="hidden" name="target" value="profile">
<input type="hidden" name="mode" value="modify">
<input type="hidden" name="backUrl" value="{url:r}">
<p align="left">
<input type="submit" value="Modify" class="DialogMainButton">
&nbsp;&nbsp;
<input type="button" name="Delete" value="Delete" onClick="javascript: DeleteProfile();">
&nbsp;&nbsp;
<input type="button" name="Orders" value="User's order history" onClick="document.user_profile.target.value='users'; document.user_profile.mode.value='orders'; document.user_profile.submit();">
</p>
</form>

<script language="JavaScript">
<!--

CheckOrders(SelectedProfileLevel);

// -->
</script>
