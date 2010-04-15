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
Use this page to configure your store to communicate with your Payment System Processing Gateway. <br>
Complete the required fields below and press the "Update" button.
<p>
<span class="SuccessMessage" IF="updated">ChronoPay parameters were successfully changed. Please make sure that the ChronoPay payment method is enabled on the <a href="admin.php?target=payment_methods">Payment methods</a> page before you can start using it.</span>

<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.payment_method}">
<center>
<table border=0 cellspacing=10>

<tr>
	<td rowspan="4" align="center"><img src="images/modules/ChronoPay/chronopay_logo.gif" border="0"></td>
    <td nowrap align="right">Product ID:</td>
    <td><input type=text name="params[product_id]" size=24 value="{pm.params.product_id:r}"></td>
</tr>

<tr>
<td nowrap align="right">Language:</td>
<td>
	<select name="params[language]">
		<option value="EN" selected="{isSelected(pm.params.language,#EN#)}">English</option>
		<option value="RU" selected="{isSelected(pm.params.language,#RU#)}">Russian</option>
		<option value="NL" selected="{isSelected(pm.params.language,#NL#)}">Dutch</option>
		<option value="ES" selected="{isSelected(pm.params.language,#ES#)}">Spanish</option>
	</select>
</td>
</tr>

<tr>
	<td valign=top nowrap align="right">URL:</td>
	<td><input type="text" size="50" name="params[url]" value="{pm.params.url:r}"><br>
	<i>normally <b>https://secure.chronopay.com/index_shop.cgi</b><br>
	or <b>https://secure.chronopay.com/index.cgi</b></i>
	</td>
</tr>

<tr>
	<td valign=top nowrap align="right">ChronoPay IP:</td>
	<td><input type="text" size="24" name="params[secure_ip]" value="{pm.params.secure_ip:r}"><br>
</tr>

</table>
<p>
<input type=submit value=" Update ">
</center>
</form>
