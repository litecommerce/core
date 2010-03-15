{* Login error page *}
If you already have an account, you can authenticate yourself by filling in the form below. The fields which are marked with <font class="Star">*</font> are mandatory.

<hr size="1" noshade>

<form action="{loginURL}" method=POST name="auth_form">

<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
    <td width="78" class="SidebarItems">E-mail</td>
    <td width="10" class="Star">*</td>
    <td>
        <input type="text" name="login" value="{login:r}" size="30" maxlength="128">
    </td>
</tr>
<tr>
    <td class="SidebarItems">Password</td>
    <td class="Star">*</td>
    <td>
        <input type="password" name="password" value="" size="30" maxlength="128">
    </td>
</tr>
<tr IF="!valid">
    <td colspan="2">&nbsp;</td>
    <td>
    <span class="ValidateErrorMessage">Invalid login or password</span>&nbsp;&nbsp;&nbsp;<a href="cart.php?target=recover_password"><u>Forgot password?</u></a>
    </td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
    <td>
        <input type="hidden" name="target" value="login">
        <input type="hidden" name="action" value="login">
        <input IF="returnUrl" type="hidden" name="returnUrl" value="{returnUrl}"/>
        <widget class="XLite_View_Button_Submit" label="Submit" />
    </td>
</tr>
</table>

<br>
<br>

If you do not have an account, you can easily <a href="cart.php?target=profile&amp;mode=register" class="SidebarItems"><u>register here</u></a>

</form>
