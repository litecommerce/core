{if:isActiveCaptchaPage(widget.id)}
<table border="0" cellpadding="0" cellspacing="5" width="100%">
<tr>
    <td height="20" colspan="2"><b>Image verification</b><hr noshade="noshade" size="1"></td>
</tr>
<tr>
    <script language="JavaScript" type="text/javascript">
    function changeCaptcha(id) {
	    var image = document.getElementById(id);
    	if (image)
	    	image.src = "cart.php?target=captcha&id="+id+"&tmp="+Math.random();
    }
    </script>
	<td colspan="2"><p align="justify">Type the characters you see in the picture. (If you do not see any picture here, please enable images in your web browser options and refresh this page)</p></td>
</tr>
<tr>
	<td align="left" width="10%">
        <img src="cart.php?target=captcha&id={widget.id}&tmp={rand()}" id="{widget.id}" alt="" /><br />
        <a href="javascript: void(changeCaptcha('{widget.id}'));" class="captcha">Get a different code</a>
    <script type="text/javascript">
        //changeCaptcha('{widget.id}');
    </script>
	</td>
	<td align="left">
        <input type="text" name="captcha_str" />
        <widget class="XLite_Validator_CaptchaValidator"id="{widget.id}" field="captcha_str">
	</td>
</tr>
</table>
{end:}
