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
  <widget class="XLite_View_Form_Cart_Main" name="shopping_form" />

  <h4>Delivery</h4>

  <widget module="UPSOnlineTools" template="modules/UPSOnlineTools/delivery.tpl">

  <ul IF="!xlite.UPSOnlineToolsEnabled" class="deliveries">
    <li FOREACH="cart.getShippingRates(),key,rate">
      <input type="radio" id="shipping_{rate.shipping.shipping_id}" name="shipping" onclick="javascript: this.form.submit();" value="{rate.shipping.shipping_id}" checked="{cart.isSelected(#shipping_id#,key)}" />
      <label for="shipping_{rate.shipping.shipping_id}"{if:cart.isSelected(#shipping_id#,key)} class="selected"{end:}>{rate.shipping.name:h} ({price_format(rate,#rate#):h})</label>
    </li>
  </ul>

  <widget name="shopping_form" end />
</div>
