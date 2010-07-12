{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<html>
<head>
    <title>Search users</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta name="ROBOTS" content="NOINDEX">
    <meta name="ROBOTS" content="NOFOLLOW">
    <LINK href="skins/admin/en/style.css"  rel=stylesheet type=text/css>
</head>
<script>
    function showReloaded()
    {
        if(!isNaN(parseInt("{reloaded}"))) {
			window.opener.location = 'admin.php?target={target}&order_id={order_id}&page=order_edit&mode=profile'; 
			window.close();
        };
    }
</script>
<body LEFTMARGIN=0 TOPMARGIN=0 RIGHTMARGIN=0 BOTTOMMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 onLoad="showReloaded();">
<widget template="common/dialog.tpl" head="Search users" body="modules/AOM/common/users_form.tpl">
<br>
<widget class="\XLite\View\Pager" data="{users}" name="pager" itemsPerPage="{config.General.users_per_page}">
<br>
<form name="users_form" action="admin.php" method="POST">
	<input type="hidden" name="target" value="{target}">
	<input type="hidden" name="order_id" value="{order_id}">
	<input type="hidden" name="action" value="fill_user">
<table IF="users&mode=#search_users#" border="0" cellpadding="0" cellspacing="3" width="100%">
<tr class="TableHead">
    <th>&nbsp;</th>
    <th>Login</th>
	<th>Username</th>
</tr>
<tr FOREACH="pager.pageData,profile" class="{getRowClass(#0#,#TableRow#)}">
	<td valign="top"><input type="radio" name="profile_id" value="{profile.profile_id}" checked="{isSelected(id,#0#)}"></td>
    <td valign="top">{profile.login}</td>
    <td valign="top">{profile.billing_firstname:h} {profile.billing_lastname:h}</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td colspan="3"><input type="button" value=" Fill in profile " onClick="users_form.submit();"></td>
</tr>
</table>
</form>
<table IF="mode=#search_users#&!users">
	<tr>
		<td>No users found</td>
	</tr>	
</table>
<br>
</body>
</html>
