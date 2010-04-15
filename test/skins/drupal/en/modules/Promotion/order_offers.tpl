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
<tbody IF="order.DC">
<tr>
	<td nowrap colspan=2>&nbsp;</td>
<tr>
<tr>
	<td nowrap bgcolor="#DDDDDD" colspan=2><b>Discount Coupon</b></td>
<tr>
<tr>
    <td nowrap>Coupon:</td>
    <td>{order.DC.coupon:h}</td>
</tr>
<tr>
    <td nowrap><b>Discount:</b></td>
    <td IF="order.DC.type=#absolute#">{price_format(order.DC.discount):h}</td>
    <td IF="order.DC.type=#percent#">{order.DC.discount}%</td>
    <td IF="order.DC.type=#freeship#">Free shipping</td>
</tr>
</tbody>
