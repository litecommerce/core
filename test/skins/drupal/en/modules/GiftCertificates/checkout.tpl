<form action="cart.php" method="POST" name="gccheckoutform">
<input type="hidden" name="target" value="checkout">
<input type="hidden" name="action" value="checkout">
<input type="hidden" name="mode" value="details">

<widget class="XLite_Module_GiftCertificates_Validator_GCValidator" field="gcid">
<br>
Please enter the eight-character gift certificate code&nbsp;&nbsp; <font class="Star">*</font> <input type="text" size="20" name="gcid">

<p>By clicking "SUBMIT" you agree with our "<a href='cart.php?target=help&mode=terms_conditions'><u>Terms &amp; Conditions</u></a>" and "<a href='cart.php?target=help&mode=privacy_statement'><u>Privacy statement</u></a>".<br>
<br>
<widget class="XLite_View_Button" label="Submit order" href="javascript: document.gccheckoutform.submit();">
</form>
