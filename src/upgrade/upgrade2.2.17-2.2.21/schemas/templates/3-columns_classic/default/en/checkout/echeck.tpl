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
        Element.innerHTML = "<b>Please wait while your order is processing...</b>";
    }

	window.scroll(0, 100000);
    document.checkout.submit();
}
</script>

<form name="checkout" action="cart.php" method="POST">
<input type="hidden" name="target" value="checkout">
<input type="hidden" name="action" value="checkout">

<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle">
    <td height="20" colspan="4"><b>E-check information</b><hr size=1 noshade></td>
</tr>
<tr valign="middle">
    <td align="right">ABA routing number</td>
    <td><font class="Star">*</font></td>
    <td><input type="text" name="ch_info[ch_routing_number]" value="{cart.details.ch_routing_number:r}" size="32"></td>
    <td colspan="2"><widget class="CArrayFieldValidator" field="ch_info[ch_routing_number]"></td>
</tr>
<tr valign="middle">
    <td align="right">Bank Account Number</td>
    <td valign="top"><font class="Star">*</font></td>
    <td valign="top"><input type="text" name="ch_info[ch_acct_number]" value="{cart.details.ch_acct_number:r}" size="32"></td>
    <td colspan="2"><widget class="CArrayFieldValidator" field="ch_info[ch_acct_number]"></td>
</tr>
<tr valign="middle">
    <td align="right" width="40%">Type of Account</td>
    <td width="10"><font class="Star">*</font></td>
    <td>
        <select name="ch_info[ch_type]">
            <option value="CHECKING" selected="{isSelected(#CHECKINGS#,cart.details.ch_type)}">Checking</option>
            <option value="SAVINGS" selected="{isSelected(#SAVINGS#,cart.details.ch_type)}">Savings</option>
        </select>
    </td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr valign="middle">
    <td align="right">Name of bank at which account is maintained</td>
    <td><font class="Star">*</font></td>
    <td><input type="text" name="ch_info[ch_bank_name]" value="{cart.details.ch_bank_name:r}" size="32"></td>
    <td colspan="2"><widget class="CArrayFieldValidator" field="ch_info[ch_bank_name]"></td>
</tr>
<tr valign="middle">
    <td align="right">Name under which the account is maintained at the bank</td>
    <td><font class="Star">*</font></td>
    <td><input type="text" name="ch_info[ch_acct_name]" value="{cart.details.ch_acct_name:r}" size="32"></td>
    <td colspan="2"><widget class="CArrayFieldValidator" field="ch_info[ch_acct_name]"></td>
</tr>
<tr IF="displayNumber">
    <td align="right">Check number</td>
    <td><font class="Star">*</font></td>
    <td><input type="text" name="ch_info[ch_number]" value="{cart.details.ch_number:r}" size="32"></td>
    <td colspan="2"><widget class="CArrayFieldValidator" field="ch_info[ch_number]"></td>
</tr>
</table>

<p>By clicking "SUBMIT" you agree with our "<a href='cart.php?target=help&mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&mode=privacy_statement'><u>Privacy statement</u></a>".<br>
<br>
<span id="submit_order_button">
<widget class="CButton" label="Submit order" href="javascript: CheckoutSubmit();">
</span>
<span id="submiting_process" style="display:none"></span>
</form>
