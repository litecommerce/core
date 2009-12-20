<script language="JavaScript">
function checkoutCart()
{
	var Element = document.getElementById("submit_order_button");
	if (Element) {
		Element.style.display = 'none';
	}

	var Element = document.getElementById("submiting_process");
	if (Element) {
		Element.style.display = "";
		Element.innerHTML = "<b>Please wait while your order is being processed...</b>";
	}

	document.checkout.submit();
}
</script>

<form name="checkout" action="cart.php" method="POST">
<input type="hidden" name="target" value="checkout">
<input type="hidden" name="action" value="checkout">
<p>By clicking "SUBMIT" you agree with our "Terms &amp; Conditions" and "Privacy statement".</p>
<span id="submit_order_button">
<input type="submit" value="Submit order" OnClick="checkoutCart();">
</span>
<span id="submiting_process" style="display:none"></span>
</form>
