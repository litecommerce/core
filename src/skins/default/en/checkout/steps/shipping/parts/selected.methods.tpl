{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : shipping step : selected state : methods
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="checkout.shipping.selected", weight="20")
 *}
<div class="secondary" IF="isShippingAvailable()">
  <h3>{t(#Delivery methods#)}</h3>
  <widget class="\XLite\View\Checkout\ShippingMethodsList" />
</div>
