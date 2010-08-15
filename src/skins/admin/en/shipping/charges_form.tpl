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
<table border="0" width="100%">
	<tr class="TableHead" IF="shippingRates">
		<th valign="top" width="15">#</th>
		<th valign="top" width="40%" align=left>Shipping methods</th>
		<th valign="top" width="30%" align=left>Range</th>
		<th valign="top" width="30%" align=left colspan="2">Shipping charges</th>
	</tr>
	<form name="rates" action="admin.php" method="post">
	<input type="hidden" name="target" value="shipping_rates">
	<input type="hidden" name="action" value="update">
	<input type="hidden" name="shipping_zone_range" value="{shipping_zone_range}">
	<input type="hidden" name="shipping_id_range" value="{shipping_id_range}">
	<input type="hidden" name="deleted_rate">
	<tbody IF="!shippingRates">
		<tr>
			<td colspan="4"><b>You have no shipping rates</b></td>
		</tr>
	</tbody>
	<tbody FOREACH="shippingRates,k,rate">
		<tr>
			<td valign="top" width="15"><b>{rate.pos}.</b></td>
			<td width="30%">&nbsp;<br>
			<select name="rate[{k}][shipping_id]">
			<option value="-1">All shipping methods</option>
			<option FOREACH="shippings,shipping" value="{shipping.shipping_id}" selected="{rate.shipping_id=shipping.shipping_id}">{shipping.name:h}</option>
			</select>
			</td>
			<td width="30%">Weight<br><input name="rate[{k}][min_weight]" size="9" value="{rate.min_weight}">&nbsp;-&nbsp;<input name="rate[{k}][max_weight]" size="9" value="{rate.max_weight}"></td>
			<td width="15%">Flat<br><input name="rate[{k}][flat]" size="5" value="{rate.flat}"></td>
            <td width="15%" nowrap>% of subtotal<br><input name="rate[{k}][percent]" size="5" value="{rate.percent}"></td>
		</tr>
		<tr>
			<td valign="top" width="15"></td>
			<td>
				<select name="rate[{k}][shipping_zone]">
				<option value="-1">All shipping zones</option>
				<option FOREACH="getShippingZones(),zone" value="{zone.getZoneId()}" selected="{rate.shipping_zone=zone.getZoneId()}">{zone.getZoneName()}</option>
				</select>
			</td>
			<td>Order total<br><input name="rate[{k}][min_total]" size="9" value="{rate.min_total}">&nbsp;-&nbsp;<input name="rate[{k}][max_total]" size="9" value="{rate.max_total}"></td>
			<td>Per item<br><input name="rate[{k}][per_item]" size="5" value="{rate.per_item}"></td>
            <td>Per {config.General.weight_unit}<br><input name="rate[{k}][per_lbs]" size="5" value="{rate.per_lbs}"></td>
		</tr>
	<tr>
		<td width="15"></td>
		<td></td>
		<td>Items<br><input name="rate[{k}][min_items]" size="9" value="{rate.min_items}">&nbsp;-&nbsp;<input name="rate[{k}][max_items]" size="9" value="{rate.max_items}"></td>
		<td>&nbsp;</td>
        <td>&nbsp;</td>
	</tr>

		<tr>
		<td width="15"></td>
			<td colspan="4" align="right"><input type="button" value="Delete" onClick="rates.deleted_rate.value={k};rates.action.value='delete';rates.submit()"></td>
		</tr>	
	<tr>
		<td colspan="5"><hr></td>
	</tr>

	</tbody>
	<tr IF="shippingRates">
		<td colspan=3>
		    <input type="submit" value=" Update " class="DialogMainButton">
		</td>
	</tr>	
	</form>
	<tr>
		<td>&nbsp;</td>
	</tr>	
	<tr>
		<td class=AdminTitle colspan="5">Add shipping charge values</td>
	</tr>
	<form name="add_shipping_charges" action="admin.php" method="POST">
	<input type="hidden" name="target" value="shipping_rates">
	<input type="hidden" name="action" value="add">
	<input type="hidden" name="shipping_zone_range" value="{shipping_zone_range}">
	<input type="hidden" name="shipping_id_range" value="{shipping_id_range}">
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;<br>
		<select name="shipping_id">
		<option value="-1">All shipping methods</option>
		<option FOREACH="shippings,shipping" value="{shipping.shipping_id}">{shipping.name:h}</option>
		</select>
		</td>
		<td>Weight<br><input name="min_weight" size="9" value="0">&nbsp;-&nbsp;<input name="max_weight" size="9" value="999999"></td>
		<td>Flat<br><input name="flat" size="5" value="0.00"></td>
		<td>% of subtotal<br><input name="percent" size="5" value="0.00"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<select name="shipping_zone">
			<option value="-1">All shipping zones</option>
			<option FOREACH="getShippingZones(),zone" value="{zone.getZoneId()}">{zone.getZoneName()}</option>
			</select>
		</td>
		<td>Order total<br><input name="min_total" size="9" value="0">&nbsp;-&nbsp;<input name="max_total" size="9" value="999999"></td>
		<td>Per item<br><input name="per_item" size="5" value="0.00"></td>
		<td>Per {config.General.weight_unit}<br><input name="per_lbs" size="5" value="0.00"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td></td>
		<td>Items<br><input name="min_items" size="9" value="0">&nbsp;-&nbsp;<input name="max_items" size="9" value="999999"></td>
		<td></td>
	</tr>
	<tr>
		<td align="right" colspan="5"><input type="submit" value=" Add new "></td>
	</tr>
	</form>
</table>
