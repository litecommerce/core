<?php

$source = strReplace('action=invoice', 'mode=invoice', $source, __FILE__, __LINE__);
$source = strReplace('{invoice.display()}', '<widget template="common/invoice.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{print_invoice.display()}', '', $source, __FILE__, __LINE__);

?>
