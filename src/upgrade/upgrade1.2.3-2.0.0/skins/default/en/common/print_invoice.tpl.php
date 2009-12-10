<?php

$source = strReplace('<td>{date_format(order,#date#)}</td>', '<td>{time_format(order.date)}</td>', $source, __FILE__, __LINE__);
$source = strReplace('<td>{order_status.display()}</td>', '<td><widget template="common/order_status.tpl"></td>', $source, __FILE__, __LINE__);

$search =<<<EOT
<td  colspan="2" nowrap><b>Billing Info</b><hr></td>
	</tr>
EOT;

$replace =<<<EOT
<td  colspan="2" nowrap><b>Billing Info</b></td>
</tr>
<tr><td colspan="2"><hr width="80%" align=left></td></tr>
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

$search =<<<EOT
<td  colspan="2" nowrap><b>Shipping Info</b><hr></td>
	</tr>
EOT;

$replace =<<<EOT
<td  colspan="2" nowrap><b>Shipping Info</b></td>
</tr>
<tr><td colspan="2"><hr width="80%" align=left></td></tr>
EOT;

$source = strReplace($search, $replace, $source, __FILE__, __LINE__);

$source = strReplace('<table>{product_options.display(item)}</table></td>', '<table><widget module="ProductOptions" template="modules/ProductOptions/invoice_options.tpl"></table></td>', $source, __FILE__, __LINE__);

$source = strReplace('{discountLabel.display()}', '<widget module="Promotion" template="modules/Promotion/print_invoice_discount_label.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{payedByPointsLabel.display()}', '<widget module="Promotion" template="modules/Promotion/print_invoice_label.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{payedByGCLabel.display()}', '<widget module="GiftCertificates" template="modules/GiftCertificates/print_invoice_label.tpl">', $source, __FILE__, __LINE__);

$source = strReplace('{discount.display()}', '<widget module="Promotion" template="modules/Promotion/print_invoice_discount.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{payedByPointsTotal.display()}', '<widget module="Promotion" template="modules/Promotion/print_invoice_total.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('{payedByGC.display()}', '<widget module="GiftCertificates" template="modules/GiftCertificates/print_invoice.tpl">', $source, __FILE__, __LINE__);

?>
