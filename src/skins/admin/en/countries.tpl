{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<p>{t(#Use this section to manage the list of existing countries. This list is used in the shipping settings and calculations, and in the registration form at the Customer Front-end.#)}</p>

<hr />

<span IF="status=#added#" class="success-message"><br /><br />&gt;&gt;&nbsp;Country added successfully&nbsp;&lt;&lt;<br /><br /></span>
<span IF="status=#deleted#" class="success-message"><br /><br />&gt;&gt;&nbsp;Country(s) deleted successfully&nbsp;&lt;&lt;<br /><br /></span>
<span IF="status=#updated#" class="success-message"><br /><br />&gt;&gt;&nbsp;Country(s) updated successfully&nbsp;&lt;&lt;<br /><br /></span>

<form action="admin.php" method="post" name="countries_form">
<input type="hidden" name="target" value="countries" />
<input type="hidden" name="action" value="update" />
	<table class="data-table">
		<tr>
    	<th>{t(#Code#)}</th>
		  <th>{t(#Country#)}</th>
		  <th class="center">{t(#Active#)}<br />
		  	<input class="column-selector" id="enable_countries" type="checkbox" />
			</th>
			<widget module="CDev\AntiFraud" template="modules/CDev/AntiFraud/risk_country/label.tpl">
			<th class="center">{t(#Delete#)}<br />
				<input class="column-selector" id="delete_countries" type="checkbox" />
			</th>
		</tr>

		<tr FOREACH="getCountries(),country_idx,country" class="{getRowClass(country_idx,#dialog-box#,#highlight#)}">
		    <td align="center"><a href="admin.php?target=states&country_code={country.code}" title="Click here to view states of country" />{country.code}</a></td>
		    <td>
		        <input type="text" size="34" maxlength="50" name="countries[{country.code}][country]" value="{country.country:r}" />
		    </td>
		    <td align="center">
		        <input id="country_enabled_{country_idx}" type="checkbox" name="countries[{country.code}][enabled]" value="Y" checked="{country.enabled}" />
		    </td>
			    <widget module="CDev\AntiFraud" template="modules/CDev/AntiFraud/risk_country/checkbox.tpl">
			<td align="center">
				<input id="countries_ids_{country.code}" type="checkbox" name="delete_countries[]" value="{country.code}" />
			</td>
		</tr>

    <tr>
      <td colspan="5">

		<table width="100%">
		<tr>
			<td align="left">
        <widget class="\XLite\View\Button\Submit" style="main-button" name="submit" label="Update" />
      </td>
			<td align="right">
        <widget class="\XLite\View\Button\Regular" label="Delete selected" name="delete" jsCode="document.countries_form.action.value='delete';document.countries_form.submit()" />
      </td>
		</tr>
		</table>

	</td>
</tr>
</table>
</form>

<p>

<a name="add_section"></a>
<form action="admin.php" method="post" name="countries_add_form">
<input type="hidden" name="target" value="countries" />
<input type="hidden" name="action" value="add" />

<h2>{t(#Add new country#)}</h2>
			
      <table class="data-table">
				<tr>
					<th>Code</th>
					<th>Country</th>
					<th>Active</th>
				</tr>
				<tr class="dialog-box">
					<td><input type="text" size="3" maxlength="2" name="code" value="{code}" /></td>
					<td><input type="text" size="34" maxlength="50" name="country" value="{country}" /></td>
					<td align="middle"><input type="checkbox" name="enabled" value="Y" checked /></td>
				</tr>

{if:!valid}
	<tr>
		<td IF="status=#exists#" colspan="6"><span class="error-message"><br />&gt;&gt;&nbsp;Specified country code is already in use<br /><br /></span></td>
		<td IF="status=#code#" colspan="6"><span class="error-message"><br />&gt;&gt;&nbsp;Mandatory field "Code" is empty.<br /><br /></span></td>
		<td IF="status=#country#" colspan="6"><span class="error-message"><br />&gt;&gt;&nbsp;Mandatory field "Country" is empty<br /><br /></span></td>
		<td IF="status=#charset#" colspan="6"><span class="error-message"><br />&gt;&gt;&nbsp;Mandatory field "Charset" is empty<br /><br /></span></td>
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
