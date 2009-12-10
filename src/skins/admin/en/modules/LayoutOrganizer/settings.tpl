{if:option.isCheckbox()}
<input id="{option.name}" type="checkbox" name="{option.name}" onClick="this.blur()" checked="{option.isChecked()}">
{end:}
{if:option.isText()}
<input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=10>
{end:}
{if:option.isName(#columns#)}
<select name="{option.name}">
    <option value="1" selected="{option.isSelected(#1#)}">1</option>
    <option value="2" selected="{option.isSelected(#2#)}">2</option>
    <option value="3" selected="{option.isSelected(#3#)}">3</option>
    <option value="4" selected="{option.isSelected(#4#)}">4</option>
    <option value="5" selected="{option.isSelected(#5#)}">5</option>
</select>
{end:}
{if:option.isName(#template#)}
<select name="{option.name}" onChange="UpdateSettings()">
    <option value="modules/LayoutOrganizer/list.tpl" selected="{option.isSelected(#modules/LayoutOrganizer/list.tpl#)}">List</option>
    <option value="modules/LayoutOrganizer/icons.tpl" selected="{option.isSelected(#modules/LayoutOrganizer/icons.tpl#)}">Icons</option>
    <option value="modules/LayoutOrganizer/table.tpl" selected="{option.isSelected(#modules/LayoutOrganizer/table.tpl#)}">Table</option>
</select>
{end:}
