<?php
    $find_str = <<<EOT
<form name="checkout" action="cart.php" method="POST">
<input type="hidden" name="target" value="checkout">
<input type="hidden" name="action" value="checkout">
EOT;
    $replace_str = <<<EOT
<script language="Javascript">
function CheckoutSubmit()
{
	var Element = document.getElementById("submit_order_button");
	if (Element) {
		Element.style.display = 'none';
	}

	var Element = document.getElementById("submiting_process");
	if (Element) {
		Element.style.display = "";
		Element.innerHTML = "Please wait while your order is processing...";
	}

	document.checkout.submit();
}
</script>

<form name="checkout" action="cart.php" method="POST">
<input type="hidden" name="target" value="checkout">
<input type="hidden" name="action" value="checkout">
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
</tr>
</table>

<p>By clicking "SUBMIT" you are agree with our "<a href='cart.php?target=help&mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&mode=privacy_statement'><u>Privacy statement</u></a>".<br>
<br>
<input type="submit" value="Submit order">
</form>
EOT;
    $replace_str = <<<EOT
</tr>
</table>

<p>By clicking "SUBMIT" you agree with our "<a href='cart.php?target=help&mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&mode=privacy_statement'><u>Privacy statement</u></a>".<br>
<br>
<span id="submit_order_button">
<widget class="CButton" label="Submit order" href="javascript: CheckoutSubmit();">
</span>
<span id="submiting_process" style="display:none"></span>
</form>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
