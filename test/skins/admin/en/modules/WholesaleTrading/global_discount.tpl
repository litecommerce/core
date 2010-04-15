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
<span FOREACH="globalDiscounts,discount">
<form action="admin.php" name="discount_form_{discount.discount_id}" method="POST">
<input type="hidden" name="target" value="global_discount">
<input type="hidden" name="action" value="update">
<input type="hidden" name="discount_id" value="{discount.discount_id}">

<table border=0 cellpadding=3 cellspacing=1>
<tr>
	<td class=TableHead>Order subtotal</td>
	<td class=TableHead>Membership</td>
	<td class=TableHead>Discount</td>
	<td class=TableHead>Discount type</td>
</tr>
<tr>
	<td><input name="gd_subtotal" value="{discount.subtotal}" size="7"></td>
	<td><widget class="XLite_View_MembershipSelect" field="gd_membership" allOption value="{discount.membership}"></td>
	<td><input name="gd_value" value="{discount.discount}" size="7"></td>
	<td>
		<select name="gd_type" align="right">
		<option value="a" selected="{discount.discount_type=#a#}">absolute</option>
		<option value="p" selected="{discount.discount_type=#p#}">percent</option>
		</select>
	</td>
</tr>
</table>
<input type="submit" value=" Update "> 
<input type="button" value=" Delete " OnClick="document.discount_form_{discount.discount_id}.action.value='delete'; document.discount_form_{discount.discount_id}.submit();">
<br>
<br>
</form>
</span>

<form action="admin.php" name="add_global_discount" method="POST">
<input type="hidden" name="target" value="global_discount">
<input type="hidden" name="action" value="add">

<table border=0>
<tr>
    <td colspan=2 class=AdminTitle>Add global discount</td>
</tr>
<tr><td colspan=2>&nbsp;</td>
<tr>
	<td>Subtotal:</td>
	<td><input size="7" name="discount_subtotal"></td>
</tr>
<tr>
    <td>Membership:</td>
    <td><widget class="XLite_View_MembershipSelect" allOption field="discount_membership" value=""></td>
</tr>
<tr>
	<td>Discount:</td>
	<td><input size="7" name="discount_value"></td>
</tr>
<tr>
	<td>Discount type</td>
	<td>
		<select name="discount_type">
		<option value="a">absolute</option>
		<option value="p">percent</option>
		</select>
	</td>
</tr>	
</table>
<input type="submit" value=" Add ">
</form>
