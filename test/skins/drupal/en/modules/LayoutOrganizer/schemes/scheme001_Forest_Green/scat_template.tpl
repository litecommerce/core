{* Subcategory list (icons) *}

<table IF="category.subcategories" cellpadding="5" cellspacing="0" border="0" width="100%">
<tbody FOREACH="split(category.subcategories,4),row">
<tr>
   <td valign="middle" FOREACH="row,subcategory" align="center" width="25%">
		<table IF="{subcategory}" border="0" cellspacing="2" cellpadding="1" style="BACKGROUND-COLOR: #8CC65D;">
		<tr>
	      <td class="DialogBox"><a href="cart.php?target=category&amp;category_id={subcategory.category_id}" IF="subcategory"><span IF="subcategory.hasImage()"><img src="{subcategory.getImageURL()}" border="0" alt=""></span><span IF="!subcategory.hasImage()"><img src="images/no_image.gif" border="0" alt=""></span></a></td>
		</tr>
		</table>

		
   </td>
</tr>
<tr>
   <td valign="top" FOREACH="row,subcategory" align="center" width="25%">
		<span IF="{subcategory}">
			<a href="cart.php?target=category&amp;category_id={subcategory.category_id}" IF="subcategory"><FONT class="ProductTitle">{subcategory.name}</FONT></a>
		</span>
   </td>
</tr>
</tbody>
</table>




