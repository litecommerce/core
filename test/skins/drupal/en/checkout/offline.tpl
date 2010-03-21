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

<form name="checkout" action="{buildURL(#checkout#)}" method="POST">
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

<p>By clicking "SUBMIT" you agree with our "<a href='cart.php?target=help&amp;mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&amp;mode=privacy_statement'><u>Privacy statement</u></a>".<br>
<br>
<span id="submit_order_button">
<widget class="XLite_View_Button_Regular" label="Submit order" jsCode="CheckoutSubmit();" />
</span>
<span id="submiting_process" style="display:none"></span>
</form>
