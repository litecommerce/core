{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment method row
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div IF="method.processor.getIconPath(order,method)" class="icon">
  <img src="{preparePaymentMethodIcon(method.processor.getIconPath(order,method))}" alt="" />
</div>
<span class="payment-title">{method.getTitle()}</span>
<div IF="{method.getDescription()}" class="payment-description">{method.getDescription()}</div>
