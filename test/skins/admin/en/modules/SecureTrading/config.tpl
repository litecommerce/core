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
<span class="SuccessMessage" IF="updated">Securetrading.com settings were successfully changed. Please make sure that the  payment method is enabled on the <a href="admin.php?target=payment_methods">Payment methods</a> page before you can start using it.</span>
<center>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="securetrading">
<table border="0" width="100%">
<tr>
	<td align="center" rowspan="4"><img src="skins/admin/en/modules/SecureTrading/logo.gif" border=0></td>
	<td align="right">Site reference : </td>
    <td><input type="text" name="params[merchant]" size="40" value="{pm.params.merchant:r}"></td>
</tr>
<tr>
    <td align="right">URL : </td>
    <td><input type="text" name="params[url]" size="40" value="{pm.params.url:r}"></td>
</tr>
<tr>
    <td align="right">Order prefix : </td>
    <td><input type="text" name="params[order_prefix]" size="40" value="{pm.params.order_prefix:r}"></td>
</tr>
<tr> 
    <td align="right">Currency : </td>
    <td><select name="params[currency]">
	<option value="USD" selected="{isSelected(#USD#,pm.params.currency)}">U.S. Dollars (USD)</option>
    <option value="EUR" selected="{isSelected(#EUR#,pm.params.currency)}">Euros (EUR)</option>
    <option value="GBP" selected="{isSelected(#GBP#,pm.params.currency)}">Pounds Sterling (GBP)</option>
				
	</td>
</tr> 
</table>
<p>
<input type=submit value=" Update ">
</center>
</form>
<p align="justify">
Here is a brief instruction how to easily setup Securetrading.com "stpaymentpages" add-on for processing online transactions:
</p>
<p align="justify">
	<ul align="left">
	<li>Download the following files (right-click on the link and choose the "Save As" option): </li>
	<ol type="none">
		<li><a href="skins/admin/en/modules/SecureTrading/templates/callback-f.txt">callback-f.txt</a></li>
		<li><a href="skins/admin/en/modules/SecureTrading/templates/callback.txt">callback.txt</a></li> 
		<li><a href="skins/admin/en/modules/SecureTrading/templates/customeremail.txt">customeremail.txt</a></li> 
		<li><a href="skins/admin/en/modules/SecureTrading/templates/failure.html">failure.html</a></li> 
		<li><a href="skins/admin/en/modules/SecureTrading/templates/failureemail.txt">failureemail.txt</a></li> 
		<li><a href="skins/admin/en/modules/SecureTrading/templates/form.html">form.html</a></li> 
		<li><a href="skins/admin/en/modules/SecureTrading/templates/merchantemail.txt">merchantemail.txt</a></li> 
		<li><a href="skins/admin/en/modules/SecureTrading/templates/success.html">success.html</a></li> 
	</ol>
	<li>Change <b>callback-f.txt</b> and <b>callback.txt</b> files as described below: 
	<ol type="lower-alpha">	
		<li>Open the file</li>
		<li>Find the row with URL definition: <br><br>
			url1    http://www.yourdomain.com/your_web_dir/cart.php?target=callback&action=callback&order_id_name=orderinfo<br><br>
		and replace this URL to: <br><br>
			url1	{if:config.Security.customer_security}https://{xlite.options.host_details.https_host}{else:}http://{xlite.options.host_details.http_host}{end:}{xlite.options.host_details.web_dir_wo_slash}/cart.php?target=callback&action=callback&order_id_name=orderinfo
<br><br>
			(please make sure that url1 and link are tab separated)<br><br>
		</li>
	</ol>
	<li>Upload these files instead of yours through the "File manager" section of your SecureTrading account.</li> 
	</ul>
</p>
