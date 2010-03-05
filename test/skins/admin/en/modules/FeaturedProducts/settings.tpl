    {if:option.isName(#featured_products_look#)}
    <select name="{option.name}">
        <option value="list" selected="{option.isSelected(#list#)}">List</option>
        <option value="grid" selected="{option.isSelected(#grid#)}">Grid</option>
    </select>
    {end:}

