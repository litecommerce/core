<?php
    $find_str = <<<EOT
<widget class="CButton" label="Continue shopping" href="cart.php" font="FormButton">

<p>
<widget class="CButton" label="Print invoice" href="cart.php?target=order&mode=invoice&order_id={order.order_id}" font="FormButton">

<p>
<widget template="common/dialog.tpl" head="Invoice" body="common/invoice.tpl">
EOT;
    $replace_str = <<<EOT
<widget class="CButton" label="Continue shopping" href="cart.php" font="FormButton">

<p>
<widget class="CButton" label="Print invoice" href="cart.php?target=order&mode=invoice&order_id={order.order_id}" font="FormButton" hrefTarget="_blank">

<p>
<widget template="common/dialog.tpl" head="Invoice" body="common/invoice.tpl">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
