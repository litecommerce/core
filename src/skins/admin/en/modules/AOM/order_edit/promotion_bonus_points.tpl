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
<tr class="{getRowClass(#0#,#TableRow#)}" height="25" IF="xlite.PromotionEnabled">
	<td><b>Bonus points discount:</b></td>
	<td IF="target=#order#">{price_format(order,#payedByPoints#):h}</td>
	<td><input type="text" name="clone[payedByPoints]" value="{cloneOrder.payedByPoints}" size="5"></td>
</tr>
