<?php

$source = strReplace('action=conditions', 'mode=terms_conditions', $source, __FILE__, __LINE__);
$source = strReplace('action=privacy_statement', 'mode=privacy_statement', $source, __FILE__, __LINE__);

?>
