<?php

$source = strReplace('<form action="{loginURL}" method="POST" name="login_form">', '<form action="{loginURL}" method="POST" name="login_form">'."\n".'<input IF="!target=#login#" type="hidden" name="returnUrl" value="{url}"/>', $source, __FILE__, __LINE__);

?>
