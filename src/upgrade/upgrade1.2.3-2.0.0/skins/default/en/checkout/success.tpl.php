<?php

$source = strReplace('{message.display()}', '<widget template="common/dialog.tpl" head="Order processed" body="checkout/success_message.tpl">', $source, __FILE__, __LINE__);
$source = strReplace('<b><a href="cart.php?target=order&action=invoice&order_id={order.order_id}" target="_blank"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"><font class="FormButton"> Print invoice</font></a></b>&nbsp;&nbsp;&nbsp;<a href="cart.php"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"><font class="FormButton"> Continue shopping</font></a>', '<b><a href="cart.php?target=order&mode=invoice&order_id={order.order_id}" target="_blank"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"><font class="FormButton"> Print invoice</font></a></b>&nbsp;&nbsp;&nbsp;<a href="cart.php"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"><font class="FormButton"> Continue shopping</font></a>', $source, __FILE__, __LINE__);
$source = strReplace('{invoice.display()}', '<widget template="common/dialog.tpl" head="Invoice" body="common/invoice.tpl">', $source, __FILE__, __LINE__);

?>
