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
<span class="SuccessMessage" IF="updated">2Checkout.com parameters were successfully changed.<br>Please make sure that the 2Checkout payment method is enabled on the <a href="admin.php?target=payment_methods">Payment methods</a> page before you can start using it.<hr></span>


<table border=0 cellspacing=0>
<form action="admin.php" method="POST" name="payment_version">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.payment_method}">
<input type="hidden" name="params[login]" value="{pm.params.login:r}">
<input type="hidden" name="params[url]" value="{pm.params.url:r}">
<input type="hidden" name="params[md5HashValue]" value="{pm.params.md5HashValue}">
<input type="hidden" name="params[account_number]" value="{pm.params.account_number}">
<input type="hidden" name="params[secret_word]" value="{pm.params.secret_word}">
<input type="hidden" name="params[test_mode]" value="{pm.params.test_mode}">

<tr>
    <td align="right" width="200">2Checkout.com payment version:</td>
    <td>
    <select name="params[version]">
	<option value="1" selected="{pm.params.version=#1#}">Version 1 (old mode)</option>
	<option value="2" selected="{pm.params.version=#2#}">Version 2 (new mode)</option>
    </select>
    </td>
    <td>&nbsp;&nbsp;</td>
    <td>
	<input type=submit value=" Change ">
    </td>
</tr>

</form>
</table>

<hr>

{if:pm.params.version=#1#}

Use this page to configure your store to 
communicate with your 2Checkout.com Payment Processing Gateway. Complete the 
required fields below and press the "Update" button. 
<P><B>Note:</B> In order to track your 2Checkout orders by the 
shopping cart software you have to proceed these steps: 
<LI>Log in to your 2Checkout account 
<LI>Go to the '<I>Shopping cart</I>' menu 
<LI>Set the option '<I>Return to a routine on your site after credit 
card processed?</I>' to '<I>Yes</I>' 
<LI>Set the '<I>Return URL</I>' 
to:<BR>{getShopUrl(#cart.php?target=callback&action=callback&order_id_name=x_invoice_num#):h}
<P>

<table border=0 cellspacing=5>
<form action="admin.php" method="POST" name="payment_config">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.payment_method}">
<input type="hidden" name="params[version]" value="{pm.params.version}">
<input type="hidden" name="params[account_number]" value="{pm.params.account_number}">
<input type="hidden" name="params[secret_word]" value="{pm.params.secret_word}">
<input type="hidden" name="params[test_mode]" value="{pm.params.test_mode}">

<tr>
	<td align="right" width="200">Merchant account ID:</td>
	<td><input type=text name="params[login]" size=24 value="{pm.params.login:r}"></td>
</tr>
<tr>
    <td align="right" width="200">URL:</td>
    <td>
	<input type="text" size="50" name="params[url]" value="{pm.params.url:r}">
    </td>
</tr>

<!--tr>
	<td align="right" width="200">
		Secret Word: <br>
		<i>A Secret Word is known only to the seller and 2CheckOut. You set your "Secret Word" in the admin area under the Account Details -> Return tab</i>
	</td>
	<td valign="top"><input type=text name="params[md5HashValue]" size=24 value="{pm.params.md5HashValue:r}"></td>
</tr-->
<input type="hidden" name="params[md5HashValue]" value="{pm.params.md5HashValue}">

<tr>
	<td align="center" colspan=2>
	<br><input type=submit value=" Update ">
	</td>
</tr>

</form>
</table>

{end:}

{if:pm.params.version=#2#}

Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.
<P>
<b>Note:</b> In order to track your 2Checkout orders by the shopping cart software you have to proceed these steps:
<li>Log in to your 2Checkout account</li>
<li>Click on the '<i>Look & Feel settings</i>' in the '<i>Helpful links</i>' section.</li>
<li>Set the option '<i>Direct Return?</i>' to '<i>Yes</i>'</li>
<li>Set the '<i>Approved URL</i>' to:<br>http<span IF="config.Security.customer_security">s://{xlite.options.host_details.https_host}</span><span IF="!config.Security.customer_security">://{xlite.options.host_details.http_host}</span>{xlite.options.host_details.web_dir_wo_slash}/classes/modules/2CheckoutCom/callback.php</li>
<li>Set the '<i>Your secret Word</i>'. A Secret Word is known only to the seller and 2CheckOut.

<P>

<table border=0 cellspacing=0 cellpadding=10>
<tr>
	<td>
    	<img src="http://www2.2checkout.com/images/buyer/2co.gif">
	</td>
	<td>
        <table border=0 cellspacing=5>
        <form action="admin.php" method="POST" name="payment_config">
        <input type="hidden" name="target" value="payment_method">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="payment_method" value="{pm.payment_method}">
        <input type="hidden" name="params[version]" value="{pm.params.version}">
        <input type="hidden" name="params[login]" value="{pm.params.login:r}">
        <input type="hidden" name="params[url]" value="{pm.params.url:r}">
        <input type="hidden" name="params[md5HashValue]" value="{pm.params.md5HashValue}">

        <tr>
        	<td align="right" width="200">2Checkout.com account number:</td>
        	<td><input type=text name="params[account_number]" size=24 value="{pm.params.account_number:r}"></td>
        </tr>

        <tr>
        	<td align="right" width="200">Secret Word:</td>
        	<td valign="top"><input type=password name="params[secret_word]" size=24 value="{pm.params.secret_word:r}"></td>
        </tr>

        <tr>
            <td align="right" width="200">Test/Live mode:</td>
            <td>
            <select name="params[test_mode]">
        	<option value="Y" selected="{pm.params.test_mode=#Y#}">Test mode</option>
        	<option value="N" selected="{pm.params.test_mode=#N#}">Live mode</option>
            </select>
            </td>
        </tr>

        <tr>
        	<td align="center" colspan=2>
        	<br><input type=submit value=" Update ">
        	</td>
        </tr>

        </form>
        </table>
	</td>
</tr>
</table>

{end:}
