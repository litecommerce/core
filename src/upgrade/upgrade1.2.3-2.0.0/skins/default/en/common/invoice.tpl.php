<?php

$source = strReplace('<td>{date_format(order,#date#)}</td>', '<td>{time_format(order.date)}</td>', $source, __FILE__, __LINE__);
$source = strReplace('<td>{order_status.display()}</td>', '<td><widget template="common/order_status.tpl"></td>', $source, __FILE__, __LINE__);
$source = strReplace('{gc.display(item)}', '', $source, __FILE__, __LINE__);
$source = strReplace('{product_options.display(item)}', '<widget module="GiftCertificates" template="modules/GiftCertificates/invoice_item.tpl">'."\n        ".'<widget module="ProductOptions" template="modules/ProductOptions/invoice_options.tpl" visible="{item.hasOptions()}">', $source, __FILE__, __LINE__);
$source = strReplace('<tbody IF="order.show_cc_info">', '<tbody IF="order.showCCInfo&adminMail">', $source, __FILE__, __LINE__);
$source = strReplace('{discount.display()}', '<widget module="Promotion" template="modules/Promotion/invoice_discount.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{payedByPoints.display()}', '<widget module="Promotion" template="modules/Promotion/invoice.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{payedByGC.display()}', '<widget module="GiftCertificates" template="modules/GiftCertificates/invoice.tpl">', $source, __FILE__, __LINE__);

?>
