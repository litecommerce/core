{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : order review step : items : subtotal
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="checkout.review.selected.items", weight="30")
 * @ListChild (list="checkout.review.inactive.items", weight="30")
 *}

<ul class="modifiers">

  <li FOREACH="getSurchargeTotals(),sType,surcharge" class="{getSurchargeClassName(sType,surcharge)}">
    {if:surcharge.count=#1#}
      <span class="name">{surcharge.lastName}:</span>
    {else:}
      <span class="name list-owner">{surcharge.name}:</span>
    {end:}
    {if:surcharge.available}
      <span class="value"><widget class="XLite\View\Surcharge" surcharge="{formatSurcharge(surcharge)}" currency="{cart.getCurrency()}" /></span>
    {else:}
      <span class="value">{t(#n/a#)}</span>
    {end:}
    {if:surcharge.count=#1#}
      <list name="modifier" type="nested" surcharge="{surcharge}" sType="{sType}" cart="{cart}" />
    {else:}
      <div style="display: none;" class="order-modifier-details">
        <ul>
          <li FOREACH="getExcludeSurchargesByType(sType),row">
            <span class="name">{row.getName()}:</span>
            <span class="value"><widget class="XLite\View\Surcharge" surcharge="{row.getValue()}" currency="{cart.getCurrency()}" /></span>
          </li>
        </ul>
      </div>
    {end:}
  </li>

</ul>
