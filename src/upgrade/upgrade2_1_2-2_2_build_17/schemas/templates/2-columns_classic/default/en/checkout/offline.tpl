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
        Element.innerHTML = "Please wait while your order is processing...";
    }

    document.checkout.submit();
}
</script>

<form name="checkout" action="cart.php" method="POST">
<input type="hidden" name="target" value="checkout">
<input type="hidden" name="action" value="checkout">

<p>
<b>Notes</b><br>
<hr>
<table border="0" >
<tr>
    <td valign="top">Customer notes:&nbsp;</td>
    <td align="left"><textarea cols="50" rows="7" name="notes"></textarea></td>
</tr>
</table>

<p>By clicking "SUBMIT" you agree with our "<a href='cart.php?target=help&mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&mode=privacy_statement'><u>Privacy statement</u></a>".<br>
<br>
<span id="submit_order_button">
<widget class="CButton" label="Submit order" href="javascript: CheckoutSubmit();">
</span>
<span id="submiting_process" style="display:none"></span>
</form>
