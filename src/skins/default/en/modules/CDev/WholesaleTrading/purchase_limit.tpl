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
<tr><td colspan=2>&nbsp;</td></tr>
<tr class="descriptionTitle">
	<td colspan=2 class="ProductDetailsTitle">Purchase Limit</td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
<tr IF="product.purchaseLimit.min">
	<td colspan="2">Min. quantity: {product.purchaseLimit.min:h}</td>
</tr>
<tr IF="product.purchaseLimit.max">
    <td colspan="2">Max. quantity: {product.purchaseLimit.max:h}</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
