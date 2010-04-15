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
<form action="admin.php" method="POST" name="wholesale_config">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<input type="hidden" name="action" value="options"/>

<table border=0 cellpadding=3 cellspacing=1>
<tr>
	<td>Price message when price is denied</td>
	<td><input name="price_denied_message" size="50" value="{config.WholesaleTrading.price_denied_message}"></td>
</tr>
<tr>
	<td>Clone purchase limit settings when a product is cloned</td>
	<td><input type="checkbox" name="clone_wholesale_purchaselimit" checked="{config.WholesaleTrading.clone_wholesale_purchaselimit}" onClick="this.blur()"></td>
</tr>
<tr>
	<td>Clone wholesale pricing settings when a product is cloned</td>
	<td><input type="checkbox" name="clone_wholesale_pricing" checked="{config.WholesaleTrading.clone_wholesale_pricing}" onClick="this.blur()"></td>
</tr>
<tr>
	<td>Clone product access settings when a product is cloned</td>
	<td><input type="checkbox" name="clone_wholesale_productaccess" checked="{config.WholesaleTrading.clone_wholesale_productaccess}" onClick="this.blur()"></td>
</tr>
<tr>
	<td>Add Sales Permit/Tax ID field to the registration form</td>
	<td><input type="checkbox" name="WholesalerFieldsTaxId" checked="{config.WholesaleTrading.WholesalerFieldsTaxId}" onClick="this.blur()"></td>
</tr>
<tr>
	<td>Add VAT Registration number field to the registration form</td>
	<td><input type="checkbox" name="WholesalerFieldsVat" checked="{config.WholesaleTrading.WholesalerFieldsVat}" onClick="this.blur()"></td>
</tr>
<tr>
	<td>Add GST Registration number field to the registration form</td>
	<td><input type="checkbox" name="WholesalerFieldsGst" checked="{config.WholesaleTrading.WholesalerFieldsGst}" onClick="this.blur()"></td>
</tr>
<tr>
	<td>Add PST Registration number field to the registration form</td>
	<td><input type="checkbox" name="WholesalerFieldsPst" checked="{config.WholesaleTrading.WholesalerFieldsPst}" onClick="this.blur()"></td>
</tr>
<tr>
    <td>Membership granted through product purchase overrides current membership</td>
    <td><input type="checkbox" name="override_membership" checked="{config.WholesaleTrading.override_membership}" onClick="this.blur()"></td>
</tr>
<tr>
    <td>Enable direct addition to the cart for products with disabled catalog visibility</td>
    <td><input type="checkbox" name="direct_addition" checked="{config.WholesaleTrading.direct_addition}" onClick="this.blur()"></td>
</tr>
<tr>
	<td valign="top">Categories for bulk shopping<br><i>To (un)select more than one category, Ctrl-click it</i></td>
	<td>
	<widget class="XLite_Module_WholesaleTrading_View_CategorySelect" fieldName="bulk_categories[]" template="modules/WholesaleTrading/multiselect_category.tpl">
	</td>
</tr>
<tr>
	<td colspan=2><input type="submit" value=" Update "></td>
</tr>
</table>
</form>
