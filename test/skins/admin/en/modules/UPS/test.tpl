<form action="admin.php#test_" method="GET">
	<input type="hidden" name="target" value="ups">
	<input type="hidden" name="action" value="test">
	Pounds: <input type="text" name="pounds" size="10" value="{pounds:r}"><br>
	Destination country code: <input type="text" name="destinationCountry" size="3" value="{destinationCountry:r}"><br>
	Destination postal code: <input type="text" name="destinationZipCode" size="10" value="{destinationZipCode:r}"><br>
	<input type="submit" value=" Run test ">
</form>
{if:testResult}
<span class="ErrorMessage" IF="ups.error">
	{ups.error:h}
</span>
<p>
<span IF="ups.xmlError">
	<pre>{ups.response:h}</pre>
</span>
<p>
Rates:
<table border="1" cellspacing="0">
<tr><th>Shipping #</th><th>Shipping Method</th><th>Rate</th></tr>
<tr FOREACH="rates,id,rate">
<td>{id}</td><td>{rate.shipping.name}</td><td>{price_format(rate.rate):h}</td></tr>
</table>
<a name="test_">
{end:}
