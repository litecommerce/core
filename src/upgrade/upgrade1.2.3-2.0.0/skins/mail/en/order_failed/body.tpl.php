<?php

$source = "{* E-mail sent to customer when an order failed or was declined by the shop administration *}\n" . $source;
$source = strReplace('{invoice.display()}', '<widget template="common/invoice.tpl">', $source, __FILE__, __LINE__);

?>
