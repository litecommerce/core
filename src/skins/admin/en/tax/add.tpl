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
//<!--
function addVal(param, select)
{
	var input = select.form.elements[param];
	selectedValue = select.options[select.selectedIndex].value;

	if (selectedValue != '') {
    if (input.value != '') {
      input.value += ',';
    }

    input.value += selectedValue;

	  select.selectedIndex = 0;
  }
}

function changeVal(param)
{
	var select = document.tax_form.elements['select_'+param];
	var input = document.tax_form.elements[param];
	selectedValue = select.options[select.selectedIndex].text;
	input.value = selectedValue;
	select.selectedIndex = 0;
}
//-->
</script>

<div class="error-message">{error}</div>

<div IF="inv_exp_error" class="error-message">There is a mistake in the tax calculation formula.</div>

<form action="admin.php" method="POST" name="tax_form">
  <input type="hidden" name="page" value="add_rate">
  <input type="hidden" name="target" value="taxes">
  <input type="hidden" name="mode" value="{mode}">
  <input IF="edit" type="hidden" name="action" value="edit_submit">
  <input IF="!edit"type="hidden" name="action" value="add_submit">
  <input type="hidden" name="add_another" value="0">
  <input type="hidden" name="ind" value="{ind}">
  <input type="hidden" name="returnUrl" value="admin.php?target=taxes&page=rates">

  <strong>Condition (optional)</strong>

  <hr />

  <table cellspacing="1" cellpadding="3">
    <tr FOREACH="taxParams,_param">
      <td><strong>{_param.name}</strong></td>
	    <td style="white-space: nowrap;">
		    <input type="text" style="width:330pt" name="{_param.var}" value="{getCondParam(tax,_param.cond,_param.var)}">
		    <select name="select_{_param.var}" onchange="javascript: addVal('{_param.var}', this);">
			    <option value="">-- select --</option>
      			{if:_param.diplay_ex}
			        {foreach:_param.values,c}
				        <option value="" style="background-color: #EEEEEE;">{c.country}</option>
        				<option FOREACH="c.states,key,val" value="{key}">&nbsp;&nbsp;{val}</option>
			        {end:}
			      {else:}
        			<option FOREACH="_param.values,key,val" value="{key}">{val}</option>
      			{end:}
		</select>
	</td></tr>
</table>
<br />
<b>Tax value (optional; if not specified, a new folder will be created)</b>
<hr />
<table cellpadding="7">
<tr valign="bottom">
	<td>Tax name, either existing or new. A special name 'Tax' means total tax:</td>
	<td>Tax value (%). You can use an '=' sign to define an expression. <br />
	<b>Example:</b> "=Tax1+(1+Tax1/100.0) * Tax2" - this will calculate Tax2 after the Tax1 was applied.
</td></tr>
<tr><td nowrap>
	<input type="text" name="taxName" value="{getNoteTaxName(tax)}" size="25">
	<select name="select_taxName" onChange="changeVal('taxName')">
		<option>-- select --</option>
		<option FOREACH="taxNames,_taxName">{_taxName}</option>
	</select>
</td><td>
	<input type="text" name="taxValue" value="{getNoteTaxValue(tax)}" size="40" style="width:300pt"><br />
</td></tr>
<tr IF="exp_error">
    <td>&nbsp;</td>
    <td><table><tr><td><img src="skins/admin/en/images/code.gif" alt="[!]"></td><td>&nbsp;The {exp_error:h} variables are not defined, the tax rate cannot be calculated.</td></tr></table></td>
</tr>
</table>
<br />
{if:edit}
<widget class="\XLite\View\Button\Submit" label=" Update " />
{else:}
<widget class="\XLite\View\Button\Submit" label="Save" style="main-button" />
<widget class="\XLite\View\Button\Regular" jsCode="document.tax_form.add_another.value='1'; document.tax_form.page.value = 'add_rate'; document.tax_form.submit();" label="Save &amp; add another" />
{end:}
&nbsp;&nbsp;&nbsp;<a href="admin.php?target=taxes&page=rates"><img src="images/go.gif" alt="" width="13" height="13" align="absmiddle"><b> Go back</b></a>
</form>
