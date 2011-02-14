{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script type="text/javascript">
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
<hr />

<font IF="status=#added#" class="success-message"><br /><br />&gt;&gt;&nbsp;Country added successfully&nbsp;&lt;&lt;<br /><br />
<font IF="status=#deleted#" class="success-message"><br /><br />&gt;&gt;&nbsp;Country(s) deleted successfully&nbsp;&lt;&lt;<br /><br />
<font IF="status=#updated#" class="success-message"><br /><br />&gt;&gt;&nbsp;Country(s) updated successfully&nbsp;&lt;&lt;<br /><br />

<form action="admin.php" method="post" name="countries_form">
<input type="hidden" name="target" value="countries">
<input type="hidden" name="action" value="update">
	<table class="data-table">
		<tr>
    		<th>Code</th>
		    <th>Country</th>
		    <th>VAT taxable<br />
		    	<input id="enable_countries_vat" type="checkbox" onclick="this.blur();setChecked('enable_countries_vat',this.checked);">
		    </th>
		    <th>Active<br />
		    	<input id="enable_countries" type="checkbox" onclick="this.blur();setChecked('enable_countries',this.checked);">
			</th>
			<widget module="CDev\AntiFraud" template="modules/CDev/AntiFraud/risk_country/label.tpl">
			<th>Delete<br />
				<input id="delete_countries" type="checkbox" onclick="this.blur();setDelete('countries_form','countries_ids',this.checked);">
			</th>
		</tr>

		<tr FOREACH="getCountries(),country_idx,country" class="{getRowClass(country_idx,#dialog-box#,#highlight#)}">
		    <td align="center"><a href="admin.php?target=states&country_code={country.code}" title="Click here to view states of country" onclick="this.blur();"><u>{country.code}</u></a></td>
		    <td>
		        <input type="text" size="34" maxlength="50" name="countries[{country.code}][country]" value="{country.country:r}">
		    </td>
		    <td align="center">
		        <input id="country_vat_{country_idx}" type="checkbox" name="countries[{country.code}][eu_member]" value="Y" checked="{country.eu_member}" onclick="this.blur();">
		        <script type="text/javascript">CountryVatEnabledCheckBoxes[CountryVatEnabledCheckBoxes.length]="country_vat_{country_idx}";</script>
		        <script type="text/javascript" IF="country.enabled">setHeaderChecked("enable_countries_vat");</script>
		    </td>
		    <td align="center">
		        <input id="country_enabled_{country_idx}" type="checkbox" name="countries[{country.code}][enabled]" value="Y" checked="{country.enabled}" onclick="this.blur();">
		        <script type="text/javascript">CountryEnabledCheckBoxes[CountryEnabledCheckBoxes.length]="country_enabled_{country_idx}";</script>
		        <script type="text/javascript" IF="country.enabled">setHeaderChecked("enable_countries");</script>
		    </td>
			    <widget module="CDev\AntiFraud" template="modules/CDev/AntiFraud/risk_country/checkbox.tpl">
			<td align="center">
				<input id="countries_ids" type="checkbox" name="delete_countries[]" value="{country.code}" onclick="this.blur();">
			</td>
		</tr>

    <tr>
      <td colspan="5">
		<table border=0 width="100%">
		<tr>
			<td align="left">
        <br />
        <widget class="\XLite\View\Button\Submit" style="main-button" name="submit" label="Update" />
      </td>
			<td align="right">
        <widget class="\XLite\View\Button\Submit" label="Delete selected" name="delete" jsCode="document.countries_form.action.value='delete';" />
      </td>
		</tr>
		</table>
	</td>
	<script type="text/javascript">CheckBoxes["enable_countries_vat"] = CountryVatEnabledCheckBoxes;</script>
	<script type="text/javascript">CheckBoxes["enable_countries"] = CountryEnabledCheckBoxes;</script>
</tr>
</table>
</form>

<p>

<a name="add_section"></a>
<form action="admin.php" method="post" name="countries_add_form">
<input type="hidden" name="target" value="countries">
<input type="hidden" name="action" value="add">

<h2>Add new country</h2>
			
      <table class="data-table">
				<tr>
					<th>Code</th>
					<th>Country</th>
					<th>VAT taxable</th>
					<th>Active</th>
				</tr>
				<tr class="dialog-box">
					<td><input type="text" size="3" maxlength="2" name="code" value="{code}"></td>
					<td><input type="text" size="34" maxlength="50" name="country" value="{country}"></td>
					<td align="middle"><input type="checkbox" name="eu_member" value="Y" checked="{isSelected(eu_member,#Y#)}"></td>
					<td align="middle"><input type="checkbox" name="enabled" value="Y" checked></td>
				</tr>

{if:!valid}
	<tr>
		<td IF="status=#exists#" colspan="6"><font class="error-message"><br />&gt;&gt;&nbsp;Specified country code is already in use<br /><br /></td>
		<td IF="status=#code#" colspan="6"><font class="error-message"><br />&gt;&gt;&nbsp;Mandatory field "Code" is empty.<br /><br /></td>
		<td IF="status=#country#" colspan="6"><font class="error-message"><br />&gt;&gt;&nbsp;Mandatory field "Country" is empty<br /><br /></td>
		<td IF="status=#charset#" colspan="6"><font class="error-message"><br />&gt;&gt;&nbsp;Mandatory field "Charset" is empty<br /><br /></td>
	</tr>
{end:}
	<tr>
		<td colspan="6">
      <widget class="\XLite\View\Button\Submit" label="Add new" name="country_add" />
    </td>
	</tr>
</table>
</form>

{if:!valid}
<script type="text/javascript">
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
