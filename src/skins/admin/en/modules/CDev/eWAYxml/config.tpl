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
<span class="SuccessMessage" IF="updated">eWAYxml parameters were successfully changed. Please make sure that the eWAYxml payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.get(#payment_method#)}">
<table border=0 cellspacing=10>

<tr>
<td>ewayCustomerID:</td>
<td><input type=text name=params[param01] size=32 value="{pm.params.param01}"></td>
</tr>

<tr>
<td>Test/Live mode:</td>
<td>
<select name=params[testmode]>
<option value="Y" selected="{IsSelected(pm.params.testmode,#Y#)}">test
<option value="N" selected="{IsSelected(pm.params.testmode,#N#)}">live
</select>
</td>
</tr>

<tr>
<td>Order prefix:</td>
<td><input type=text name=params[param03] size=32 value="{pm.params.param03}"></td>
</tr>

<tr>
<td>Live gatway address:</td>
<td><input type=text name=params[param08] size=32 value="{pm.params.param08}"></td>
</tr>

<tr>
<td>Test gateway address:</td>
<td><input type=text name=params[param09] size=32 value="{pm.params.param09}"></td>
</tr>

<tr>
<td colspan=2>
<input type=submit value=" Update ">
</td>
</tr>

</table>
</form>
