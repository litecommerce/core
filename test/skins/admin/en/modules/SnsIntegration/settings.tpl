{if:option.isCheckbox()}
<input id="{option.name}" type="checkbox" name="{option.name}" onClick="this.blur()" checked="{option.isChecked()}">
{end:}
{if:option.isText()}
<input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size="{dialog.getOptionSize(option.name)}">
{end:}
{if:option.name=#collectorLanguage#}
<select name="{option.name}">
	<option value="php" selected="{option.value=#php#}">php</option>
	<option value="aspx" selected="{option.value=#aspx#}">Asp.Net</option>
</select>
{end:}
