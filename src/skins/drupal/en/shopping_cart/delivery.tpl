{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shopping cart delivery block
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div IF="cart.isDeliveryAvailable()" class="delivery-box">
  <h4>{t(#Delivery#)}</h4>

  <widget module="UPSOnlineTools" template="modules/UPSOnlineTools/delivery.tpl" />

  <ul IF="!xlite.UPSOnlineToolsEnabled" class="deliveries">
    {foreach:cart.getShippingRates(),rate}
    <li {if:cart.getShippingId()=rate.getMethodId()} class="selected"{end:}>
      <input type="radio" id="shipping_{rate.getMethodId()}" name="shipping" value="{rate.getMethodId()}" checked="{isSelected(cart.getShippingId(),rate.getMethodId())}" />
      <label for="shipping_{rate.getMethodId()}">{rate.getMethodName():h}</label>
      <span>{rate.getTotalRate():p}</span>
    </li>
    {end:}
  </ul>
</div>
