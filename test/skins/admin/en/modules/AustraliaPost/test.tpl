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
    <input type="hidden" name="target" value="aupost">
    <input type="hidden" name="action" value="test">
	<table border="0" cellpadding="3" cellspacing="0">
		<tr>
			<td>Shipping weight:</td>
			<td IF="!weight=##"><input type="text" name="weight" size="10" value="{weight:r}"></td>
			<td IF="weight=##"><input type="text" name="weight" size="10" value="100"></td>
		</tr>
		<tr>
			<td>Weight unit:</td>
			<td>
            <select name="weight_unit">
                <option value="g" selected="weight_unit=#g#">G</option>
                <option value="kg" selected="weight_unit=#kg#">KG</option>
                <option value="lbs" selected="weight_unit=#lbs#">LB</option>
                <option value="oz" selected="weight_unit=#oz#">OZ</option>
            </select>
			</td>
		</tr>
		<tr>
			<td colspan="2"><b>Source</b></td>
		</tr>
        <tr>
            <td>Country:</td> 
            <td>
            <input type="text" size="15" value="Australia" disabled>
            <span IF="!config.Company.location_country=#AU#"><font class="Star">(!)</font> <a href="admin.php?target=settings&page=Company"><u>Company country</u></a> has wrong value</span>
            </td>
        </tr>
        <tr>
            <td>Postal Code:</td>
            <td IF="!sourceZipcode=##"><input type="text" name="sourceZipcode" size="10" value="{sourceZipcode:r}"></td>
            <td IF="sourceZipcode=##"><input type="text" name="sourceZipcode" size="10" value="{config.Company.location_zipcode:r}"></td>
        </tr>
		<tr>
			<td colspan="2"><b>Destination</b></td>
		</tr>
        <tr>
            <td>Country:</td> 
            <td IF="!destinationCountry=##"><widget class="XLite_View_CountrySelect" field="destinationCountry" value="{destinationCountry}"></td>
            <td IF="destinationCountry=##"><widget class="XLite_View_CountrySelect" field="destinationCountry" value="{config.General.default_country}"></td>
        </tr>
        <tr>
            <td>Postal/ZIP Code:</td>
            <td IF="!sourceZipcode=##"><input type="text" name="destinationZipcode" size="10" value="{destinationZipcode:r}"></td>
            <td IF="sourceZipcode=##"><input type="text" name="destinationZipcode" size="10" value="{config.Company.location_zipcode:r}"></td>
        </tr>
	</table>
<br>
<input type="submit" value=" Calculate rates ">
</form>
<br>
<span IF="testResult">
<span class="ErrorMessage" IF="aupost.error">
    Error #{aupost.error:h}
</span>
<p>
<a name="test_results"></a>
<p IF="rates">
<b>Rates:</b>
<table border="1" cellspacing="0">
<tr><th>Shipping Method</th><th>Rate</th></tr>
<tr FOREACH="rates,id,rate">
<td>{rate.shipping.name}</td><td>{price_format(rate.rate):h}</td></tr>
</table>
</p>
<span IF="!rates">
No shipping method is available for specified shipping destination and/or package measurements/weight.
</span>
<SCRIPT language="JavaScript">
document.location = "#test_results";
</SCRIPT>
</span>
