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
<table border="0">
<tr>
<td width="50%">
	<font class=AdminHead>International rates test</font>
	<form action="admin.php" method="GET">
	<input type="hidden" name="target" value="usps">
	<input type="hidden" name="action" value="int_test">
	Ounces: <input type="text" name="ounces" size="10" value="{ounces:r}"><br>
	Country name to ship to: <input type="text" name="destinationCountry" size="30" value="{destinationCountry:r}"><br>
	<input type="submit" value=" Run test ">
	</form>
</td><td width="30">
</td><td>
	<font class=AdminHead>Domestic rates test</font>
	<form action="admin.php" method="GET">
	<input type="hidden" name="target" value="usps">
	<input type="hidden" name="action" value="nat_test">
	Ounces: <input type="text" name="ounces" size="10" value="{ounces:r}"><br>
	Destination zip code: <input type="text" name="ZipDestination" size="10" value="{ZipDestination:r}"><br>
	<input type="submit" value=" Run test ">
	</form>
</td>
</td></table>

{if:testResult}
<span class="ErrorMessage" IF="usps.error">
	{usps.error:h}
</span>
<p>
<span IF="usps.xmlError">
	<pre>{usps.response:h}</pre>
</span>
<p>
Rates:
<table border="1" cellspacing="0">
<tr><th>Shipping #</th><th>Shipping Method</th><th>Rate</th></tr>
<tr FOREACH="rates,id,rate">
<td>{id}</td><td>{rate.shipping.name}</td><td>{price_format(rate.rate):h}</td></tr>
</table>
{end:}
