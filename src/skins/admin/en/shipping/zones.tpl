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
<script language="Javascript">
function visibleBox(id, status)
{
	    var Element = document.getElementById(id);
	    if (Element) {
	        Element.style.display = ((status) ? "" : "none");
	    }
}
function ShowNotes()
{
	    visibleBox("notes_url", false);
	    visibleBox("notes_body", true);
}
</script>
Use this section to define shipping zones.
<span id="notes_url" style="display:"><a href="javascript:ShowNotes();" class="NavigationPath" onClick="this.blur()"><b>How to define shipping zones &gt;&gt;&gt;</b></a></span>
<span id="notes_body" style="display: none"><br><br>
Select a country or a state from a list, specify the zone where the country or state should be listed and click on the 'Apply' button. To select more than one country/state, hold down the CTRL key while making a selection. A zone can contain either countries or states. You cannot include both states and countries into the same zone.  
</span>

<hr>

<table border=0 cellpadding=5>
<tbody FOREACH="xlite.factory.XLite_Model_ShippingZone.findAll(),zone">
<form action="admin.php" method="POST" name="shipping_zone_{zone.shipping_zone}">
<input type="hidden" name="target" value="shipping_zones">
<input type="hidden" name="action" value="">
<input type="hidden" name="shipping_zone" value="{zone.shipping_zone}">
<tr>
    <td class=AdminHead colspan=2>
    {zone.name}
    <span IF="zone.hasCountries()"> (countries)</span>
    <span IF="zone.hasStates()"> (states)</span>
    </td>
</tr>
<tr>
<td valign=top IF="zone.hasCountries()">
	<select name="countries[]" multiple size=20 style="width:140pt">
		<option FOREACH="zone.countries,country" value="{country.code}">{country.country}</option>
	</select>
</td>
<td valign=top IF="zone.hasStates()">
	<select name="states[]" multiple size=20 style="width:140pt">
		<option FOREACH="zone.states,state" value="{state.state_id}">{if:state.country_code}{state.country_code}/{end:}{state.state}</option>
	</select>
</td>
</tr>
<tr>
<td IF="zone.hasCountries()">
Move selected countries to:<br>
<select name="target_country_zone">
<option value="new">New zone</option>
<option FOREACH="xlite.factory.XLite_Model_ShippingZone.findCountryZones(),z" value="{z.shipping_zone}">{z.name}</option>
</select><br>
<input type=button value=" Apply " onClick="shipping_zone_{zone.shipping_zone}.action.value='update_countries';shipping_zone_{zone.shipping_zone}.submit()">
</td>
<td IF="zone.hasStates()">
Move selected states to:<br>
<select name="target_state_zone">
<option value="new">New zone</option>
<option FOREACH="xlite.factory.XLite_Model_ShippingZone.findStateZones(),z" value="{z.shipping_zone}">{z.name}</option>
</select><br>
<input type=button value=" Apply " onClick="shipping_zone_{zone.shipping_zone}.action.value='update_states';shipping_zone_{zone.shipping_zone}.submit()">
</td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
</form>
</tbody>
</table>
