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
<script language="JavaScript" type="text/javascript">
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

<div id="payment-methods-box">

  <h4>Payment methods</h4>

  <widget class="XLite_View_Form_Checkout_PaymentMethod" name="payment_method_form" />

  <ul IF="paymentMethods">
    <li FOREACH="paymentMethods,payment_method">
      <input type="radio" name="payment_id" value="{payment_method.payment_method}" id="payment_{payment_method.payment_method}" checked="isSelected(config.Payments.default_select_payment,payment_method.payment_method)" />
      <label for="payment_{payment_method.payment_method}" checked="isSelected(config.Payments.default_select_payment,payment_method.payment_method)" class="payment-method-title">{payment_method.name:h}</label>
      &nbsp;&nbsp;<span class="payment-method-descr" IF="{payment_method.details}">({payment_method.details:h})</span>
    </li>
  </ul>

  <br /><widget class="XLite_View_Button_Regular" label="Continue.." jsCode="continueCheckout();" />

  <widget name="payment_method_form" end />

</div>
