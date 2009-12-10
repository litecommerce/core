<?php

$bodySearch = '<table width="100%" border="0" cellspacing="0" cellpadding="2">';
$bodyReplace =<<<EOT
<table border="0" cellspacing="0" cellpadding="2">
<tr valign="middle">
    <td width=150><img src="images/spacer.gif" width=1 height=1 border=0></td>
    <td width=10><img src="images/spacer.gif" width=1 height=1 border=0></td>
    <td width=150><img src="images/spacer.gif" width=1 height=1 border=0></td>
    <td><img src="images/spacer.gif" width=1 height=1 border=0></td>
</tr>
EOT;

$source = strReplace($bodySearch, $bodyReplace, $source, __FILE__, __LINE__);

$source = strReplace('<td align="right" width="150">E-mail</td>', '<td align="right">E-mail</td>', $source, __FILE__, __LINE__);

$bodySearch =<<<EOT
    <td align="right">Password</td>
    <td>&nbsp;</td>
    <td>
        <input type="password" name="password" value="{password:r}" size="32" maxlength="128">
    </td>
    <td>
		&nbsp;
EOT;
$bodyReplace =<<<EOT
    <td align="right">Password</td>
    <td>{if:mode=#register#}<font class="Star">*</font>{else:}&nbsp;{end:}</td>
    <td>
        <input type="password" name="password" value="{password:r}" size="32" maxlength="128">
    </td>
    <td>
        &nbsp;
        <widget class="CRequiredValidator" field="password" visible="{mode=#register#}">
EOT;

$source = strReplace($bodySearch, $bodyReplace, $source, __FILE__, __LINE__);

$bodySearch =<<<EOT
    <td align="right">Confirm password</td>
    <td>&nbsp;</td>
EOT;
$bodyReplace =<<<EOT
    <td align="right">Confirm password</td>
    <td>{if:mode=#register#}<font class="Star">*</font>{else:}&nbsp;{end:}</td>
EOT;

$source = strReplace($bodySearch, $bodyReplace, $source, __FILE__, __LINE__);

$source = strReplace('<widget class="CPasswordValidator" field="confirm_password" passwordField="password">', '&nbsp;<widget class="CRequiredValidator" field="confirm_password" visible="{mode=#register#}"><widget class="CPasswordValidator" field="confirm_password" passwordField="password" visible="{mode=#register#}">', $source, __FILE__, __LINE__);

$source = strReplace('Are you sure you want to changes access level', 'Are you sure you want to change access level', $source, __FILE__, __LINE__);

?>
