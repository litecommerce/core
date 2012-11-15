{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice totals : total
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="invoice.base.totals", weight="300")
 *}
<tr bgcolor="grey">
  <td class="title"><strong>{t(#Grand total#)}:</strong></td>
  <td align="right"><strong>{formatPrice(order.getTotal(),order.getCurrency())}</strong></td>
</tr>
