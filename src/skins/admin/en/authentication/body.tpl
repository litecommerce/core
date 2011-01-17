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
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr> 
    <td>&nbsp;&nbsp;&nbsp;</td>
    <td class="VertMenuItems" valign="top">
        {auth.profile.login}<br>
        Logged in!<br>
        <br>
        <a href="admin.php?target=login&action=logoff" class="SidebarItems"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Logoff</a>
        <br>
        <br>
    </td>
</tr>
<tr IF="recentAdmins"><td colspan=2>Logins history:<br><br></td></tr>
<tbody FOREACH="recentAdmins,recentAdmin">
<tr><td align=left colspan=2>{formatTime(recentAdmin,#last_login#)}</td></tr>
<tr><td align=right colspan=2>{recentAdmin.login}<br><br></td></tr>
</tbody>
</table>
