{* Subcategory list (icons) *}

<table IF="category.subcategories" cellpadding="5" cellspacing="0" border="0" width="100%">
<tbody FOREACH="split(category.subcategories,4),row">
<tr>
   <td valign="middle" FOREACH="row,subcategory" align="center" width="25%">
		<table IF="{subcategory}" border="0" cellspacing="2" cellpadding="1" style="BACKGROUND-COLOR: #C4E1F6;">
		<tr>
	      <td class="DialogBox"><a href="cart.php?target=category&amp;category_id={subcategory.category_id}" IF="subcategory"><span IF="subcategory.hasImage()"><img src="{subcategory.getImageURL()}" border="0" alt=""></span><span IF="!subcategory.hasImage()"><img src="images/no_image.gif" border="0" alt=""></span></a></td>
		</tr>
		</table>

		
   </td>
</tr>
<tr>
   <td valign="top" FOREACH="row,subcategory" align="center" width="25%">
		<table IF="{subcategory}" cellspacing="0" cellpadding="0" width="50%">
		<tr>
			<td><img src="images/modules/LayoutOrganizer/soap_bubbles/catname_left.gif" width="10" height="20" alt=""></td>
	      <td nowrap width="100%" align="center" style="background:url('{xlite.layout.path}images/modules/LayoutOrganizer/soap_bubbles/catname_filler.gif');"><a href="cart.php?target=category&amp;category_id={subcategory.category_id}" IF="subcategory"><FONT class="ProductTitle">{subcategory.name}</FONT></a></td>
			<td><img src="images/modules/LayoutOrganizer/soap_bubbles/catname_right.gif" width="10" height="20" alt=""></td>
		</tr>
		</table>
   </td>
</tr>
</tbody>
</table>
