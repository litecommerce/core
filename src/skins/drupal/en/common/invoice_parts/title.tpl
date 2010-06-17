{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Invoice title
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="invoice.base", weight="20")
 *}
<h2 class="invoice">Invoice #{order.order_id}</h2>
<div class="invoice-title">
{time_format(order.date)}
<span>Grand total: {price_format(order,#total#):h}</span>
</div>
