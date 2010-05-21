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
<tr>
	<td class="FormButton">Select membership to sell through this product&nbsp;</td>
	<td class="ProductDeails">
		<widget class="XLite_View_MembershipSelect" field="selling_membership" value="{product.selling_membership}">
	</td>
</tr>
<tr>
	<td class="FormButton">Membership validity period:</td>
	<td>
		<input name="vperiod" size="7" value="{validatyPeriod}">&nbsp;
		<select name="vp_modifier">
			<option value="D" selected="{validatyModifier=#D#}">Days</option>
			<option value="W" selected="{validatyModifier=#W#}">Weeks</option>
			<option value="M" selected="{validatyModifier=#M#}">Months</option>
			<option value="Y" selected="{validatyModifier=#Y#}">Years</option>
		</select>
	</td>
</tr>
