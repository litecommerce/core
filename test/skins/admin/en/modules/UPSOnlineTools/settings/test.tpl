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
<a name="test_"></a>
<span IF="useDynamicStates"><widget template="js/select_states_begin_js.tpl"></span>
<form action="admin.php#test_" method="POST" name="shipping_test">
    <input type="hidden" name="target" value="{target}">
    <input type="hidden" name="action" value="test">

<table width="100%" cellspacing="0" cellpadding="5" border="0">
<tr valign="top">
    <td>
        <b>Original address:</b><hr>
        <table width="100%">
		<tr><td>Address</td><td>{config.Company.location_address}</td></tr>
        <tr><td>City</td><td>{config.Company.location_city}</td></tr>
		<tr><td>State</td><td>{config.Company.locationState.state}</td></tr>
        <tr><td>Country</td><td>{config.Company.location_country}</td></tr>
        <tr><td>Zip/Postal code</td><td>{config.Company.location_zipcode}</td></tr>
        </table>
    </td>
    <td>
        <b>Destination address:</b><hr>
        <table width="100%">
        <tr><td>City</td><td><input type="text" name="destinationCity" value="{destinationCity}"></td></tr>
        <tr>
            <td>State</td>
            <td>
			    <widget class="XLite_View_StateSelect" field="destinationState" onChange="{if:useDynamicStates}javascript: changeState(this, 'destination');{end:}" fieldId="destination_state_select" value="{destinationState}">
  			    <widget class="XLite_Validator_StateValidator" field="destinationState" countryField="destinationCountry">
            </td>
        </tr>
        <tr valign="middle" id="destination_custom_state_body">
            <td>Other state (specify)</td>
            <td><input type="text" name="destination_custom_state" value="{destination_custom_state:r}" size="32" maxlength="64"><td>
        </tr>
        <tr>
            <td>Country</td>
            <td><widget class="XLite_View_CountrySelect" field="destinationCountry" onChange="{if:useDynamicStates}javascript: populateStates(this,'destination_state');{end:}" fieldId="destination_country_select" value="{destinationCountry}"></td>
        </tr>
        <tr><td>Zip/Postal code</td><td><input type="text" name="destinationZipCode" value="{destinationZipCode}"></td></tr>
        <tr><td>Weight ({weightUnit:h})</td><td><input type="text" name="pounds" size="10" value="{pounds:r}"></td></tr>
        </table>
    </td>
</tr>
<tr>
	<td colspan="2"><hr></td>
</tr>
<tr>
	<td>&nbsp;</td>
    <td align="right">
        <input type="submit" class="DialogMainButton" value=" Run test ">
    </td>
</tr>
</table>
</form>
{if:useDynamicStates}
<script type="text/javascript" language="JavaScript 1.2">

    function initCountries()
	{
        var elm = document.getElementById('destination_country_select');
        if (elm) populateStates(elm,"destination_state",true);
	}
	function initStates()
	{
        elm = document.getElementById('destination_state_select');
	    if (elm) changeState(elm, "destination");
	}
	initCountries();
	initStates();
</script>
{end:}
{if:testResult}
<span class="ErrorMessage" IF="ups.error">
    {ups.error:h}
</span>
<p>
<span IF="ups.xmlError">
    <pre>{ups.response:h}</pre>
</span>
<p>
<b>Shipping Rates:</b>
<span IF="rates">
<table border="1" cellspacing="0">
<tr><th>Shipping #</th><th>Shipping Method</th><th>Rate</th></tr>
<tr FOREACH="rates,id,rate">
<td>{id}</td><td>{rate.shipping.nameUPS:h}</td><td>{price_format(rate.rate):h}</td></tr>
</table>
</span>
<span IF="!rates">
No shipping rates
</span>
{end:}
