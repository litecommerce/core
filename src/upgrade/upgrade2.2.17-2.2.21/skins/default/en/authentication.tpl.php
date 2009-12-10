<?php

	$find_str = "<td width=\"10\"><font class=\"Star\">*</font></td>";
    $replace_str = "<td width=\"10\" class=\"Star\">*</td>";
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = "<td><font class=\"Star\">*</font></td>";
    $replace_str = "<td class=\"Star\">*</td>";
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
    <td>
    <font color="red">Invalid login or password</font>&nbsp;&nbsp;&nbsp;<a href="cart.php?target=recover_password"><u>Forgot password?</u></a>
    </td>
EOT;
    $replace_str = <<<EOT
    <td>
    <span class="ValidateErrorMessage">Invalid login or password</span>&nbsp;&nbsp;&nbsp;<a href="cart.php?target=recover_password"><u>Forgot password?</u></a>
    </td>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>