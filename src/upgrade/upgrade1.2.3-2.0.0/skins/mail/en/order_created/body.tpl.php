<?php

$source = "{* E-mail sent to customer about initially placed order *}\n" . $source;
$source = strReplace('{invoice.display()}', '<widget template="common/invoice.tpl">', $source, __FILE__, __LINE__);

?>
