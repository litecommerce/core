{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart item : subtotal
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="cart.item", weight="60")
 *}
<td class="item-subtotal">
  <span class="subtotal{if:item.getExcludeSurcharges()} modified-subtotal{end:}">{formatPrice(item.getTotal(),cart.getCurrency())}</span>
  <div IF="item.getExcludeSurcharges()" class="including-modifiers" style="display: none;">
    <table class="including-modifiers" cellspacing="0">
      <tr FOREACH="item.getExcludeSurcharges(),surcharge">
        <td class="name">{t(#Including X#,_ARRAY_(#name#^surcharge.getName()))}:</td>
        <td class="value">{formatPrice(surcharge.getValue(),cart.getCurrency())}</td>
      </tr>
    </table>
  </div>
  {displayViewListContent(#cart.item.actions#,_ARRAY_(#item#^item))}
</td>
