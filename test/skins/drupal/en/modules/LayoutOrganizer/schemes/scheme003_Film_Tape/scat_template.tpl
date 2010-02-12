{* Subcategory list (icons) *}

<table IF="category.subcategories" cellpadding="5" cellspacing="0" border="0" width="100%">
<tbody FOREACH="split(category.subcategories,4),row">
<tr>
   <td valign="middle" FOREACH="row,subcategory" align="center" width="{percent(4)}%">

		<table IF="{subcategory}" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td style="background-image:url('{xlite.layout.path}images/modules/LayoutOrganizer/film_tape/film_left.gif');background-repeat: repeat-y;"><img src="images/spacer.gif" width="13" height="1" alt=""></td>

			<td>
				<table border="0" cellspacing="1" cellpadding="1" style="BACKGROUND-COLOR: #B2B2B2;">
				<tr>
					<td class="DialogBox"><a href="cart.php?target=category&amp;category_id={subcategory.category_id}" IF="subcategory"><span IF="subcategory.hasImage()"><img src="{subcategory.getImageURL()}" border="0" alt=""></span><span IF="!subcategory.hasImage()"><img src="images/no_image.gif" border="0" alt=""></span></a></td>
				</tr>
				</table>
			</td>

			<td style="background-image:url('{xlite.layout.path}images/modules/LayoutOrganizer/film_tape/film_right.gif');background-repeat: repeat-y;"><img src="images/spacer.gif" width="13" height="1" alt=""></td>
		</tr>
		</table>

		
   </td>
</tr>
<tr>
   <td valign="top" FOREACH="row,subcategory" align="center" width="{percent(4)}%">
		<table IF="{subcategory}" cellspacing="0" cellpadding="0" width="50%">
		<tr>
			<td><img src="images/modules/LayoutOrganizer/film_tape/catname_left.gif" width="8" height="17" alt=""></td>
	      <td nowrap width="100%" align="center" style="background-image:url('{xlite.layout.path}images/modules/LayoutOrganizer/film_tape/catname_filler.gif');"><a href="cart.php?target=category&amp;category_id={subcategory.category_id}" IF="subcategory"><FONT style="FONT-WEIGHT: normal; FONT-SIZE: 11px; COLOR: #102D50; FONT-FAMILY: Verdana;">{subcategory.name}</FONT></a></td>
			<td><img src="images/modules/LayoutOrganizer/film_tape/catname_right.gif" width="8" height="17" alt=""></td>
		</tr>
		</table>
   </td>
</tr>
</tbody>
</table>




