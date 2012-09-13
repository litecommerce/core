{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice payment methods
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="invoice.bottom.methods", weight="20")
 *}
<td class="payment" IF="order.getVisiblePaymentMethods()">
  <strong>{t(#Payment method#)}:</strong>
  {foreach:order.getVisiblePaymentMethods(),m}
    {m.getTitle():h}<br />
  {end:}
</td>
