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
<!-- 

var CountryVatEnabledCheckBoxes = new Array();
var CountryEnabledCheckBoxes = new Array();
var CheckBoxes = new Array();

function setChecked(column, check)
{
	var CheckBoxesArray = CheckBoxes[column];

	if (CheckBoxesArray) {
        for (var i = 0; i < CheckBoxesArray.length; i++) {
        	var Element = document.getElementById(CheckBoxesArray[i]);
            if (Element) {
            	Element.checked = check;
            }
        }
	}
}

function setHeaderChecked(column)
{
	var Element = document.getElementById(column);
    if (Element && !Element.checked) {
    	Element.checked = true;
    }
}

function setDelete(form, input, check)
{
    var elements = document.forms[form].elements[input];

    for (var i = 0; i < elements.length; i++) {
        elements[i].checked = check;
    }
}
// -->
</script>

<p>
Use this section to manage the list of existing countries. This list is used in the shipping and taxation settings and calculations, and in the registration form at the Customer Front-end.
<hr>

<font IF="status=#added#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;Country added successfully&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#deleted#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;Country(s) deleted successfully&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#updated#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;Country(s) updated successfully&nbsp;&lt;&lt;<br><br></font>

<p>
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="CenterBorder">
	<table border="0" cellspacing="1" cellpadding="2">
		<tr class="TableHead">
    		<th class="TableHead">Code</th>
		    <th class="TableHead">Country</th>
			<th class="TableHead">Language</th>
		    <th class="TableHead">Charset</th>
		    <th class="TableHead">VAT taxable<br>
		    	<input id="enable_countries_vat" type="checkbox" onClick="this.blur();setChecked('enable_countries_vat',this.checked);">
		    </th>
		    <th class="TableHead">Active<br>
		    	<input id="enable_countries" type="checkbox" onClick="this.blur();setChecked('enable_countries',this.checked);">
			</th>
			<widget module="AntiFraud" template="modules/AntiFraud/risk_country/label.tpl">
			<th class="TableHead">Delete<br>
				<input id="delete_countries" type="checkbox" onClick="this.blur();setDelete('countries_form','countries_ids',this.checked);">
			</th>
		</tr>

		<form action="admin.php" method="post" name="countries_form">
		<input type="hidden" name="target" value="countries">
		<input type="hidden" name="action" value="update">
		<tr FOREACH="getCountries(),country_idx,country" class="{getRowClass(country_idx,#DialogBox#,#TableRow#)}">
		    <td align="center"><a href="admin.php?target=states&country_code={country.code}" title="Click here to view states of country" onClick="this.blur();"><u>{country.code}</u></a></td>
		    <td>
		        <input type="text" size="34" maxlength="50" name="countries[{country.code}][country]" value="{country.country:r}">
		    </td>
			<td align="center">
				<input type="text" size="15" maxlength="32" name="countries[{country.code}][language]" value="{country.language:r}">
			</td>
		    <td align="center">
		        <input type="text" size="15" maxlength="32" name="countries[{country.code}][charset]" value="{country.charset:r}">
		    </td>
		    <td align="center">
		        <input id="country_vat_{country_idx}" type="checkbox" name="countries[{country.code}][eu_member]" value="Y" checked="{country.eu_member}" onClick="this.blur();">
		        <script language="Javascript">CountryVatEnabledCheckBoxes[CountryVatEnabledCheckBoxes.length]="country_vat_{country_idx}";</script>
		        <script language="Javascript" IF="country.enabled">setHeaderChecked("enable_countries_vat");</script>
		    </td>
		    <td align="center">
		        <input id="country_enabled_{country_idx}" type="checkbox" name="countries[{country.code}][enabled]" value="Y" checked="{country.enabled}" onClick="this.blur();">
		        <script language="Javascript">CountryEnabledCheckBoxes[CountryEnabledCheckBoxes.length]="country_enabled_{country_idx}";</script>
		        <script language="Javascript" IF="country.enabled">setHeaderChecked("enable_countries");</script>
		    </td>
			    <widget module="AntiFraud" template="modules/AntiFraud/risk_country/checkbox.tpl">
			<td align="center">
				<input id="countries_ids" type="checkbox" name="delete_countries[]" value="{country.code}" onClick="this.blur();">
			</td>
		</tr>
	</table>
		</td>
	</tr>

<tr>
    <td colspan="7">
		<table border=0 width="100%">
		<tr>
			<td align="left"><br><input type="submit" class="DialogMainButton" name="submit" value="Update"></td>
			<td align="right"><input type="submit" name="delete" value="Delete selected" onClick="document.countries_form.action.value='delete';"></td>
		</tr>
		</table>
	</td>
	<script language="Javascript">CheckBoxes["enable_countries_vat"] = CountryVatEnabledCheckBoxes;</script>
	<script language="Javascript">CheckBoxes["enable_countries"] = CountryEnabledCheckBoxes;</script>
</tr>
</form>
</table>

<p>

<a name="add_section"></a>
<form action="admin.php" method="post" name="countries_add_form">
<input type="hidden" name="target" value="countries">
<input type="hidden" name="action" value="add">


<table cellpadding="0" cellspacing="0" border="0">
	<tr><td>&nbsp;</td></tr>
	<tr class="DialogBox">
		<td class="AdminTitle">Add new country</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td class="CenterBorder">
			<table cellspacing="1" cellpadding="2" border="0">
				<tr class="TableHead">
					<th class="TableHead">Code</th>
					<th class="TableHead">Country</th>
					<th class="TableHead">Language</th>
					<th class="TableHead">Charset</th>
					<th class="TableHead">VAT taxable</th>
					<th class="TableHead">Active</th>
				</tr>
				<tr class="DialogBox">
					<td><input type="text" size="3" maxlength="2" name="code" value="{code}"></td>
					<td><input type="text" size="34" maxlength="50" name="country" value="{country}"></td>
					<td><input type="text" size="15" maxlength="32" name="language" value="{language}"></td>
					<td><input type="text" size="15" maxlength="32" name="charset" {if:charset}value="{charset}"{else:}value="iso-8859-1"{end:}></td>
					<td align="middle"><input type="checkbox" name="eu_member" value="Y" checked="{isSelected(eu_member,#Y#)}"></td>
					<td align="middle"><input type="checkbox" name="enabled" value="Y" checked></td>
				</tr>
			</table>
		</td>
	</tr>

{if:!valid}
	<tr>
		<td IF="status=#exists#" colspan="6"><font class="ErrorMessage"><br>&gt;&gt;&nbsp;Specified country code is already in use</font><br><br></td>
		<td IF="status=#code#" colspan="6"><font class="ErrorMessage"><br>&gt;&gt;&nbsp;Mandatory field "Code" is empty.</font><br><br></td>
		<td IF="status=#country#" colspan="6"><font class="ErrorMessage"><br>&gt;&gt;&nbsp;Mandatory field "Country" is empty</font><br><br></td>
		<td IF="status=#charset#" colspan="6"><font class="ErrorMessage"><br>&gt;&gt;&nbsp;Mandatory field "Charset" is empty</font><br><br></td>
	</tr>
{end:}
	<tr>
		<td colspan="6"><input type="submit" name="country_add" value="Add new"></td>
	</tr>
</table>
</form>


{if:!valid}
<script language="javascript">
<!--
var anchor = '#add_section';
if (window.opera) {
	window.onload = opera_click;
} else {
	window.document.location.hash = anchor;
}

function opera_click() {
	window.document.location.hash = anchor;
}
-->
</script>
{end:}
