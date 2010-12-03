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
<p>
<span class="SuccessMessage" IF="updated">Parameters were successfully changed. Please make sure that the PayFlow Link  payment method is enabled on the <a href="admin.php?target=payment_methods">Payment methods</a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.payment_method}">

<table border=0 cellspacing=10>
<tr>
	<td width="120" valign="top"><a href="https://payments.verisign.com/manager" target="_blank" title="Set up your PayFlow Link account information" onClick="this.blur()"><img src="images/modules/PayFlowLink/PayFlowLink.gif" border=0></a></td>
	<td>
<b>Note:</b> In order to track your PayFlow Link orders by the shopping cart software you have to proceed these steps:
<li>Log in to your PayFlow Link account</li>
<li>Go to the '<i>Account Info/Payflow Link Info</i>' menu</li>
<li>Set the option '<i>Return URL Method</i>' to '<i>POST</i>'</li>
<li>Set the '<i>Return URL</i>' to:<br> {getShopUrl(#classes/modules/PayFlowLink/callback.php#):h}</li>
<P>
</td>
</tr>

<tr>
	<td align="right">Login:</td>
	<td><input type=text name="params[login]" size=24 value="{pm.params.login:r}"></td>
</tr>

<tr>
	<td align="right">Partner:</td>
	<td><input type=text name="params[partner]" size=24 value="{pm.params.partner:r}"></td>
</tr>

<tr>
	<td align="right">Gateway URL:</td>
	<td><input type=text name="params[gateway_url]" size=24 value="{pm.params.gateway_url:r}"></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td colspan=2><p><input type=submit value=" Update "></td>
</tr>

</table>

</form>
