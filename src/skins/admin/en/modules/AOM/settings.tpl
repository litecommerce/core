{if:option.isCheckbox()}
<input id="{option.name}" type="checkbox" name="{option.name}" onClick="this.blur()" checked="{option.isChecked()}">
{end:}
{if:option.isText()}
<input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size="{widget.dialog.getOptionSize(option.name)}">
{end:}
{if:option.isName(#current_currency#)}
<select name="{option.name}">
	<option FOREACH="xlite.factory.Currency.findAll(),currency" value="{currency.code}" selected="{option.value=currency.code}">{currency.code}: {currency.name}</option>
</select>
{end:}
