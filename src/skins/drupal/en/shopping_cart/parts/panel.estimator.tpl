{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart Shipping estimator panel
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="cart.panel", weight="20")
 *}
<div class="estimator">
  {if:cart.getShippingMethod()&cart.profile}
    <ul>
      <li><span>{t(#Shipping#)}:</span> {cart.shippingMethod.getName():h}</li>
      <li><span>{t(#Estimated for#)}:</span> {cart.profile.shippingCountry.getCountry()} {cart.profile.shippingState.getState()}, {cart.profile.shipping_zipcode}</li>
    </ul>
    <a href="{buildUrl(#estimate_shipping#)}">{t(#Change method#)}</a>
  {else:}
    <div class="buttons">
      <widget class="\XLite\View\Button\Link" label="Estimate shipping cost" location="{buildUrl(#estimate_shipping#)}" style="action estimate" />
    </div>
  {end:}
</div>
