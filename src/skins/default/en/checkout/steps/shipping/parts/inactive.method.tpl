{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : shipping step : inactive state : shipping method
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="checkout.shipping.inactive", weight="20")
 *}
<div class="secondary" IF="isShippingAvailable()">
  <div class="label">{t(#Shipping method#)}:</div>
  {modifier.method.name}
  <span class="price">{formatPrice(getMarkup(modifier.selectedRate),cart.getCurrency())}</span>
</div>
