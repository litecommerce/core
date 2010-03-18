<script language="Javascript">
<!--

function SubmitHandler()
{
	var Element = document.getElementById("submit_order_button");
	if (Element) {
		Element.disabled = true;
	}
	var Element = document.getElementById("submiting_process");
	if (Element) {
		Element.style.display = "";
		Element.innerHTML = "Please wait while connecting to <b>Verisign PayFlow Link</b> payment gateway...";
	}

	window.scroll(0, 100000);

	return false;
}

-->
</script>

<form action="{xlite.getShopUrl(#cart.php?target=vlcheckout#,config.Security.customer_security)}" method="POST" name="payflowlink_form" onSubmit="SubmitHandler()">
<p>By clicking "SUBMIT" you are agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order" id="submit_order_button">
</form>

<span id="submiting_process" style="display:none"></span>
