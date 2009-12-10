<?php
    $find_str = <<<EOT
<p align=justify>You can recover your lost account information using the form below. Please enter valid e-mail address, your account information will be mailed to you shortly.</p>

<form action="cart.php" method=post name="recover_password">
<input type="hidden" name="target" value="recover_password">
EOT;
    $replace_str = <<<EOT
<p align=justify>In order to recover your password, please type in your valid e-mail address you used as a login.<br>Your account information will be e-mailed to you shortly.</p>

<form action="cart.php" method=post name="recover_password">
<input type="hidden" name="target" value="recover_password">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
