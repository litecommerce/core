    {if:option.isName(#featured_products_look#)}
    <select name="{option.name}">
        <option value="modules/FeaturedProducts/featuredProducts.tpl" selected="{option.isSelected(#modules/FeaturedProducts/featuredProducts.tpl#)}">List</option>
        <option value="modules/FeaturedProducts/featuredProducts_icons.tpl" selected="{option.isSelected(#modules/FeaturedProducts/featuredProducts_icons.tpl#)}">Icons</option>
    </select>
    {end:}

