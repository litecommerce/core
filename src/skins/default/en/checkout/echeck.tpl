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
function CheckoutSubmit()
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
    <td class="Star">*</td>
    <td><input type="text" name="ch_info[ch_routing_number]" value="{cart.details.ch_routing_number:r}" size="32"></td>
    <td colspan="2"><widget class="\XLite\Validator\ArrayFieldValidator" field="ch_info[ch_routing_number]"></td>
</tr>
<tr valign="middle">
    <td align="right">Bank Account Number</td>
    <td valign="top" class="Star">*</td>
    <td valign="top"><input type="text" name="ch_info[ch_acct_number]" value="{cart.details.ch_acct_number:r}" size="32"></td>
    <td colspan="2"><widget class="\XLite\Validator\ArrayFieldValidator" field="ch_info[ch_acct_number]"></td>
</tr>
<tr valign="middle">
    <td align="right" width="40%">Type of Account</td>
    <td width="10" class="Star">*</td>
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
    <td class="Star">*</td>
    <td><input type="text" name="ch_info[ch_bank_name]" value="{cart.details.ch_bank_name:r}" size="32"></td>
    <td colspan="2"><widget class="\XLite\Validator\ArrayFieldValidator" field="ch_info[ch_bank_name]"></td>
</tr>
<tr valign="middle">
    <td align="right">Name under which the account is maintained at the bank</td>
    <td class="Star">*</td>
    <td><input type="text" name="ch_info[ch_acct_name]" value="{cart.details.ch_acct_name:r}" size="32"></td>
    <td colspan="2"><widget class="\XLite\Validator\ArrayFieldValidator" field="ch_info[ch_acct_name]"></td>
</tr>
<tr IF="displayNumber">
    <td align="right">Check number</td>
    <td class="Star">*</td>
    <td><input type="text" name="ch_info[ch_number]" value="{cart.details.ch_number:r}" size="32"></td>
    <td colspan="2"><widget class="\XLite\Validator\ArrayFieldValidator" field="ch_info[ch_number]"></td>
</tr>
</table>

<p>
<b>Notes</b><br>
<hr>
<table border="0" width="100%">
<tr>
    <td valign="top" align="right" width="40%">Customer notes:&nbsp;</td>
    <td align="left">&nbsp;<textarea cols="50" rows="7" name="notes"></textarea></td>
</tr>
</table>
</p>

<p>By clicking "SUBMIT" you agree with our "<a href='cart.php?target=help&amp;mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&amp;mode=privacy_statement'><u>Privacy statement</u></a>".<br>
<br>
<span id="submit_order_button">
<widget class="\XLite\View\Button\Regular" label="Submit order" jsCode="CheckoutSubmit();" />
</span>
<span id="submiting_process" style="display:none"></span>
</form>
