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
<form action="{loginURL}" method="POST" name="login_form">

<table cellpadding="2" cellspacing="0" border="0">
<tr>
    <td>
        <FONT class="SidebarItems">E-mail</FONT><br>
        <input type="text" name="login" value="{login:r}" size="20" maxlength="128"><br>
        <FONT class="SidebarItems">Password</FONT><br>
        <input type="password" name="password" value="" size="20" maxlength="128"><br>
        <input type="hidden" name="target" value="partner_login">
        <input type="hidden" name="action" value="login">
    </td>
</tr>
<tr>
    <td>
        <a href="javascript: document.login_form.submit()" class="SidebarItems"><input type="image" src="images/go.gif" style="width: 13px; height: 13px; margin: 0px;" alt="" align="left"> Submit</a>
    </td>
</tr>
<tr>
    <td>
        <a IF="config.CDev.Affiliate.registration_enabled" href="cart.php?target=partner_profile&amp;mode=register" class="SidebarItems"><img src="images/go.gif" width="13" height="13" border="0" alt="" align="left">Join now!</a>
    </td>
</tr>
</table>
</form>
