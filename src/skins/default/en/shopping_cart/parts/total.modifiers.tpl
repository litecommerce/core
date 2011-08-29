{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart modifiers
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="cart.panel.totals", weight="20")
 *}
<li FOREACH="cart.getSurchargeTotals(),type,surcharge" class="order-modifier {type}-modifier">
  {if:surcharge.count=#1#}
    <strong>{surcharge.lastName}:</strong>
  {else:}
    <strong class="list-owner">{surcharge.name}:</strong>
  {end:}
  {if:surcharge.available}
    {formatPrice(surcharge.cost,cart.getCurrency()):h}
  {else:}
    {t(#n/a#)}
  {end:}
  <div IF="surcharge.count=#1#" style="display: none;" class="order-modifier-details">
    <ul>
      <li FOREACH="cart.getExcludeSurchargesByType(type),row">
        <span class="name">{row.getName()}:</span>
        <span class="value">{formatPrice(row.getValue(),cart.getCurrency()):h}</span>
      </li>
    </ul>
  </div>
</li>
