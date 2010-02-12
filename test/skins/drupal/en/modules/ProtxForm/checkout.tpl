<script language="Javascript" type="text/javascript">
function pfCheckoutSubmit()
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

	window.scroll(0, 100000);
	document.ProtxForm.submit();
}
</script>

<form action="{dialog.cart.PaymentMethod.formPostUrl:h}" method="POST" name="ProtxForm">
<INPUT type="hidden" name="VPSProtocol" value="2.22">
<INPUT type="hidden" name="TxType" value="{cart.paymentMethod.paymentType:h}">
<input type="hidden" name="Vendor" value="{cart.paymentMethod.vendorName:h}">
<input type="hidden" name="Crypt" value="{dialog.cart.PaymentMethod.getCryptedInfo(dialog.cart):h}">
<p>By clicking "SUBMIT" you are agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<span id="submit_order_button">
<input type="button" value="Submit order" onClick="pfCheckoutSubmit();">
</span>
<span id="submiting_process" style="display:none"></span>
</form>
