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
Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.
<p>
<span class="SuccessMessage" IF="updated">PayPal settings were successfully changed. Please make sure that the PayPal payment method is enabled on the <a href="admin.php?target=payment_methods">Payment methods</a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="paypal">
<table border=0>

<tr>
<td align="right" width="250">PayPal account:</td>
<td><input type=text name="params[login]" size="40" value="{pm.params.login:r}"></td>
</tr>

<tr>
<td align="right" width="250">
	PayPal logo URL:<br>
	<i>The internet URL of the 150 X 50 pixel image you would like to use as your logo</i>
</td>
<td valign="top">
	<input type="text" name="params[image_url]" size="40" value="{pm.params.image_url:r}">
</td>
	</tr>

<tr>
<td align="right" width="250">
	PayPal invoice prefix:<br>
	<i>This text will appear as an invoice name in your PayPal transaction history</i>
</td>
<td valign="top">
	<input type="text" name="params[prefix]" size="40" value="{pm.params.prefix:r}">
</td>
</tr>

<tr>
<td align="right" width="250">
	Currency:<br>
	<i>You can setup an automatic currency conversion in the "Payment Recieving Preferences" in your PayPal account</i>
</td>
<td valign="top">
	<select name="params[currency]">
    <option value="USD" selected="{isSelected(#USD#,pm.params.currency)}">U.S. Dollars (USD)</option>
    <option value="CAD" selected="{isSelected(#CAD#,pm.params.currency)}">Canadian Dollars (CAD)</option>
    <option value="EUR" selected="{isSelected(#EUR#,pm.params.currency)}">Euros (EUR)</option>
    <option value="GBP" selected="{isSelected(#GBP#,pm.params.currency)}">Pounds Sterling (GBP)</option>
    <option value="JPY" selected="{isSelected(#JPY#,pm.params.currency)}">Yen (JPY)</option>
    <option value="AUD" selected="{isSelected(#AUD#,pm.params.currency)}">Australian Dollars (AUD)</option>
	</select>
</td>
</tr>
<tr>
    <td align="right" width="250">
        URL:
    </td>
    <td valign="top">
        <input type="text" size="50" name="params[url]" value="{pm.params.url:r}">
    </td>
</tr>

<tr>
    <td align="right" width="250">
        Treat PayPal orders in progress as 'Queued' rather than 'Incomplete':
    </td>
    <td valign="top">
        <input type="checkbox" name="params[use_queued]" value="1" onClick="this.blur()" checked="{pm.params.use_queued}">
    </td>
</tr>

</table>
<p>
<input type=submit value=" Update ">
</form>
</center>

<HR>

<B>Note:</B> Please make sure the 'Instant Payment Notification Preferences' have been set up correctly.<br>
Log into your PayPal account, go to the "My Account / Profile / Instant Payment Notification Preferences".<br>
You should have:<br>
&nbsp;&nbsp;&nbsp;- Instant Payment Notification (IPN) checkbox selected,<br>
&nbsp;&nbsp;&nbsp;- Instant Payment Notification (IPN) URL field empty.<br>
