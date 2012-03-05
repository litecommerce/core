{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice totals : modifiers
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.8
 *
 * @ListChild (list="invoice.base.totals", weight="200")
 *}

<tr FOREACH="getSurchargeTotals(),sType,surcharge" class="{getSurchargeClassName(sType,surcharge)}">
  {if:surcharge.count=#1#}
    <td class="title">
      {surcharge.lastName}:
      <list name="invoice.base.totals.modifier.name" surcharge="{surcharge}" sType="{sType}" order="{order}" />
    </td>
  {else:}
    <td class="title list-owner">
      {surcharge.name}:
      <list name="invoice.base.totals.modifier.name" surcharge="{surcharge}" sType="{sType}" order="{order}" />
    </td>
  {end:}
  <td class="value">
    {if:surcharge.available}
      {formatSurcharge(surcharge):h}
    {else:}
      {t(#n/a#)}
    {end:}
    <list name="invoice.base.totals.modifier.value" surcharge="{surcharge}" sType="{sType}" order="{order}" />
  </td>
</tr>
