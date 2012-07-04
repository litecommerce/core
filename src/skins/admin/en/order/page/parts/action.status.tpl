{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order status selector
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.24
 *
 * @ListChild (list="order.actions", weight="100")
 *}

<div class="status">
  <widget class="\XLite\View\FormField\Select\OrderStatus" label="Status" fieldName="status" value="{order.getStatus()}" orderId="{order.getOrderId()}" />
</div>
