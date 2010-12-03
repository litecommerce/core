{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script language="Javascript" type="text/javascript">
<!--
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
	document.SagePayForm.submit();
}
-->
</script>

<form action="{cart.PaymentMethod.formPostUrl:h}" method="POST" name="SagePayForm">
<INPUT type="hidden" name="VPSProtocol" value="2.23">
<INPUT type="hidden" name="TxType" value="{cart.paymentMethod.paymentType:h}">
<input type="hidden" name="Vendor" value="{cart.paymentMethod.vendorName:h}">
<input type="hidden" name="Crypt" value="{cart.PaymentMethod.getCryptedInfo(cart):h}">
<p>By clicking "SUBMIT" you are agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<span id="submit_order_button">
<input type="button" value="Submit order" onClick="pfCheckoutSubmit();">
</span>
<span id="submiting_process" style="display:none"></span>
</form>
