<?php

$source = strReplace('action=modify', 'mode=modify', $source, __FILE__, __LINE__);
$source = strReplace('&action=delete', 'mode=delete', $source, __FILE__, __LINE__);

?>
