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
Use this page to configure your store to communicate with your Nochex payment gateway. Complete the required fields below and press the "Update" button.
<p>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="nochex">
<table border="0" cellpadding="0" cellspacing="5" align="center">
<tr>
	<td align="right" width="250">Nochex account:</td>
	<td><input type=text name="params[param01]" size="32" value="{pm.params.param01:r}"></td>
</tr>
<tr>
    <td align="right" width="250">Mode:</td>
    <td><select name=params[param03]>
			<option value=T selected="{IsSelected(pm.params.param03,#T#)}">test</option>
			<option value=L selected="{IsSelected(pm.params.param03,#L#)}">live</option>
		</select>
	</td>
</tr>
<tr>
    <td align="right" width="250">Order prefix: </td>
    <td><input type=text name="params[param04]" size="32" value="{pm.params.param04:r}"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>	
</tr>
<tr>
	<td align="center" colspan=2><input type=submit value=" Update "></td>
</tr>
</table>
</form>
