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

<span class="ErrorMessage" IF="error=#pmSelect#">To proceed with checkout, you need to select a payment method.</span>

<form action="{buildURL(#checkout#)}" method="post" name="payment_method">
<table border="0" align="center">
<tr FOREACH="paymentMethods,payment_method">
    <td width="5%"><input type="radio" name="payment_id" value="{payment_method.payment_method}" checked=isSelected(config.Payments.default_select_payment,payment_method.payment_method)></td>
    <td nowrap><b>{payment_method.name:h}</b></td>
    <td nowrap>{payment_method.details:h}</td>
</tr>
<tr>
    <td colspan="3" align="center">
        <br>
        <input type="hidden" name="target" value="checkout">
        <input type="hidden" name="action" value="payment">
        <widget class="XLite_View_Button_Regular" label="Continue.." jsCode="continueCheckout();" />
    </td>
</tr>
</table>
</form>
