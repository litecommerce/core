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
<form name="default_currency_form" action="admin.php" method="POST">
<input type="hidden" name="target" value="currencies">
<input type="hidden" name="action" value="update_default">

<table border="0" cellpadding="0" cellspacing="2">
<tr>
    <td class="AdminTitle" colspan="3">Default currency:</td>
</tr>
<tr>
    <td colspan="3">&nbsp;</td>
</tr>
<tr>
    <th class="TableHead">Code</th>
    <th class="TableHead">Name</th>
    <th class="TableHead">Currency format</th>
</tr>
<tr>
    <td><input type="text" size="3" name="currency[code]" value="{defaultCurrency.code:h}"></td>
    <td><input type="text" size="15" name="currency[name]" value="{defaultCurrency.name:h}"></td>
    <td><input type="text" size="15" name="currency[price_format]" value="{defaultCurrency.price_format:h}"></td>
</tr>	
    <td colspan="3"><hr></td>
</table>
<input type="submit" value=" Update ">	
</form>
<br>
<form name="currency_form" action="admin.php" method="POST">
<input type="hidden" name="target" value="currencies">
<input type="hidden" name="action" value="update">
<table cellpadding="0" cellspacing="0">
<tr>
    <td colspan="2" class="AdminTitle">Additional currencies:</td>
</tr>
<tr IF="!allCurrencies">
	<td colspan="2">There are no additional currencies.</td>
</tr>
<tr foreach="allCurrencies,key,currency"> 
<input type="hidden" name="currencies[{key}][currency_id]" value="{currency.currency_id}">
	<td colspan="2">
		<table width="100%" cellpadding="0" cellspacing="2">
			<tr>
			    <td colspan="6">&nbsp;</td>
			</tr>
			<tr class="TableHead">
				<th>Active</th>
				<th>Name</th>
		        <th>Code</th>
		        <th>Pos.</th>
		        <th>Countries of circulation</th>
		        <th>&nbsp;</th>
			</tr>
			<tr>
				<td rowspan="3" align="center" valign="top"><input type="checkbox" name="currencies[{key}][enabled]" checked="{currency.enabled}" value="{currency.enabled}"></td>
                <td valign="top"><input type="text" name="currencies[{key}][name]" value="{currency.name}" size="15"></td>
                <td valign="top"><input type="text" name="currencies[{key}][code]" value="{currency.code}" size="5" maxlength="3"></td>
                <td valign="top"><input type="text" name="currencies[{key}][order_by]" value="{currency.order_by}" size="5"></td>
                <td rowspan="3"><select style="width : 250px" IF="!currency.base" name="currencies[{key}][countries][]" size="5" multiple>
		            {foreach:countries,country}{if:currency.inCurrencyCountries(country.code)}<option value="{country.code:r}" selected>{country.country:h}</option>{end:}{end:}
		            {foreach:countries,country}{if:!currency.inCurrencyCountries(country.code)}<option value="{country.code:r}">{country.country:h}</option>{end:}{end:}
			        </select>
				</td>
                <td valign="top" rowspan="3"><input type="checkbox" name="deleted[]" value="{currency.currency_id}"></td>
			</tr>
			<tr>
				<th class="TableHead">Currency format</th>
				<th colspan="2" class="TableHead">Exchange rate</th>
			</tr>
            <tr>
                <td valign="top"><input type="text" name="currencies[{key}][price_format]" value="{currency.price_format}" size="15"></td>
		        <td valign="top" colspan="2"><input type="text" name="currencies[{key}][exchange_rate]" value="{currency.exchange_rate}" size="13"></td>
			</tr> 
		</table>	
	</td>
</tr>
<tr>
    <td colspan="2"><hr></td>
</tr>
<script>
	function delete_warning() 
	{
    	if (confirm('You are about to delete choosen currencies.\n\nAre you sure you want to delete it?')) { 
			javascript: document.currency_form.action.value='delete';
			document.currency_form.submit();
		    return true;
	    }
		return false;
	}
</script>
<tr if="allCurrencies">
	<td><input type="submit" value=" Update "></td>
	<td align="right"><input type="button" value=" Delete " onClick="javascript: delete_warning();"></td>
</tr>
</table>
</form>
<form name="new_currency_form" action="admin.php" method="POST">
<input type="hidden" name="target" value="currencies">
<input type="hidden" name="action" value="add">
<input type="hidden" name="currency[base]" value="0">
<table border="0" cellpadding="0" cellspacing="2">
<tr>
    <td class="AdminTitle" colspan="8">&nbsp;</td>
</tr>
<tr>
	<td class="AdminTitle" colspan="8">Add new currency:</td>
</tr>
<tr>
    <td colspan="8">&nbsp;</td>
</tr>
<tr>
	<th rowspan="4" width="40px">&nbsp;</th>
    <th class="TableHead">Name</th>
    <th class="TableHead">Code</th>
    <th class="TableHead">Pos.</th>
    <th class="TableHead">Countries of circulation</th>
</tr>
<tr valign="top">
    <td><input type="text" size="15" name="currency[name]"></td>
    <td><input type="text" size="5" name="currency[code]" maxlength="3"></td>
    <td><input type="text" size="5" name="currency[order_by]"></td>
    <td rowspan="3"><select style="width : 250px"   name="currency[countries][]" size="5" multiple>
            <option FOREACH="countries,country" value="{country.code:r}">{country.country:h}
            </option>
        </select>
    </td>
</tr>
<tr>
    <th class="TableHead">Currency format</th>
    <th class="TableHead" colspan="2">Exchange rate</th>
</tr>
<tr>
    <td><input type="text" size="15" name="currency[price_format]"></td>
    <td colspan="2"><input type="text" size="13" name="currency[exchange_rate]" value="1.00"></td>
</tr>
<tr>
    <td colspan="5"><hr></td>
</tr>
<tr>

<tr>
	<td colspan="5" align="right"><input type="submit" value=" Add new "></td>
</tr>
</table>
</form>
