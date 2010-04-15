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
