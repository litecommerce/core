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
{if:isActiveCaptchaPage(id)}
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
        <img src="cart.php?target=captcha&id={id}&tmp={rand()}" id="{id}" alt="" /><br />
        <a href="javascript: void(changeCaptcha('{id}'));" class="captcha">Get a different code</a>
    <script type="text/javascript">
        //changeCaptcha('{id}');
    </script>
	</td>
	<td align="left">
        <input type="text" name="captcha_str" />
        <widget class="XLite_Validator_CaptchaValidator" id="{id}" field="captcha_str">
	</td>
</tr>
</table>
{end:}
