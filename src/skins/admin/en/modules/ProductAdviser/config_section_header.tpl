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
<TD colspan=2 IF="id=#0#|id=#8#|id=#9#|id=#13#|id=#20#">
	<table cellspacing=0 cellpadding=0 border=0 width="100%">
	<tr IF="!id=#0#">
		<td colspan=2>&nbsp;</td>
	</tr>
	<tr>
		<td class="SidebarTitle" align=center width=250 nowrap>
		<span IF="id=#0#">Related products</span>
		<span IF="id=#8#">Recently viewed</span>
		<span IF="id=#9#">New Arrivals</span>
		<span IF="id=#13#">People who buy this product also buy</span>
		<span IF="id=#20#">Customer Notifications</span>
		</td>
		<td width=100%>&nbsp;</td>
	</tr>
	<tr>
		<td class="SidebarTitle" align=center colspan=2 height=3></td>
	</tr>
	</table>
</TD>
