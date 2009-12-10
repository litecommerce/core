<?php

$source = strReplace('<form action="{shopURL(#cart.php#,config.Security.customer_security):h}" method="post" name="login_form">', '<form action="{loginURL}" method="POST" name="login_form">', $source, __FILE__, __LINE__);
$source = strReplace('action=register', 'mode=register', $source, __FILE__, __LINE__);

?>
