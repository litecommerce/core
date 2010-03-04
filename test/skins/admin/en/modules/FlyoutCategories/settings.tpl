{if:option.isCheckbox()}
<input id="{option.name}" type="checkbox" name="{option.name}" onClick="this.blur()" checked="{option.isChecked()}">
{end:}

{if:option.isText()}
<input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=10>
{end:}

{if:option.isName(#scheme#)}
<select class="FixedSelect" name="{option.name}" size="1" OnChange="if (this.value >= 0) document.options_form.submit();">
   <option value=0>None</option>
   <option value=-1>---------------------</option>
   <option FOREACH="schemes,k,v" value="{v.scheme_id}" selected="{v.scheme_id=option.value}">{v.name}</option>
</select>
{end:}

{if:option.isName(#smallimage_width#)}
<select class="FixedSelect" name="smallimage_width" size="1">
	<option value="16" selected="{option.value=#16#}">16</option>
	<option value="32" selected="{option.value=#32#}">32</option>
	<option value="64" selected="{option.value=#64#}">64</option>
</select>
{end:}

{if:option.isName(#resize_quality#)}
<select class="FixedSelect" name="resize_quality" size="1">
	<option value="75" selected="{option.value=#75#}">Normal</option>
	<option value="85" selected="{option.value=#85#}">Good</option>
	<option value="95" selected="{option.value=#95#}">Best</option>
</select>
{end:}
