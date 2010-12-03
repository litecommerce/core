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
<p align=justify>From this section you can grant access to the selected memberships. <a href="admin.php?target=memberships"><u>Click on this link</u></a> to add/delete memberships.</p>

<form action="admin.php" method="POST" name=add_option_form>
<input FOREACH="allparams,name,val" type="hidden" name="{name}" value="{val}"/>
<input type="hidden" name="action" value="update_access">

<table width="100%" border=0>
<tr>
	<td width="33%">The product will be shown to: </td>
	<td width="33%">The product price will be shown to: </td>
	<td width="33%">The product will be available for sale to: </td>
</tr>
<tr>
	<td>
	<select multiple size="10" name=access_show[]>
		<option value="all" selected="productAccess.groupInAccessList(#all#,#show_group#,false)">All</option>
		<option value="registered" selected="productAccess.groupInAccessList(#registered#,#show_group#,false)">Registered</option>
		<option FOREACH="getMemberships(),membership" value="{membership.membership_id}" selected="productAccess.groupInAccessList(membership.membership_id,#show_group#,false)">{membership.name}</option>
	</select>
	</td>
	<td>
	<select multiple size="10" name=access_show_price[]>
		<option value="all" selected="productAccess.groupInAccessList(#all#,#show_price_group#,false)">All</option>
		<option value="registered" selected="productAccess.groupInAccessList(#registered#,#show_price_group#,false)">Registered</option>
		<option FOREACH="getMemberships(),membership" value="{membership.membership_id}" selected="productAccess.groupInAccessList(membership.membership_id,#show_price_group#,false)">{membership.name}</option>
	</select>
	</td>
	<td>
	<select multiple size="10" name=access_sell[]>
		<option value="all" selected="productAccess.groupInAccessList(#all#,#sell_group#,false)">All</option>
        <option value="registered" selected="productAccess.groupInAccessList(#registered#,#sell_group#,false)">Registered</option>
		<option FOREACH="getMemberships(),membership" value="{membership.membership_id}" selected="productAccess.groupInAccessList(membership.membership_id,#sell_group#,false)">{membership.name}</option>
	</select>
	</td>
</tr>	
</table>
<br>Note: To (un)select more than one group, Ctrl-click it.<br><br>
<input type="submit" value="Update" />
</form>
