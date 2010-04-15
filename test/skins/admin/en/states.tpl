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

function setChecked(form, input, check)
{
    var elements = document.forms[form].elements[input];

    for (var i = 0; i < elements.length; i++) {
        elements[i].checked = check;
    }
}

function setHeaderChecked()
{
	var Element = document.getElementById("activate_products");
    if (Element && !Element.checked) {
    	Element.checked = true;
    }
}

// -->
</script>

<a name="select_country"></a>
<p>Use this section to manage the lists of counties, provinces, regions and states of different countries. The lists are used in shipping and tax settings and calculations, and in the registration form at the Customer Front-end.
<hr>

<font IF="status=#country_code#" class="ErrorMessage"><br><br>&gt;&gt;&nbsp;Please, select country&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#added#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;State added successfully&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#deleted#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;State(s) deleted successfully&nbsp;&lt;&lt;<br><br></font>
<font IF="status=#updated#" class="SuccessMessage"><br><br>&gt;&gt;&nbsp;State(s) updated successfully&nbsp;&lt;&lt;<br><br></font>

<form name="select_country_form" method="GET">
<p class="AdminHead">Select country<p>
<input type="hidden" name="target" value="states">
<widget class="XLite_View_CountrySelect" field="country_code" value="{country_code}" onChange="document.select_country_form.submit();" all nonFixed>
</form>

<span IF="!states">
<br>No states found.
</span>
<p>
<form IF="states" action="admin.php" name="update_delete_states_form" method="POST">
<input type="hidden" name="target" value="states">
<input type="hidden" name="action" value="update">
<input type="hidden" name="country_code" value="{country_code}">

<p>
<table cellpadding="0" cellspacing="0" border="0">
	<tr><td>&nbsp;</td></tr>
	<tr class="DialogBox">
		<td class="AdminHead">List of states</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td class="CenterBorder">
			<table cellspacing="1" cellpadding="2" border="0">
				<tr class="TableHead">
				    <th class="TableHead">Code</th>
				    <th class="TableHead">State</th>
				    <th class="TableHead"><input id="select_states" type="checkbox" onClick="this.blur();setChecked('update_delete_states_form','state_ids',this.checked);"></th>
				</tr>

				<tr FOREACH="states,state_idx,state" class="{getRowClass(state_idx,#DialogBox#,#TableRow#)}">
				    <td>
				        <input type="text" size="8" name="state_data[{state.state_id}][code]" value="{state.code:r}">
				    </td>
				    <td>
				        <input type="text" size="32" name="state_data[{state.state_id}][state]" value="{state.state:r}">
				    </td>
				    <td>
				        <input id="state_ids" type="checkbox" name="delete_states[]" value="{state.state_id}" onClick="this.blur();">
				    </td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
    	<td colspan="3">
			<table border=0 width="100%">
            	<tr>
                	<td align=left>
	        		<input type="submit" name="update" value="Update" class="DialogMainButton">
    	            </td>
	                <td align=right>
    	    		<input type="submit" name="delete" value="Delete selected" onClick="document.update_delete_states_form.action.value='delete'; document.update_delete_states_form.submit()">
        	        </td>
				</tr>
			</table>
	    </td>
	</tr>
	<tr>
	    <td colspan="3">&nbsp;</td>
	</tr>
</tbody>
</table>

</form>

<a name="add_section"></a>
<form action="admin.php" method="post">
<input type="hidden" name="target" value="states">
<input type="hidden" name="country_code" value="{country_code}">
<input type="hidden" name="action" value="add">

<table cellpadding="0" cellspacing="0" border="0">
	<tr><td>&nbsp;</td></tr>
	<tr class="DialogBox">
		<td class="AdminTitle">Add new state</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td class="CenterBorder">
			<table cellspacing="1" cellpadding="2" border="0">
			<tr>
			    <th class="TableHead">Code</th>
			    <th class="TableHead">State</th>
			</tr>
			<tr class="DialogBox">
			    <td><input type="text" size="8" name="code" value="{code}"></td>
			    <td><input type="text" size="30" name="state" value="{state}"></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
{if:!valid}
	<tr class="DialogBox">
		<td IF="status=#code#" colspan="2"><font class="ErrorMessage">&gt;&gt;&nbsp;Mandatory field "Code" empty.</font><br><br></td>
		<td IF="status=#state#" colspan="2"><font class="ErrorMessage">&gt;&gt;&nbsp;Mandatory field "State" empty.</font><br><br></td>
	</tr>
{end:}
	<tr>
		<td colspan=2> <input type="submit" name="add" value="Add new"> </td>
	</tr>
</table>

{if:!valid}
<script language="javascript">
{if:status=#country_code#}
var anchor = '#select_country';
{else:}
var anchor = '#add_section';
{end:}
<!--
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
