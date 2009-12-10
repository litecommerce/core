<?php
	$find_str = <<<EOT
{* Login error page *}
If you already have an account, you can authenticate yourself by filling in the form below. The fields which are marked with <font color="red">*</font> are mandatory.
EOT;
    $replace_str = <<<EOT
{* Login error page *}
If you already have an account, you can authenticate yourself by filling in the form below. Mandatory fields are marked with an asterisk (<font class="Star">*</font>).
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
    <td width="78" class="SidebarItems">E-mail</td>
    <td width="10"><font color="red">*</font></td>
EOT;
    $replace_str = <<<EOT
    <td width="78" class="SidebarItems">E-mail</td>
	<td width="10" class="Star">*</td>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
    <td class="SidebarItems">Password</td>
    <td><font color="red">*</font></td>
EOT;
    $replace_str = <<<EOT
    <td class="SidebarItems">Password</td>
	<td class="Star">*</td>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
    <td colspan="2">&nbsp;</td>
    <td><font color="red">Invalid login or password</font></td>
EOT;
    $replace_str = <<<EOT
    <td colspan="2">&nbsp;</td>
	<td class="ValidateErrorMessage">Invalid login or password</td>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
        <input type="hidden" name="action" value="login">
        <input IF="returnUrl" type="hidden" name="returnUrl" value="{returnUrl:h}"/>
EOT;
    $replace_str = <<<EOT
        <input type="hidden" name="action" value="login">
        <input IF="returnUrl" type="hidden" name="returnUrl" value="{returnUrl}"/>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
