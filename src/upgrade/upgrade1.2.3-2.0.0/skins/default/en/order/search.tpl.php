<?php

$source = strReplace('{search_form.display()}', '<widget template="common/dialog.tpl" body="order/search_form.tpl" head="Search orders">', $source, __FILE__, __LINE__);
$source = strReplace('<span class="Text" IF="found">', '<span class="Text" IF="mode=#search#&count">', $source, __FILE__, __LINE__);
$source = strReplace('{order_list.display()}', '<widget template="common/dialog.tpl" mode="search" body="order/list.tpl" head="Search result">', $source, __FILE__, __LINE__);
$source = strReplace('<span class="Text" IF="not_found">No orders found</span>', '<span class="Text" IF="mode=#search#&!count">No orders found</span>', $source, __FILE__, __LINE__);

?>
