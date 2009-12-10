{* Subcategory list (icons) *}
<table IF="category.subcategories" cellpadding="5" cellspacing="0" border="0" width="100%">
<tbody FOREACH="split(category.subcategories,4),row">
<tr>
   <td valign="middle" FOREACH="row,subcategory" align="center" width="{percent(4)}%">
      <a href="cart.php?target=category&amp;category_id={subcategory.category_id}" IF="subcategory"><span IF="subcategory.hasImage()"><img src="{subcategory.getImageURL()}" border="0" alt=""></span><span IF="!subcategory.hasImage()"><img src="images/no_image.gif" border="0" alt=""></span></a>&nbsp;
   </td>
</tr>
<tr>
   <td valign="top" FOREACH="row,subcategory" align="center" width="{percent(4)}%">
      <a href="cart.php?target=category&amp;category_id={subcategory.category_id}" IF="subcategory"><FONT class="ItemsList">{subcategory.name}</FONT></a><br><br>
   </td>
</tr>
</tbody>
</table>
