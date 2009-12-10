<?php

$source = strReplace(";templateDir = templates\n", '', $source, __FILE__, __LINE__);
$source = strReplace(';compileDir = compiled_templates', 'compileDir = "var/run/"', $source, __FILE__, __LINE__);
$source = strReplace("method = evaluate\n", '', $source, __FILE__, __LINE__);
$source = strReplace("; method = file\n", '', $source, __FILE__, __LINE__);
$source = strReplace("forceCompile = On\n", '', $source, __FILE__, __LINE__);
$source = strReplace('compileDir = "var/run"', 'compileDir = "var/run/classes/"', $source, __FILE__, __LINE__);

?>
