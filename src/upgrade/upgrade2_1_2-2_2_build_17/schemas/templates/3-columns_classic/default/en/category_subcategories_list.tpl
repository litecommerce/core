{* Subcategory list *}
<table IF="category.subcategories" cellpadding="5" cellspacing="0" border="0">
<tr>
    <td valign="top" IF="category.hasImage()">
            <img src="{category.getImageURL():h}" border="0">
    </td>
    <td valign="top">
        <table FOREACH="category.subcategories,subcategory">
        <tr>
            <td>
            <a href="cart.php?target=category&category_id={subcategory.category_id}"><FONT class="ItemsList">{subcategory.name}</FONT></a>
            </td>
        </tr>    
        </table>
    </td>
</tr>
</table>
