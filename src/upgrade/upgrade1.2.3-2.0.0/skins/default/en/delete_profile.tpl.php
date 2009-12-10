<?php

$source = strReplace('<tr IF="warning">', '<tr IF="submode=#warning#">', $source, __FILE__, __LINE__);
$source = strReplace('&mode=confirmed', '', $source, __FILE__, __LINE__);
$source = strReplace('action=delete&mode=cancelled', 'mode=delete&submode=cancelled', $source, __FILE__, __LINE__);
$source = strReplace('<tr IF="confirmed">', '<tr IF="submode=#confirmed#">', $source, __FILE__, __LINE__);
$source = strReplace('<tr IF="cancelled">', '<tr IF="submode=#cancelled#">', $source, __FILE__, __LINE__);

?>
