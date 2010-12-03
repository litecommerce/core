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
	<td valign="top" nowrap>Download URL&nbsp;&nbsp;&nbsp;</td>
	<td IF="order.is(#processed#)">
	<table cellspacing="0" cellpadding="0" border="0" IF="item.hasValidLinks()">
	<tr FOREACH="item.getEgoods(),egood">
		<td valign="top">{egood.name:h}:&nbsp;&nbsp;&nbsp;</td>
		<td><a href="{egood.link:r}"><u>{egood.link:r}</u></a></td>
	</tr>
	</table>
	<span IF="!item.hasValidLinks()">
	N/A
	</span>
	</td>
	<td IF="!order.is(#processed#)">
	N/A
	</td>
</tr>
