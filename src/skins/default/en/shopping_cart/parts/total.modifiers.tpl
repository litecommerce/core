{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart modifiers
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="cart.panel.totals", weight="20")
 *}

<li FOREACH="getSurchargeTotals(),sType,surcharge" class="{getSurchargeClassName(sType,surcharge)}">
  {if:surcharge.count=#1#}
    <strong>{surcharge.lastName}:</strong>
  {else:}
    <strong class="list-owner">{surcharge.name}:</strong>
  {end:}
  {if:surcharge.available}
    <span class="value">{formatSurcharge(surcharge):h}</span>
  {else:}
    <span>{t(#n/a#)}</span>
  {end:}
  {if:surcharge.count=#1#}
    {displayNestedViewListContent(#modifier#,_ARRAY_(#surcharge#^surcharge,#sType#^sType,#cart#^cart))}
  {else:}
    <div style="display: none;" class="order-modifier-details">
      <ul>
        <li FOREACH="getExcludeSurchargesByType(sType),row">
          <span class="name">{row.getName()}:</span>
          <span class="value">{formatPrice(row.getValue(),cart.getCurrency()):h}</span>
        </li>
      </ul>
    </div>
  {end:}
</li>
