<?php

$source = strReplace('<input type="text" name="name" size="45" value="">', '<input type="text" name="name" size="45" value="">' . "\n" . '<widget class="CRequiredValidator" field="name">', $source, __FILE__, __LINE__);
$source = strReplace('<i>You can specify tax classes in Settings/Taxes/add rate/condition dialog</i></td>', '<i>You can specify tax classes in<br>Settings/Taxes/add rate/condition dialog</i></td>', $source, __FILE__, __LINE__);
$source = strReplace('{*extraFields*}', '{*extraFields*}' . "\n" . '<widget class="CExtraFields">', $source, __FILE__, __LINE__);

?>
