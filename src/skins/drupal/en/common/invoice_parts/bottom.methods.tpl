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
  <strong>Payment method:</strong>
  {order.paymentMethod.name:h}
</td>

<td class="shipping">
  <strong>Shipping method:</strong>
  {if:order.shippingMethod}{order.shippingMethod.name:h}{else:}n/a{end:}
</td>

