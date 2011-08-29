{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice totals
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="invoice.base", weight="40")
 *}
<table cellspacing="0" class="totals">

  <tr>
    <td class="title">{t(#Subtotal#)}:</td>
    <td class="value">{formatPrice(order.getSubtotal(),order.getCurrency())}</td>
  </tr>

  <tr FOREACH="order.getSurchargeTotals(),type,surcharge" class="{type}-modifier">
    {if:surcharge.count=#1#}
      <td class="title">{surcharge.lastName}:</td>
    {else:}
      <td class="title list-owner">{surcharge.name}:</td>
    {end:}
    {if:surcharge.available}
      <td class="value">{formatPrice(surcharge.cost,order.getCurrency()):h}</td>
    {else:}
      <td class="value">{t(#n/a#)}</td>
    {end:}
  </tr>

  <tr class="total">
    <td class="title">{t(#Grand total#)}:</td>
    <td class="value">{formatPrice(order.getTotal(),order.getCurrency())}</td>
  </tr>

</table>
