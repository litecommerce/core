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
<span class="SuccessMessage" IF="updated">ePDQ parameters were successfully changed. Please make sure that the ePDQ payment method is enabled on the <a href="admin.php?target=payment_methods">Payment methods</a> page before you can start using it.</span>

<B>Note:</B> In setup ePDQ payment gateway, you have to proceed these steps:
<LI>Log in to your ePDQ backoffice</LI>
<LI>In the '<I>Allowed URL</I>' set URLs to :<BR>http://{xlite.options.host_details.http_host}{xlite.options.host_details.web_dir_wo_slash}/classes/modules/ePDQ/submit.php</LI>
<LI>In the '<I>POST URL</I>' set URLs to :<BR>http://{xlite.options.host_details.http_host}{xlite.options.host_details.web_dir_wo_slash}/classes/modules/ePDQ/callback.php<BR><I><FONT color=gray>(Transaction result data is posted to a predefined fulfilment script hosted on your<BR>web server - this is the POST URL. This must be a standard non-SSL page (http://))</FONT></I></LI>
<li>Set '<i>Encryption</i>' to <b>YES</b>
{if:!config.Security.customer_security}
<div class="ErrorMessage">Warning: turn on the customer zone security option in the <a href="admin.php?target=settings&page=Security">'General Settings/Security'</a> section</div>
{end:}
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.get(#payment_method#)}">
<table border=0 cellspacing=10>

<tr>
<td width="40%">Merchant display name</td>
<td><input type=text name=params[param01] size=32 value="{pm.params.param01}"></td>
</tr>

<tr>
<td>Client Id</td>
<td><input type=text name=params[param02] size=32 value="{pm.params.param02}"></td>
</tr>

<tr>
<td>Pass-phrase</td>
<td><input type=password name=params[param03] size=32 value="{pm.params.param03}"></td>
</tr>

<tr>
<td>Charge Type:</td>
<td><select name=params[param05]>
<option value=Auth selected="{IsSelected(pm.params.param05,#Auth#)}">Auth / immediate</option>
<option value=PreAuth selected="{IsSelected(pm.params.param05,#PreAuth#)}">PreAuth / delayed shipment</option>
</select>
</td>
</tr>

<tr>
<td>Currency:</td>
<td><select name=params[param04]>
<option value=036 selected="{IsSelected(pm.params.param04,#036#)}">Australian Dollar</option>
<option value=124 selected="{IsSelected(pm.params.param04,#124#)}">Canadian Dollar</option>
<option value=208 selected="{IsSelected(pm.params.param04,#208#)}">Danish Krone</option>
<option value=344 selected="{IsSelected(pm.params.param04,#344#)}">Hong Kong Dollar</option>
<option value=392 selected="{IsSelected(pm.params.param04,#392#)}">Japanese Yen</option>
<option value=578 selected="{IsSelected(pm.params.param04,#578#)}">Norwegian Krone</option>
<option value=752 selected="{IsSelected(pm.params.param04,#752#)}">Swedish Krona</option>
<option value=756 selected="{IsSelected(pm.params.param04,#756#)}">Swiss Francs</option>
<option value=826 selected="{IsSelected(pm.params.param04,#826#)}">Sterling</option>
<option value=840 selected="{IsSelected(pm.params.param04,#840#)}">US Dollars</option>
<option value=978 selected="{IsSelected(pm.params.param04,#978#)}">Euro</option>
</select>
</td>
</tr>

<tr>
<td>Store's logo URL:<br>Your logo will need to meet the following dimensions, WIDTH=500 HEIGHT=100.  Failure to do this will 'stretch' your logo. The graphical file must reside on a secure server (with an https:// URL). If this is not practical then you can show your storename instead.</td>
<td valign="top"><input type=text name=params[param06] size=32 value="{pm.params.param06}"></td>
</tr>

<tr>
<td>ePDQ encription tool URL:</td>
<td><input name=params[param08] size=32 value="{pm.params.param08}"></td>
</tr>

<tr>
<td>ePDQ gateway URL:</td>
<td><input name=params[param09] size=32 value="{pm.params.param09}"></td>
</tr>

<tr>
<td colspan=2><input type=submit value=" Update "></td>
</tr>

</table>
</form>
