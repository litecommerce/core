<form action="cart.php" method="POST" name="gccheckoutform">
<input type="hidden" name="target" value="checkout">
<input type="hidden" name="action" value="checkout">
<input type="hidden" name="mode" value="details">

<widget class="CGCValidator" field="gcid">
<br>
Please enter the eight-character gift certificate code&nbsp;&nbsp; <font class="Star">*</font> <input type="text" size="20" name="gcid">

<p>By clicking "SUBMIT" you agree with our "<a href='cart.php?target=help&mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&mode=privacy_statement'><u>Privacy statement</u></a>".<br>
<br>
<widget class="CButton" label="Submit order" href="javascript: document.gccheckoutform.submit();">
</form>
