{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : shipping step : inactive state : shipping method
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="checkout.shipping.inactive", weight="20")
 *}
<div class="secondary" IF="isShippingAvailable()">
  <div class="label">{t(#Shipping method#)}:</div>
  {modifier.method.name:h}
  <span class="price"><widget class="XLite\View\Surcharge" surcharge="{getTotalRate(modifier.selectedRate)}" currency="{cart.getCurrency()}" /></span>
</div>
