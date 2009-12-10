<widget template="common/dialog.tpl" head="Order processed" body="checkout/success_message.tpl">

<p>
<widget class="CButton" label="Continue shopping" href="cart.php" font="FormButton">

<p>
<widget class="CButton" label="Print invoice" href="cart.php?target=order&mode=invoice&order_id={order.order_id}" font="FormButton">

<p>
<widget template="common/dialog.tpl" head="Invoice" body="common/invoice.tpl">

<widget class="CButton" label="Continue shopping" href="cart.php" font="FormButton">
<p>
