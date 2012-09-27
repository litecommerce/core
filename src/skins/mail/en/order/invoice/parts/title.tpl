{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice title
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="invoice.base", weight="20")
 *}
<h2 class="invoice">{t(#Invoice X#,_ARRAY_(#id#^order.getOrderId()))}</h2>
<div class="subhead">
  {formatTime(order.getDate())}
  <span>{t(#Grand total#)}: {formatPrice(order.getTotal(),order.getCurrency())}</span>
</div>
