{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice totals
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="invoice.base", weight="40")
 *}
<ul class="invoice-totals">

  <li>
    <em>{t(#Subtotal#)}:</em>
    {order.getSubtotal():p}
  </li>

  <li FOREACH="order.getSurcharges(),surcharge" class="{surcharge.getType()}">
    <em>{t(surcharge.getName())}:</em>
    {if:surcharge.getAvailable()}
      {formatPrice(surcharge.getValue(),order.getCurrency())}
    {else:}
      {t(#n/a#)}
    {end:}
  </li>

  <li class="grand-total">
    <em>{t(#Grand total#)}:</em>
    {order.getTotal():p}
  </li>

</ul>
