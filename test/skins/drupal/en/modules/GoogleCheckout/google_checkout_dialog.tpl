<span IF="action=#checkout#">
<font class="ErrorMessage">Order processing error !</font><br><br>
Checkout cannot be started as it is impossible to redirect to the Google Checkout server.<br>
<span IF="googleError"><br><b>Error: </b>{googleError}<br></span>
<br>
<widget class="XLite_View_Button" label="Go back" href="cart.php?target=cart" font="FormButton">
