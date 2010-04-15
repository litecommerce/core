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
<tr IF="order.is(#processed#)&item.hasValidPins()">
	<td valign="top">PIN codes</td>
	<td>
		<table border="0" cellpadding="0" cellspacing="0">
		<tr FOREACH="item.pinCodes,pin">
		<td valign="top">{pin}</td>
		</tr>
		</table>
	</td>
</tr>
