<?php
    $find_str = <<<EOT
<form action="cart.php" method="post" name="payment_method">
<table border="0" align="center">
<tr FOREACH="paymentMethods,payment_method">
    <td width="5%"><input type="radio" name="payment_id" value="{payment_method.payment_method}"></td>
    <td nowrap><b>{payment_method.name:h}</b></td>
    <td nowrap>{payment_method.details:h}</td>
</tr>
EOT;
    $replace_str = <<<EOT
<script language="JavaScript">
function continueCheckout()
{
	if (!document.payment_method.payment_id) {
		alert("No payment methods are available! Please contact the store administrator.");
		return false;
	}

	pass = false;
	if (document.payment_method.payment_id.length && document.payment_method.payment_id.length > 0) {
		for (i = 0; i < document.payment_method.payment_id.length; i++) {
			if (document.payment_method.payment_id[i].checked == true) {
				pass = true;
				break;
			}
		}

	} else {
		pass = document.payment_method.payment_id.checked;
	}

	if (pass == false) {
		alert("To proceed with checkout, you need to select a payment method.");
		return false;
	}

	document.payment_method.submit();
}
</script>

<span class="ErrorMessage" IF="error=#pmSelect#">To proceed with checkout, you need to select a payment method.</span>

<form action="cart.php" method="post" name="payment_method">
<table border="0" align="center">
<tr FOREACH="paymentMethods,payment_method">
    <td width="5%"><input type="radio" name="payment_id" value="{payment_method.payment_method}" checked=isSelected(config.Payments.default_select_payment,payment_method.payment_method)></td>
    <td nowrap><b>{payment_method.name:h}</b></td>
    <td nowrap>{payment_method.details:h}</td>
</tr>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
        <br>
        <input type="hidden" name="target" value="checkout">
        <input type="hidden" name="action" value="payment">
        <widget class="CSubmit" label="Continue.." href="javascript: document.payment_method.submit()" font="FormButton">
    </td>
</tr>
</table>
EOT;
    $replace_str = <<<EOT
        <br>
        <input type="hidden" name="target" value="checkout">
        <input type="hidden" name="action" value="payment">
        <widget class="CSubmit" label="Continue.." href="javascript: continueCheckout(); void(0);" font="FormButton">
    </td>
</tr>
</table>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
