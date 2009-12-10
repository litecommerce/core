{* Icons subcategory menu body *}

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
last_data[{item.category.category_id}] = '{item.is_last}';
first_data[{item.category.category_id}] = '{item.is_first}';
previous_data[{item.category.category_id}] = '{item.previous}';
next_data[{item.category.category_id}] = '{item.next}';
depth_data[{item.category.category_id}] = '{item.depth}';
{end:}
}
</script>

<div id="submenu_{data.parent}" style="visibility: hidden; position: absolute; left: 0; top: 0; z-index: {data.zIndex};">
<table border=0 cellpadding=0 cellspacing=0 width=144>
<tr>
	<td class="SubCatBorder">
		<div FOREACH="data.items,k,item">
<script type="text/javascript" language="JavaScript">var catHref='cart.php?target=category&category_id={item.category.category_id}'; var catHref_{item.category.category_id}=catHref;</script>
			<div id="menu_{item.category.category_id}" style="position: relative;" onclick="gotoCategory(catHref_{item.category.category_id});">
{if:item.number}
    {if:item.is_last}
				<div id="table2_menu_{item.category.category_id}" class="CatSubOutLast" onmouseOver="if (!isRealOverAction(event, this)) return; show_menu('{item.category.category_id}', '{data.parent}'); change_color('{item.category.category_id}','over'); change_parents_color('{item.category.category_id}', 'over');" OnMouseOut="if (!isRealOutAction(event, this)) return; change_color('{item.category.category_id}','out'); change_parents_color('{item.category.category_id}', 'out'); close_menu();"></div>
    {else:}
                <div id="table2_menu_{item.category.category_id}" class="CatSubOut" onmouseOver="if (!isRealOverAction(event, this)) return; show_menu('{item.category.category_id}', '{data.parent}'); change_color('{item.category.category_id}','over'); change_parents_color('{item.category.category_id}', 'over');" OnMouseOut="if (!isRealOutAction(event, this)) return; change_color('{item.category.category_id}','out'); change_parents_color('{item.category.category_id}', 'out'); close_menu();"></div>
    {end:}
<script type="text/javascript" language="JavaScript">if (typeof(SubCategoryBodyB) != "undefined") { SubCategoryBodyB('table2_menu_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{data.scheme.subcat_icons}', '{item.image_url}', '{data.scheme.cat_icons}', '{data.scheme.options.subcat_icons_width.value}'); }</script>
{else:}
    {if:item.is_last}
				<div id="table1_menu_{item.category.category_id}" class="CatSubOutLast" onMouseOver="if (!isRealOverAction(event, this)) return; show_menu(0, '{data.parent}'); change_color('{item.category.category_id}','over'); change_parents_color('{item.category.category_id}', 'over');" onmouseOut="if (!isRealOutAction(event, this)) return; change_color('{item.category.category_id}','out'); change_parents_color('{item.category.category_id}', 'out'); close_menu();"></div>
    {else:}
                <div id="table1_menu_{item.category.category_id}" class="CatSubOut" onMouseOver="if (!isRealOverAction(event, this)) return; show_menu(0, '{data.parent}'); change_color('{item.category.category_id}','over'); change_parents_color('{item.category.category_id}', 'over');" onmouseOut="if (!isRealOutAction(event, this)) return; change_color('{item.category.category_id}','out'); change_parents_color('{item.category.category_id}', 'out'); close_menu();"></div>
    {end:}
<script type="text/javascript" language="JavaScript">if (typeof(SubCategoryBodyA) != "undefined") { SubCategoryBodyA('table1_menu_{item.category.category_id}', catHref, '{addslashes(item.category,#name#)}', '{data.scheme.subcat_icons}', '{item.image_url}', '{data.scheme.cat_icons}', '{data.scheme.options.subcat_icons_width.value}'); }</script>
{end:}
			</div>
		</div>
	</td>
</tr>
</table>
</div>
