<?php
	$find_str = <<<EOT
{* Login error page *}
If you already have an account, you can authenticate yourself by filling in the form below. The fields which are marked with <font color="red">*</font> are mandatory.

<hr size="1" noshade>

EOT;
	$replace_str = <<<EOT
{* Login error page *}
If you already have an account, you can authenticate yourself by filling in the form below. Mandatory fields are marked with an asterisk (<font class="Star">*</font>).

<hr size="1" noshade>

EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
    <td width="78" class="SidebarItems">E-mail</td>
    <td width="10"><font color="red">*</font></td>
    <td>
        <input type="text" name="login" value="{login:r}" size="30" maxlength="128">
    </td>
</tr>
<tr>
    <td class="SidebarItems">Password</td>
    <td><font color="red">*</font></td>
    <td>
        <input type="password" name="password" value="" size="30" maxlength="128">
    </td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
    <td><font color="red">Invalid login or password</font></td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
EOT;
    $replace_str = <<<EOT
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
<tr>
    <td colspan="2">&nbsp;</td>
	<td class="ValidateErrorMessage">Invalid login or password</td>
</tr>
<tr>
     <td colspan="2">&nbsp;</td>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>
