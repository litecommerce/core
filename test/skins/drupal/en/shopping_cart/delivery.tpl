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
<div IF="cart.shippingAvailable&cart.shipped&cart.getShippingRates()" class="delivery-box">
  <h4>Delivery</h4>

  <widget module="UPSOnlineTools" template="modules/UPSOnlineTools/delivery.tpl">

  <ul IF="!xlite.UPSOnlineToolsEnabled" class="deliveries">
    {foreach:cart.getShippingRates(),rate}
    <li {if:cart.shipping_id=rate.shipping.shipping_id} class="selected"{end:}>
      <input type="radio" id="shipping_{rate.shipping.shipping_id}" name="shipping" value="{rate.shipping.shipping_id}" checked="{cart.isSelected(#shipping_id#,rate.shipping.shipping_id)}" />
      <label for="shipping_{rate.shipping.shipping_id}">{rate.shipping.name:h}</label>
      <span>{price_format(rate,#rate#):h}</span>
    </li>
    {end:}
  </ul>
</div>
