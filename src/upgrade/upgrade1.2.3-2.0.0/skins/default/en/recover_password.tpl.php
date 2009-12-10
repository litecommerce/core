<?php

$source = strReplace('<input type="hidden" name="target" value="help">', '<input type="hidden" name="target" value="recover_password">', $source, __FILE__, __LINE__);
$source = strReplace('<td width="282" height="10"><input type="text" name="email" size="30"></td>', '<td height="10"><input type="text" name="email" value="{email:r}" size="30"><widget class="CEmailValidator" field="email"></td>', $source, __FILE__, __LINE__);
$source = strReplace('<tr IF="invalid_email">', '<tr IF="noSuchUser">', $source, __FILE__, __LINE__);
$source = strReplace('<td class="ErrorMessage">E-mail address is invalid! Please, correct.</td>', '<td class="ErrorMessage">No such user: {email}</td>', $source, __FILE__, __LINE__);
$source = strReplace('', '', $source, __FILE__, __LINE__);
$source = strReplace('', '', $source, __FILE__, __LINE__);

?>
