{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<p IF="cart.isShippingAvailable()&cart.shipped&cart.getShippingRates()" align="right">

  <widget module="CDev\UPSOnlineTools" template="modules/CDev/UPSOnlineTools/delivery.tpl">

  <span IF="!xlite.UPSOnlineToolsEnabled">

    <strong>Delivery:&nbsp;&nbsp;</strong>
    <select name="shipping" onchange="javascript: cart_form.submit();">
      <option FOREACH="cart.getShippingRates(),key,rate" value="{rate.shipping.shipping_id}" selected="{cart.isSelected(#shipping_id#,key)}">{rate.shipping.name:h} {price_format(rate,#rate#):h}</option>
    </select>

  </span>
</p>
