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
<form action="admin.php" method="POST">
<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="target" value="product">
<input type="hidden" name="action" value="add_purchase_limit">
<table border="0">
<tr>
	<td>Minimal purchase limit</td>
	<td><input name="min_purchase" size="7" value="{PurchaseLimit.min}">
</tr>
<tr>
	<td>Maximal purchase limit</td>
	<td><input name="max_purchase" size="7" value="{purchaseLimit.max}">
</tr>
<tr>
	<td colspan="2"><input type="submit" value=" Update "></td>
</tr>	
</table>
</form>
