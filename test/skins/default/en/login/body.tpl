<form action="{loginURL}" method="POST" name="login_form">
<input IF="!target=#login#" type="hidden" name="returnUrl" value="{url}"/>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
<td align="center">

<table cellpadding="10" cellspacing="0" border="0">
<tr>
    <td>
        <FONT class="SidebarItems">Email address</FONT><br>
        <input type="text" name="login" value="{login:r}" size="14" maxlength="128"><br>
        <FONT class="SidebarItems">Password</FONT><br>
        <input type="password" name="password" value="" size="14" maxlength="128"><br>
        <input type="hidden" name="target" value="login">
        <input type="hidden" name="action" value="login">
    </td>
</tr>
<tr>
    <td>
		<table cellpadding="0" cellspacing="0" border="0">
		<tr>
		    <td><widget class="XLite_View_Button_Submit" label="Log in" /></td>
		</tr>
		<tr>
			<td><IMG src="images/spacer.gif" width="1" height="5" alt=""></td>
		</tr>
		<tr>
			<td><widget class="XLite_View_Button_Link" location="cart.php?target=profile&mode=register" label="Register" /></td>
		</tr>
		</table>
    </td>
</tr>
</table>
<a href="cart.php?target=recover_password"><u>Recover password</u></a>

</td>
</tr>
</table>
</form>
