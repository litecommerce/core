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
<table width="100%" border=0 cellpadding=5 cellspacing=0>
<tr IF="xlite.session.ups_failed_items&cart.shippingMethod.class=#ups#">
	<td {if:cart.shippingAvailable&cart.shipped&cart.getCarrierRates()}colspan=3{else:}colspan=2{end:} class="ErrorMessage">United Parcel Service carrier is unavailable. One or more items added to cart exceed the size or the weight limit of the container. Please contact the <a href="mailto: {config.Company.site_administrator:h}"><font class="ErrorMessage"><u>store administrator.</u></font></a></td>
</tr>
<tr IF="xlite.session.ups_rates_error&cart.shippingMethod.class=#ups#">
	<td {if:cart.shippingAvailable&cart.shipped&cart.getCarrierRates()}colspan=3{else:}colspan=2{end:} class="ErrorMessage">United Parcel Service return error: ({xlite.session.ups_rates_error})<br>Please contact the <a href="mailto: {config.Company.site_administrator:h}"><font class="ErrorMessage"><u>store administrator.</u></font></a></td>
</tr>
<tr valign="top">
    <td width="80%">&nbsp;</td>
    <td IF="cart.shippingAvailable&cart.shipped&cart.getCarriers()" align="right" nowrap>
        <b>Select a carrier:&nbsp;&nbsp;</b>
        <select name="carrier" onChange="cart_form.submit()">
        <option FOREACH="cart.getCarriers(),key,carrier" value="{key}" selected="{cart.isSelected(#carrier#,key)}">{carrier:h}</option>
        </select>
        &nbsp;&nbsp;
    </td>
    <td IF="cart.shippingAvailable&cart.shipped&cart.getCarrierRates()" align="left" nowrap>
        <b>Delivery:&nbsp;&nbsp;</b>
        <div FOREACH="cart.getCarrierRates(),key,rate">
        <input type="radio" name="shipping" onClick="cart_form.submit()" value="{rate.shipping.shipping_id}" checked="{cart.isSelected(#shipping_id#,key)}">{if:cart.isSelected(#shipping_id#,key)}<b>{rate.shipping.name:h} ({price_format(rate,#rate#):h})</b>{else:}{rate.shipping.name:h} ({price_format(rate,#rate#):h}){end:}<br/>
        </div>
    </td>
</tr>
</table>
</p>

<widget template="modules/CDev/UPSOnlineTools/notice.tpl" IF="cart.shippingMethod.class=#ups#"/>
