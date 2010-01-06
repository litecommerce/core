{* Candy subcategory menu body *}

{if:data.scatCounter=#1#}
<script type="text/javascript" language="JavaScript" src="{data.catalogPath}/body_footer.js"></script>
{end:}

<script type="text/javascript" language="JavaScript">
if (typeof(parents) != "undefined") {
{foreach:data.items,k,item}
parents[{item.category.category_id}] = Array();
{foreach:item.path_ids,itemIndex,v}
parents[{item.category.category_id}][{itemIndex}] = {v};
{end:}
{end:}
}
</script>

<div id="submenu_{data.parent}" style="visibility: hidden; position: absolute; left: 0; top: 0; z-index: {data.zIndex};">
<table border=0 cellpadding=0 cellspacing=0 width=144>
	<tr>
		<td class="SubCatBorder">

<table FOREACH="data.items,k,item" width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td onclick="gotoCategory(catHref_{item.category.category_id});">
            <script type="text/javascript" language="JavaScript">var catHref='cart.php?target=category&category_id={item.category.category_id}'; var catHref_{item.category.category_id}=catHref;</script>
			<div id="menu_{item.category.category_id}" style="position: relative;" >

{if:item.number}
			<table  border=0 cellpadding=0 cellspacing=0 width="100%">
				<tr>
					<td class="CatTD"><div id="table2_menu_{item.category.category_id}" OnMouseOver="if (!isRealOverAction(event, this)) return; show_menu('{item.category.category_id}', '{data.parent}'); change_color_{data.scheme.options.color.value}('{item.category.category_id}','over', 'table2_menu_'); change_parents_color_{data.scheme.options.color.value}('{item.category.category_id}', 'over');" OnMouseOut="if (!isRealOutAction(event, this)) return; change_color_{data.scheme.options.color.value}('{item.category.category_id}','out', 'table2_menu_'); change_parents_color_{data.scheme.options.color.value}('{item.category.category_id}', 'out'); close_menu();"></div></td>
				</tr>
				<tr> 
					<td><img src="{data.imagesPath}{data.scheme.options.color.value}/spacer.gif" width=1 height=1 alt="" /></td>
				</tr>
			</table>
<script type="text/javascript" language="JavaScript">if (typeof(CategoryBody_{data.scheme.options.color.value}) != "undefined") { CategoryBody_{data.scheme.options.color.value}('table2_menu_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{data.scheme.subcat_icons}', '{item.image_url}', '{data.scheme.cat_icons}'); }</script>
{else:}
			<table border=0 cellpadding=0 cellspacing=0 width="100%">
				<tr>
					<td class="CatTD"><div id="table1_menu_{item.category.category_id}" onMouseOver="if (!isRealOverAction(event, this)) return; show_menu(0, '{data.parent}'); change_color_{data.scheme.options.color.value}('{item.category.category_id}','over', 'table1_menu_'); change_parents_color_{data.scheme.options.color.value}('{item.category.category_id}', 'over');" onmouseOut="if (!isRealOutAction(event, this)) return; change_color_{data.scheme.options.color.value}('{item.category.category_id}','out', 'table1_menu_'); change_parents_color_{data.scheme.options.color.value}('{item.category.category_id}', 'out'); close_menu();"></div></td>
				</tr>
				<tr>
					<td><img src="{data.imagesPath}{data.scheme.options.color.value}/spacer.gif" width=1 height=1 alt="" /></td>
				</tr>
			</table>
<script type="text/javascript" language="JavaScript">if (typeof(CategoryBody_{data.scheme.options.color.value}) != "undefined") { CategoryBody_{data.scheme.options.color.value}('table1_menu_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{data.scheme.subcat_icons}', '{item.image_url}', '{data.scheme.cat_icons}'); }</script>
{end:}

			</div>
		</td>
	</tr>
</table>

		</td>
	</tr>
</table>
</div>
