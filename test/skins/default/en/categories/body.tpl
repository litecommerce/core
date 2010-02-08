{* Categories menu body *}
<table border="0" cellpadding="2" cellspacing="0">
<tr FOREACH="categories,category">
	<td valign="bottom"><img src="images/category_arrow.gif" width="12" height="13" alt=""></td>
    <td width="100%"><a href="{buildURL(#category#,##,_ARRAY_(#category_id#^category.category_id))}" class="SidebarItems"><FONT class="CategoriesList">{category.name}</FONT></a></td>
</tr>
</table>
