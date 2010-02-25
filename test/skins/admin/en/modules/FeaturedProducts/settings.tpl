    {if:option.isName(#featured_products_look#)}
    <select name="{option.name}">
        <option value="list" selected="{option.isSelected(#list#)}">List</option>
        <option value="icons" selected="{option.isSelected(#icons#)}">Icons</option>
    </select>
    {end:}

