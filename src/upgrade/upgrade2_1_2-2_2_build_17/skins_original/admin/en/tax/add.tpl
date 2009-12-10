<script>
//<!--
function addVal(param)
{
	var select = document.tax_form.elements['select_'+param];
	var input = document.tax_form.elements[param];
	selectedValue = select.options[select.selectedIndex].value;

	if ( selectedValue == '' )
		return;

	if (input.value == '') {
		input.value = selectedValue;
	} else {
		input.value = input.value+','+selectedValue;
	}
	select.selectedIndex = 0;
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
<span class="ErrorMessage">{error}</span>
<form action="admin.php" method="POST" name="tax_form">
<input type="hidden" name="page" value="rates">
<input type="hidden" name="target" value="taxes">
{if:edit}
<input type="hidden" name="action" value="edit_submit">
{else:}
<input type="hidden" name="action" value="add_submit">
{end:}
<input type="hidden" name="add_another" value="0">
<input type="hidden" name="ind" value="{ind}">
<b>Condition (optional)</b>
<hr>
<table border="0">
<tr FOREACH="taxParams,_param"><td><b>{_param.name}</b></td>
	<td nowrap>
		<input type="text" style="width:330pt" name="{_param.var}" value="{getCondParam(tax,_param.cond)}">
		<select name="select_{_param.var}" onChange="addVal('{_param.var}')">
			<option value="">-- select --</option>
			{if:_param.diplay_ex}
			{foreach:_param.values,v}
				{if:v.country}<option {if:v.lit}style="background-color: #EEEEEE;"{end:} value="">{v.country}</option>{end:}
				<option {if:v.lit}style="background-color: #EEEEEE;"{end:} value="{v.val}">&nbsp;&nbsp;{v.val}&nbsp;&nbsp;[{v.code}]</option>
			{end:}
			{else:}
			<option FOREACH="_param.values,val" value="{val}">{val}</option>
			{end:}
		</select>
	</td></tr>
</table>
<br>
<b>Tax value (optional; if not specified, a new folder will be created)</b>
<hr>
<table border="0" cellpadding="7">
<tr valign="bottom">
	<td>Tax name, either existing or new. A special name 'Tax' means total tax:</td>
	<td>Tax value (%). You can use an '=' sign to define an expression. <br>
	<b>Example:</b> "=Tax1+(1+Tax1/100.0) * Tax2" - this will calculate Tax2 after the Tax1 was applied.
</td></tr>
<tr><td nowrap>
	<input type="text" name="taxName" value="{getNoteTaxName(tax)}" size="25">
	<select name="select_taxName" onChange="changeVal('taxName')">
		<option>-- select --</option>
		<option FOREACH="taxNames,_taxName">{_taxName}</option>
	</select>
</td><td>
	<input type="text" name="taxValue" value="{getNoteTaxValue(tax)}" size="40" style="width:300pt">
</table>
<br>
{if:edit}
<input type="submit" value=" Update ">
{else:}
<input type="submit" value=" Save " class="DialogMainButton" style="width:200pt">&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value=" Save & Add another " onclick="document.tax_form.add_another.value='1'; document.tax_form.page.value = 'add_rate'; document.tax_form.submit();" style="width:200pt">
{end:}
&nbsp;&nbsp;&nbsp;<a href="admin.php?target=taxes&page=rates"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"><b> Go back</b></a>
</form>
