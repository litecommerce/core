<?php

$source = strReplace('{*extraFields*}', '{*extraFields*}' . "\n" . '<widget class="CExtraFields">', $source, __FILE__, __LINE__);
$source = strReplace('<input type="submit" value=" Update ">', '<input type="submit" value=" Update ">&nbsp;<input type="button" value=" Clone " onClick="document.modify_form.action.value=\'clone\'; document.modify_form.submit();">', $source, __FILE__, __LINE__);

?>
