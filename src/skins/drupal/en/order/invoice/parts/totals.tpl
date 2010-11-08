{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice totals
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="invoice.base", weight="40")
 *}
<table cellspacing="0" class="totals">

  <tr>
    <td class="title">{t(#Subtotal#)}:</td>
    <td class="value">{formatPrice(order.getSubtotal(),order.getCurrency())}</td>
  </tr>

  <tr FOREACH="order.getVisibleSavedModifiers(),modifier" class="{modifier.getCode()} {modifier.getSubcode()}">
    <td class="title">{t(modifier.getName())}:</td>
    <td class="value">{if:modifier.isAvailable(modifier.getSubcode())}{formatPrice(modifier.getSurcharge(),order.getCurrency())}{else:}{t(#n/a#)}{end:}
  </tr>

  <tr class="total">
    <td class="title">{t(#Grand total#)}:</td>
    <td class="value">{formatPrice(order.getTotal(),order.getCurrency())}</td>
  </tr>

</table>
