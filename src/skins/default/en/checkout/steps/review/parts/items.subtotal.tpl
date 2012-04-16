{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : order review step : items : subtotal
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="checkout.review.selected.items", weight="10")
 * @ListChild (list="checkout.review.inactive.items", weight="10")
 *}
<div class="items-row clearfix">
  {t(#_X items_ in bag#,_ARRAY_(#count#^cart.countQuantity())):h}
  <span class="price{if:cart.getItemsIncludeSurchargesTotals()} modified-subtotal{end:}"><widget class="XLite\View\Surcharge" surcharge="{cart.getSubtotal()}" currency="{cart.getCurrency()}" /></span>
  <div IF="cart.getItemsIncludeSurchargesTotals()" class="including-modifiers" style="display: none;">
    <table class="including-modifiers" cellspacing="0">
      <tr FOREACH="cart.getItemsIncludeSurchargesTotals(),row">
        <td class="name">{t(#Including X#,_ARRAY_(#name#^row.surcharge.getName()))}:</td>
        <td class="value"><widget class="XLite\View\Surcharge" surcharge="{row.cost}" currency="{cart.getCurrency()}" /></td>
      </tr>
    </table>
  </div>
</div>
