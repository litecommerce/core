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
<ul class="invoice-totals">

  <li>
    <em>{t(#Subtotal#)}:</em>
    {cart.getSubtotal():p}
  </li>

  <li FOREACH="cart.getVisibleSavedModifiers(),modifier" class="{modifier.getCode()} {modifier.getSubcode()}">
    <em>{t(modifier.getName())}:</em>
    {if:modifier.isAvailable(modifier.getSubcode())}{modifier.getSurcharge():p}{else:}{t(#n/a#)}{end:}
  </li>

  <li class="grand-total">
    <em>{t(#Grand total#)}:</em>
    {cart.getTotal():p}
  </li>

</ul>
