{* Categories menu body *}
<ul FOREACH="categories,category">
    <li><a href="{buildURL(#category#,##,_ARRAY_(#category_id#^category.category_id))}" class="SidebarItems"><FONT class="CategoriesList">{category.name}</FONT></a></li>
</ul>
