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
{if:clone}
<tr>
    <td colspan="4" align="right" class="ProductDetailsTitle">Global discount:</td>
    <td align="right">{price_format(invertSign(cloneOrder.global_discount)):h}</td>
</tr>
{else:}
<tr>
    <td colspan="4" align="right" class="ProductDetailsTitle">Global discount:</td>
    <td align="right">{price_format(invertSign(order.global_discount)):h}</td>
</tr>
{end:}
