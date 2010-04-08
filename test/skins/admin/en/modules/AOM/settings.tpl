{if:option.isCheckbox()}
<input id="{option.name}" type="checkbox" name="{option.name}" onClick="this.blur()" checked="{option.isChecked()}">
{end:}
{if:option.isText()}
<input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size="{getOptionSize(option.name)}">
{end:}
