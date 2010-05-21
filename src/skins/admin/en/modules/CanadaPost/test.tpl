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
<form action="admin.php#test_" method="GET">
    <input type="hidden" name="target" value="cps">
    <input type="hidden" name="action" value="test">
	<table border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td>Shipping weight (kg):</td>
			<td><input type="text" name="weight" size="10" value="{weight:r}"></td>
		</tr>
		<tr>
			<td colspan="2"><b>Destination</b></td>
		</tr>
        <tr>
            <td>City:</td>
            <td><input type="text" name="destinationCity" size="10" value="{destinationCity:r}"></td>
        </tr>
        <tr>
            <td>Province/State:</td>
            <td><widget class="XLite_View_StateSelect" field="destinationState"></td>
        </tr>
        <tr>
            <td>Country:</td> 
            <td><widget class="XLite_View_CountrySelect" field="destinationCountry"></td>
        </tr>
        <tr>
            <td>Postal/ZIP Code:</td>
            <td><input type="text" name="destinationZipcode" size="10" value="{destinationZipcode:r}"></td>
        </tr>
	</table>
    <input type="submit" value=" Calculate rates ">
</form>
<br>
{if:testResult}
<span class="ErrorMessage" IF="cps.error">
    Error #{cps.error:h}
<p>
</span>
<span IF="cps.xmlError">
    <table border=0 cellspacing=1 cellpading=1>
    <tr>
    	<td><b>Response:</b></td>
    </tr>
    <tr>
    	<td style="font-family: Courier; FONT-SIZE: 10px">{cps.response}</td>
    </tr>
    </table>
<p>
</span>
<p IF="rates">
<b>Rates:</b>
<table border="1" cellspacing="0">
<tr><th>Shipping Method</th><th>Rate</th></tr>
<tr FOREACH="rates,id,rate">
<td>{rate.shipping.name}</td><td>{price_format(rate.rate):h}</td></tr>
</table>
<a name="test_">
</p>
{end:}
<b>Notes:</b><br>
- For destinations in Canada 'Country' ,'City' and 'Postal Code' have to be valid ('State' is not used).
<br>
- For destinations in US, 'Country' and 'State' have to be valid. ('City', 'Postal Code' are not used).                            
<br>
- For international destinations only 'Country' has to be valid ('City', 'Postal Code' and 'State' are not used).
</p>
