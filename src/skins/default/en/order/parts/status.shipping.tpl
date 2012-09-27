{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order shipping status
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="order.status", weight="10")
 *}
<div class="shipping order-status-{order.getStatus()}"><widget template="common/order_status.tpl" order="{getOrder()}" /></div>
