{if:option.name=#bestsellers_menu#}
<select name="{option.name}">
<option value="1" selected="{!option.value=#0#}">a side box</option>
<option value="0" selected="{option.value=#0#}">the main column</option>
</select>
{end:}
