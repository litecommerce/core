{if:option.isName(#template#)}
    <select name="{option.name}">
        <option value="modules/ShowcaseOrganizer/list.tpl" selected="{option.isSelected(#modules/ShowcaseOrganizer/list.tpl#)}">List</option>
        <option value="modules/ShowcaseOrganizer/icons.tpl" selected="{option.isSelected(#modules/ShowcaseOrganizer/icons.tpl#)}">Icons</option>
        <option value="modules/ShowcaseOrganizer/table.tpl" selected="{option.isSelected(#modules/ShowcaseOrganizer/table.tpl#)}">Table</option>
    </select>
{end:}

{if:option.isName(#so_columns#)}
    <select name="{option.name}">
        <option value="1" selected="{option.isSelected(#1#)}">1</option>
        <option value="2" selected="{option.isSelected(#2#)}">2</option>
        <option value="3" selected="{option.isSelected(#3#)}">3</option>
        <option value="4" selected="{option.isSelected(#4#)}">4</option>
        <option value="5" selected="{option.isSelected(#5#)}">5</option>
    </select>
{end:}

{if:option.isCheckbox()}
    <input type="checkbox" name="{option.name}" checked="{option.isChecked()}">
{end:}

