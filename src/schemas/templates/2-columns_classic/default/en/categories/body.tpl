{* Categories menu body *}
<table border=0 cellpadding=1 cellspacing=0>
<tr FOREACH="categories,category">
    <td class=CategoriesList><a href="cart.php?target=category&category_id={category.category_id}" class="SidebarItems">{category.name}</a></td>
</tr>
</table>
