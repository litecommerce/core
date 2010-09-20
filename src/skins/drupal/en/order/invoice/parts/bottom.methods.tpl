{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice payment and shipping methods
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="invoice.bottom", weight="20")
 *}
<td class="payment">
  <strong>{t(#Payment method#)}:</strong>
  {foreach:order.getActivePaymentTransactions(),t}
    {t.paymentMethod.getName():h}<br />
  {end:}
</td>

<td class="shipping">
  <strong>{t(#Shipping method#)}:</strong>
  {if:order.getShippingMethod()}{order.shippingMethod.name:h}{else:}n/a{end:}
</td>

