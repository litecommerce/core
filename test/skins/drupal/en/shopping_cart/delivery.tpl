{* SVN $Id$ *}
<p IF="cart.shippingAvailable&cart.shipped&cart.getShippingRates()" align="right">

  <widget module="UPSOnlineTools" template="modules/UPSOnlineTools/delivery.tpl">

  <span IF="!xlite.UPSOnlineToolsEnabled">

    <strong>Delivery:&nbsp;&nbsp;</strong>
    <select name="shipping" onchange="javascript: cart_form.submit();">
      <option FOREACH="cart.getShippingRates(),key,rate" value="{rate.shipping.shipping_id}" selected="{cart.isSelected(#shipping_id#,key)}">{rate.shipping.name:h} {price_format(rate,#rate#):h}</option>
    </select>

  </span>
</p>
