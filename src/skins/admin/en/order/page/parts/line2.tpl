{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order : line 2
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="order.operations", weight="200")
 *}

<div class="line-2">

  <div class="payment" IF="order.getVisiblePaymentMethods()">
    <h2>{t(#Payment info#)}</h2>
    <div class="box"><list name="order.payment" /></div>
  </div>

  <div class="shipping" IF="getShippingModifier()&shippingModifier.getMethod()">
    <h2>{t(#Shipping info#)}</h2>
    <div class="box"><list name="order.shipping" /></div>
  </div>

  <div class="clear"></div>
</div>
