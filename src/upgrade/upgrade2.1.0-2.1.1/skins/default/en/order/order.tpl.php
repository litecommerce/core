<?php
    $source = strReplace(
'<b><a href="cart.php?target=order&mode=invoice&order_id={order.order_id}" target="_blank"><input type="image" src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Print invoice</a></b>',
'<br>
<widget class="CButton" label="Print invoice" href="cart.php?target=order&mode=invoice&order_id={order.order_id}" hrefTarget="_blank">
', $source, __FILE__, __LINE__);
?>
