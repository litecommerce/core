{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<a name="select_country"></a>

<p>{t(#Use this section to manage the lists of counties, provinces, regions and states of different countries. The lists are used in shipping settings and calculations, and in the registration form at the Customer Front-end.#)}

<hr />

<span IF="status=#country_code#" class="error-message"><br /><br />&gt;&gt;&nbsp;{t(#Please, select country#)}&nbsp;&lt;&lt;<br /><br /></span>
<span IF="status=#added#" class="success-message"><br /><br />&gt;&gt;&nbsp;{t(#The state has been added successfully#)}&nbsp;&lt;&lt;<br /><br /></span>
<span IF="status=#deleted#" class="success-message"><br /><br />&gt;&gt;&nbsp;{t(#States have been deleted successfully#)}&nbsp;&lt;&lt;<br /><br /></span>
<span IF="status=#updated#" class="success-message"><br /><br />&gt;&gt;&nbsp;{t(#States have been updated successfully#)}&nbsp;&lt;&lt;<br /><br /></span>

<form name="select_country_form" method="get">

<p class="admin-head">{t(#Select country#)}</p>

<input type="hidden" name="target" value="states" />

<widget class="\XLite\View\CountrySelect" field="country_code" country="{country_code}" onchange="javascript: document.select_country_form.submit();" all />

</form>

<span IF="!states">
<br />{t(#No states found#)}.
</span>

<p>

<form IF="states" action="admin.php" name="update_delete_states_form" method="post">

<input type="hidden" name="target" value="states" />
<input type="hidden" name="action" value="update" />
<input type="hidden" name="country_code" value="{country_code}" />

<h2>{t(#List of states#)}</h2>
			
<table class="data-table">

  <tr>
    <th>{t(#Code#)}</th>
    <th>{t(#State#)}</th>
    <th><input id="select_states" type="checkbox" onclick="javascript:setChecked('.state_ids',this.checked);"></th>
  </tr>

  <tr FOREACH="states,state_idx,state" class="{getRowClass(state_idx,#dialog-box#,#highlight#)}">
    <td>
      <input type="text" size="8" name="state_data[{state.state_id}][code]" value="{state.code:r}" />
    </td>
    <td>
      <input type="text" size="32" name="state_data[{state.state_id}][state]" value="{state.state:r}" />
    </td>
    <td>
      <input class="state_ids" type="checkbox" name="delete_states[]" value="{state.state_id}" onclick="this.blur();">
    </td>
  </tr>

  <tr>
    <td colspan="3">
      <widget class="\XLite\View\Button\Submit" name="update" label="{t(#Update#)}" style="main-button" />
      <widget class="\XLite\View\Button\Regular" name="delete" label="{t(#Delete selected#)}" jsCode="document.update_delete_states_form.action.value='delete'; document.update_delete_states_form.submit()" />
    </td>
  </tr>
</table>

</form>

<a name="add_section"></a>

<form action="admin.php" method="post">

<input type="hidden" name="target" value="states" />
<input type="hidden" name="country_code" value="{country_code}" />
<input type="hidden" name="action" value="add" />

<h2>{t(#Add new state#)}</h2>

<table class="data-table">

	<tr>
	  <th>{t(#Code#)}</th>
	  <th>{t(#State#)}</th>
	</tr>

	<tr class="dialog-box">
	  <td><input type="text" size="8" name="code" value="{code}" /></td>
	  <td><input type="text" size="30" name="state" value="{state}" /></td>
  </tr>

{if:!valid}
	<tr class="dialog-box">
		<td IF="status=#code#" colspan="2"><span class="error-message">&gt;&gt;&nbsp;{t(#Mandatory field "Code" empty#)}.<br /><br /></span></td>
		<td IF="status=#state#" colspan="2"><span class="error-message">&gt;&gt;&nbsp;{t(#Mandatory field "State" empty#)}.<br /><br /></span></td>
	</tr>
{end:}

	<tr>
		<td colspan=2>
      <widget class="\XLite\View\Button\Submit" label="{t(#Add new#)}" name="add" />
    </td>
	</tr>

</table>

{if:!valid}

<script type="text/javascript">

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
