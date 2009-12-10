<?php

	$find_str = <<<EOT
<tr IF="userExists">
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;The user {login} is already registered! Please choose another e-mail.&nbsp;&lt;&lt;</font></td>
</tr>
EOT;
    $replace_str = <<<EOT
<tr IF="userExists">
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;The user {login} is already registered! Please choose another e-mail.&nbsp;&lt;&lt;</font></td>
</tr>
<tr IF="userAdmin">
    <td colspan="4"><font class="ErrorMessage">&gt;&gt;&nbsp;The user's profile of {login} should be modified in admin back-end!&nbsp;&lt;&lt;</font></td>
</tr>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>