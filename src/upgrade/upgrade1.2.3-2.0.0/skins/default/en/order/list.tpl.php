<?php

$source = strReplace('{pager.display()}', '<widget class="CPager" data="{orders}" name="pager">', $source, __FILE__, __LINE__);
$source = strReplace('<tr FOREACH="pageData,order">', '<tr FOREACH="pager.pageData,order">', $source, __FILE__, __LINE__);
$source = strReplace('<td>{order_status.display(order)}</td>', '<td><widget template="common/order_status.tpl"></td>', $source, __FILE__, __LINE__);
$source = strReplace('{date_format(order,#date#)}', '{time_format(order.date)}', $source, __FILE__, __LINE__);

?>
