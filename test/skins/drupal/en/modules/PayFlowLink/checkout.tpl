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
		Element.innerHTML = "Please wait while connecting to <b>PayFlow Link</b> payment gateway...";
	}

	window.scroll(0, 100000);

	return false;
}

-->
</script>

<form action="{xlite.getShopUrl(#cart.php?target=pflcheckout#,config.Security.customer_security)}" method="POST" name="payflowlink_form" onSubmit="SubmitHandler()">
<p>By clicking "SUBMIT" you are agree with our "Terms &amp; Conditions" and "Privacy statement".<br>
<br>
<input type="submit" value="Submit order" id="submit_order_button">
</form>

<span id="submiting_process" style="display:none"></span>
