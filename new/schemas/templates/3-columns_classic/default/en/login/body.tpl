<form action="{loginURL}" method="POST" name="login_form">
<input IF="!target=#login#" type="hidden" name="returnUrl" value="{url}"/>
<table cellpadding="2" cellspacing="0" border="0">
<tr>
    <td>
        <FONT class="SidebarItems">E-mail</FONT><br>
        <input type="text" name="login" value="{login:r}" size="20" maxlength="128"><br>
        <FONT class="SidebarItems">Password</FONT><br>
        <input type="password" name="password" value="" size="20" maxlength="128"><br>
        <input type="hidden" name="target" value="login">
        <input type="hidden" name="action" value="login">
    </td>
</tr>
<tr>
    <td>
        <a href="javascript: document.login_form.submit()" class="SidebarItems"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Submit</a><br>
        <a href="cart.php?target=profile&mode=register" class="SidebarItems"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Register</a>
    </td>
</tr>
</table>
</form>

