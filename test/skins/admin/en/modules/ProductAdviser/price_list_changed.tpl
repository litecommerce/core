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
	<td>&nbsp;</td>
	<td colspan=9>
		<table border=0 cellpadding=1 cellspacing=1>
		<tr>
        	<td class="SidebarBorder" colspan=3>
        		<table border=0 cellpadding=1 cellspacing=1 class="SidebarBox">
                	<tr>
						<td><img src="images/modules/ProductAdviser/alert.gif" width=12 height=12 border=0></td>
						<td>&nbsp;</td>
                		<td class="ProductDetails">There <span IF="isNotifyPresent(product.product_id)=#1#">is</span><span IF="!isNotifyPresent(product.product_id)=#1#">are</span> <b><font color=blue>{isNotifyPresent(product.product_id)}</font> Customer Notification<span IF="!isNotifyPresent(product.product_id)=#1#">s</span></b> awaiting.</span></td>
                		<td>&nbsp;<a href="admin.php?target=CustomerNotifications&type=price&status=U&period=-1&notify_key={product_id}" onClick="this.blur()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle">&nbsp;<b><u>View request<span IF="!isNotifyPresent(product.product_id)=#1#">s</span></u></b></a></td>
                	</tr>
                </table>
        	</td>
		</tr>
		</table>
	</td>
</tr>
