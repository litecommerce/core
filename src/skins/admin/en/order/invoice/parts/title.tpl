{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice title
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="invoice.base", weight="20")
 *}
<h2 class="invoice">Invoice #{order.getOrderId()}</h2>
<div class="subhead">
  {formatTime(order.getDate())}
  <span>{t(#Grand total#)}: {formatPrice(order.getTotal(),order.getCurrency()):h}</span>
</div>
