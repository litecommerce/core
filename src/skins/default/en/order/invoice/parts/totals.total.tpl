{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice totals : total
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.8
 * @ListChild (list="invoice.base.totals", weight="300")
 *}
<tr class="total">
  <td class="title">{t(#Grand total#)}:</td>
  <td class="value">{formatPrice(order.getTotal(),order.getCurrency())}</td>
</tr>
