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
Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="updated">CyberSource parameters were successfully changed. Please make sure that the CyberSource payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.get(#payment_method#)}">
<table border=0 cellspacing=10>
<tr>
<td>Merchant ID:</td>
<td><input type=text name=params[param01] size=24 value="{pm.params.param01}"></td>
</tr>
<tr>
<td>Full ICS path:</td>
<td><input type=text name=params[param02] size=24 value="{pm.params.param02}"></td>
</tr>
<tr>
<td>Server:Port:</td>
<td><input type=text name=params[param03] size=24 value="{pm.params.param03}">:<input type=text name=params[param04] size=4 value="{pm.params.param04}"></td>
</tr>
<tr>
<td>Currency:</td>
<td>
<select name=params[param05]>
<option value="USD" selected="{IsSelected(pm.params.param05,#USD#)}">US Dollars
<option value="GBP" selected="{IsSelected(pm.params.param05,#GBP#)}">Sterling
<option value="EUR" selected="{IsSelected(pm.params.param05,#EUR#)}">Euro
</select>
</td>
</tr>
<tr>
<td colspan=2>
<input type=submit value=" Update ">
</td>
</tr>
</table>
</form>
